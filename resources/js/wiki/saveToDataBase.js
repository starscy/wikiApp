/**
 * Функция для сохранения данных в базу данных
 *
 * @param {string} title - заголовок статьи
 * @param {string} articleUrl - url статьи
 * @param {string} text - текст статьи
 * @param {string[]} words - массив слов из статьи
 * @param {float} size - размер файла в КБ
 * @return {Promise<boolean>} - ответ Promise с true или false
 */
export async function saveToDatabase(title, articleUrl, text, words, size) {
    try {
        await axios.post('/api/articles/save', {
            title: title,
            url: articleUrl,
            text: text,
            words: words,
            size: size,
            words_count: words.length
        });
        console.log('Данные успешно сохранены в базу данных.');
        return true;
    } catch (error) {
        progressBar('100', true); //прогресс бар
        console.error('Ошибка при сохранении данных в базу данных:', error);
        return false;
    }
}

window.saveToDatabase = saveToDatabase;
