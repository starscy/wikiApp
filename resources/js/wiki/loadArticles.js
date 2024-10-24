import {renderArticles} from "./renderArticles.js";

/**
 * функция отображения списка статей
 *
 * @return {Promise<void>}
 */
export async function loadArticles(page = 1) {
    try {
        const response = await axios.get(`/api/articles?per_page=10&page=${page}`);

        //рендерим статьи
        renderArticles(response.data.data);

        //рендерим пагиноатор
        paginator(response.data);
    } catch (error) {
        console.error('Ошибка при загрузке статей:', error);
    }
}

window.loadArticles = loadArticles;
