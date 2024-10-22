/**
 * функция отображения списка статей
 *
 * @return {Promise<void>}
 */
export async function loadArticles() {
    try {
        const response = await axios.get('/api/articles');
        const articles = response.data;
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

    } catch (error) {
        console.error('Ошибка при загрузке статей:', error);
    }
}

window.loadArticles = loadArticles;
