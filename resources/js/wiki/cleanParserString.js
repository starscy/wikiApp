/**
 * ф-ия очищает строку от ненужных символов
 *
 * @param {string} plainText - искомая строка
 * @return {string}
 */
export function cleanParserString(plainText) {
    // Используем DOMParser для парсинга HTML
    const parser = new DOMParser();
    const doc = parser.parseFromString(plainText, 'text/html');

    // Получаем чистый текст
    const cleanText = doc.body.textContent;

    // Фильтрация нежелательной информации
    const filteredText = cleanText.split('\n').filter(line => {
        return !line.includes('У этого термина существуют и другие значения') &&
            !line.includes('Запрос «') &&
            !line.includes('перенаправляется сюда');
    }).join('\n');

    return filteredText
        .replace(/\.mw-parser-output[^}]*\{[^}]*\}/g, '') // Удаляем CSS-код
        .replace(/body\.[\w-]+/g, '') // Удаляем строки, начинающиеся с body.
        .replace(/\.skin-minerva\.skin-minerva/g, '')
        .replace(/\s+/g, ' ') // Убираем лишние пробелы
        .trim();
}

window.cleanParserString = cleanParserString;
