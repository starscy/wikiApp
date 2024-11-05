<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Article;
use App\Models\Word;


/**
 * Сервис для работы со статьей
 */
class ArticleService
{
    /**
     * @var Article - статья
     */
    private Article $article;

    /**
     * Метод по создание статьи и добавлением связей
     *
     * @param array $data
     * @return Article
     */
    public function createArticle(array $data): Article
    {
        $this->article = $this->createNewArticle($data);
        $this->saveWordsToBD($data['words']);
        return $this->article;
    }

    /**
     * Создание новой статьи
     *
     * @param array $data
     * @return Article
     */
    public function createNewArticle(array $data): Article
    {
        try {
            return Article::create([
                'title' => $data['title'],
                'url' => $data['url'],
                'text' => $data['text'],
                'words' => json_encode($data['words']),
                'size' => $data['size'],
                'words_count' => $data['words_count'],
            ]);
        } catch (\Exception $e) {
            \Log::error('Article creation failed: ' . $e->getMessage());
            throw new \RuntimeException('Article creation failed. Please try again later.');
        }
    }

    /**
     * Метод сохранение в базу слов и обновление данных количество вхождений слова в таблице article_word
     *
     * @param array $words
     * @param Article $article
     * @return void
     */
    private function saveWordsToBD(array $words): void
    {
        $wordCounts = [];
        $wordIds = [];

        foreach ($words as $word) {
            // Находим или создаем слово
            $wordModel = Word::firstOrCreate(['word' => $word]);
            $wordIds[] = $wordModel->id;

            // Подсчитываем количество вхождений слова в тексте статьи без учета регистра
            $count = $this->countWordsInText($wordModel->word);
            $wordCounts[$wordModel->id] = $count;
        }

        // Получаем существующие связи со статьей
        $existingEntries = $this->article->words()->whereIn('word_id', $wordIds)->get()->keyBy('word_id');

        $attachData = [];
        $updateData = [];

        foreach ($wordIds as $wordId) {
            $count = $wordCounts[$wordId];

            if (isset($existingEntries[$wordId])) {
                $currentCount = $existingEntries[$wordId]->pivot->count; // Получаем текущее количество
                // Если связь существует, добавляем в массив для обновления
                $updateData[] = [
                    'word_id' => $wordId,
                    'count' => (int)$currentCount + (int)($count) ,
                ];
            } else {
                // Если связи нет, добавляем в массив для вставки
                $attachData[] = [
                    'word_id' => $wordId,
                    'count' => $count,
                ];
            }
        }

        // Пакетная вставка новых записей
        if (!empty($attachData)) {
            $this->article->words()->attach($attachData);
        }

        // Пакетное обновление существующих записей
        foreach ($updateData as $data) {
            $this->article->words()->updateExistingPivot($data['word_id'], ['count' => $data['count']]);
        }

    }

    /**
     * Метод подсчета количество вхождений слова в тексте статьи без учета регистра
     *
     * @param string $word - слово
     * @return int
     */
    private function countWordsInText(string $word): int
    {
        // Приводим текст и слово к нижнему регистру
        $textLower = mb_strtolower($this->article->text);
        $wordLower = mb_strtolower($word);

        // Используем регулярное выражение для подсчета вхождений слова
        // \b обозначает границы слова
        $pattern = '/\b' . preg_quote($wordLower, '/') . '\b/u';

        // Подсчитываем количество вхождений
        preg_match_all($pattern, $textLower, $matches);

        return count($matches[0]);
    }
}

