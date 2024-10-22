/**
 * функция выдает результат о парсинге статьи
 *
 * @param {string} articleUrl - адрес статьи
 * @param {number} processingTime - время обработки в секундах
 * @param {number} articleSizeInKBytes - размер статьи в килобайтах
 * @param {number} wordCount - кол-во слов
 * @param {boolean} isSaveToBD - результат записи в БД
 * @param {boolean=false} isStart - начало операции обработки статьи
 * @return {string} - html отображение результата
 */
export function showParseWikiResult(articleUrl, processingTime, articleSizeInKBytes, wordCount, isSaveToBD, isStart = false) {
    const decodedArticleUrl = decodeURIComponent(articleUrl);
    let result;
    if (isStart) {
        result = `
            <div class="border-3 p-6">
                <h3 class="mb-3">Процесс импорта начался</h3>
            </div>
        `;
        return result;
    }
    if (isSaveToBD) {
        result = `
            <div class="border-3 p-6">
                <h3 class="mb-3">Импорт завершен</h3>
                <p>Найдена статья по адресу: ${decodedArticleUrl} </p>
                <p>Время обработки: ${processingTime} сек.</p>
                <p>Размер статьи: ${articleSizeInKBytes}Kb</p>
                <p>Кол-во слов: ${wordCount}</p>
            </div>
        `;
    } else {
        result = `
            <div class="border-3 p-6">
                <h3 class="mb-3">Импорт не завершен из за ошибки</h3>
            </div>
        `;
    }

    return result;
}

window.showParseWikiResult = showParseWikiResult;
