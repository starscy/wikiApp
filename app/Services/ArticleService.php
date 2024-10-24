<?php

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
        $this->processWords($data['words'], $this->article);
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
     * Метод сохранение в базу данных количество вхождений слова
     *
     * @param array $words
     * @param Article $article
     * @return void
     */
    private function processWords(array $words, Article $article): void
    {
        foreach ($words as $word) {
            // Находим или создаем слово
            $wordModel = Word::firstOrCreate(['word' => $word]);

            // Подсчитываем количество вхождений слова в тексте статьи без учета регистра
            $count = $this->countWordsInText($wordModel);

            // Проверяем, существует ли связь со статьей
            $existingEntry = $article->words()->where('word_id', $wordModel->id)->first();

            if ($existingEntry) {
                // Если связь существует, обновляем количество вхождений
                $article->words()->updateExistingPivot($wordModel->id, ['count' => $existingEntry->pivot->count + $count]);
            } else {
                // Если связи нет, создаем новую
                $article->words()->attach($wordModel->id, ['count' => $count]);
            }
        }
    }

    /**
     * Метод подсчета количество вхождений слова в тексте статьи без учета регистра
     *
     * @param Word $word - слово
     * @return void
     */
    private function countWordsInText(Word $word): int
    {
        // Подсчитываем количество вхождений слова в тексте статьи без учета регистра
        $textLower = mb_strtolower($this->article->text);
        $wordLower = mb_strtolower($word);
        $textCleaned = preg_replace('/[^\w\s]/u', ' ', $textLower);

        return substr_count($textCleaned, $wordLower);
    }
}

