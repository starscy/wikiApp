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
        foreach ($words as $word) {
            // Находим или создаем слово
            $wordModel = Word::firstOrCreate(['word' => $word]);

            // Подсчитываем количество вхождений слова в тексте статьи без учета регистра
            $count = $this->countWordsInText($wordModel->word);

            // Проверяем, существует ли связь со статьей
            $existingEntry = $this->article->words()->where('word_id', $wordModel->id)->first();

            if ($existingEntry) {
                // Если связь существует, обновляем количество вхождений
                $this->article->words()->updateExistingPivot($wordModel->id, ['count' => $count]);
            } else {
                // Если связи нет, создаем новую
                $this->article->words()->attach($wordModel->id, ['count' => $count]);
            }
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

