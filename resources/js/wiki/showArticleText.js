/**
 * ф-ия отображения контента статьи
 *
 * @param {string} articleTitle - заголовок статьи
 * @return {Promise<void>}
 */
export async function showArticleText(articleTitle) {
    const resultContainer = document.getElementById('resultsTextContainer');
    resultContainer.style.display = 'flex';
    resultContainer.style.justifyContent = 'center';
    resultContainer.innerHTML = `
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    `;
    const contentResponse = await axios.get('/api/wiki', {
        params: {
            action: 'parse',
            page: articleTitle,
            format: 'json',
            prop: 'text',
            origin: '*',
            disableeditsection: true, // Отключаем секции редактирования
            disabletoc: true, // Отключаем таблицу содержания
        }
    });

    resultContainer.innerHTML =
        `
            <div>${contentResponse.data.parse.text['*']}</div>
        `;
}

window.showArticleText = showArticleText;
