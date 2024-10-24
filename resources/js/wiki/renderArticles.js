/**
 * Рендеринг на странице списка статей
 *
 * @param {Array<Object>} articles - Массив статей, найденных по запросу
 * @param {Object} articles[].article - Объект статьи
 * @param {string} articles[].article.id - id статьи
 * @param {string} articles[].article.title - Заголовок статьи
 * @param {string} articles[].article.url - URL статьи
 * @param {string} articles[].article.word_count - кол-о вхождений слова
 * @param {string} articles[].article.created_at - дата создания
 * @param {string} articles[].article.updated_at - дата обновления
 */
export function renderArticles(articles) {
    const articlesTableBody = document.getElementById('articlesTableBody');

    if (!articlesTableBody) {
        console.error('Элемент articlesTableBody не найден');
        return;
    }

    if (articles.length === 0) {
        articlesTableBody.innerHTML = 'Статьи не найдены';
        return;
    }

    articlesTableBody.innerHTML = '';

    const headerRow = document.createElement('tr');
    headerRow.classList.add('bg-secondary');
    headerRow.innerHTML =
        `
                <thead>
                    <tr>
                        <th>Название</th>
                        <th>Ссылка</th>
                        <th>Размер (КБ)</th>
                        <th>Количество слов</th>
                    </tr>
                </thead>
            `;
    articlesTableBody.appendChild(headerRow);
    articles.forEach((article, index) => {
        const row = document.createElement('tr');
        if (index % 2 === 0) {
            row.classList.add('bg-light')
        }
        row.innerHTML = `
                <td>${article.title}</td>
                <td>${decodeURIComponent(article.url)}</td>
                <td>${article.size}</td>
                <td>${article.words_count}</td>
            `;
        articlesTableBody.appendChild(row);
    });
}

window.renderArticles = renderArticles;
