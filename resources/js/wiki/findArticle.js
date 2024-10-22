/**
 * функция поиска статей, содержащих значение из поля ввода #searchInput
 *
 * @param {MouseEvent} event
 * @return {Promise<void>}
 */
export async function handleClickFindArticle(event) {
    event.preventDefault();
    const searchInput = document.getElementById('searchInput').value;

    try {
        // Отправляем запрос на сервер для поиска статей
        const response = await fetch(`/api/articles/search?keyword=${encodeURIComponent(searchInput)}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        });

        // Проверяем, успешен ли запрос
        if (!response.ok) {
            throw new Error('Ошибка при поиске статей');
        }

        // Получаем данные из ответа
        const articles = await response.json();

        // Обрабатываем и отображаем результаты
        displaySearchResults(articles, searchInput);
    } catch (error) {
        console.error('Ошибка:', error);
    }
}

window.handleClickFindArticle = handleClickFindArticle;
