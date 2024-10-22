/**
 * Функция для отображения результатов поиска вхождения слова в статьи
 *
 * @param {Array<Object>} articles - Массив статей, найденных по запросу
 * @param {Object} articles[].article - Объект статьи
 * @param {string} articles[].article.id - id статьи
 * @param {string} articles[].article.title - Заголовок статьи
 * @param {string} articles[].article.url - URL статьи
 * @param {string} articles[].article.word_count - кол-о вхождений слова
 * @param {string} searchInput - Слово, по которому осуществлялся поиск
 */
export function displaySearchResults(articles, searchInput) {
    const articlesArray = [...Object.values(articles)].sort((a, b) => b.word_count - a.word_count);
    const resultsContainer = document.getElementById('resultsSearchContainer');
    resultsContainer.innerHTML =
        `
            <div class="flex gap-3">
                <h3>Найдено: ${articlesArray.length}</h3>
            </div>
        `

    if (articles.length === 0) {
        resultsContainer.innerHTML = '<p>Статьи не найдены.</p>';
        return;
    }

    const countWords = articlesArray.reduce((sum, article) => sum + article.word_count, 0);
    const title = document.createElement('div');
    title.classList.add('mb-6');
    title.innerHTML =
        `
            <p>${searchInput} - ${countWords} вхождений</p>
        `;
    resultsContainer.appendChild(title);
    articlesArray.forEach(article => {
        const articleElement = document.createElement('div');
        articleElement.innerHTML = `
            <div class="flex gap-3 align-items-center mb-3">
                <div id="showFromDB" role="button" onclick="showArticleTextFromDB()" class="flex gap-3 align-items-center">
                    <p  class="mb-0">${article.title}</p>
                    <p  class="mb-0">(${article['word_count']} вхождений)</p>
                </div>
                <button onclick="showArticleText()" class="btn btn-info">Оригинал</button>
            </div>
        `;
        articleElement.querySelector('button').addEventListener('click', () => {
            showArticleText(article.title);
        });
        articleElement.querySelector('#showFromDB').addEventListener('click', () => {
            showArticleTextFromDB(article.id);
        });
        resultsContainer.appendChild(articleElement);
    });
}

window.displaySearchResults = displaySearchResults;
