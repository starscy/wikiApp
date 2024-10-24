/**
 * Представляет собой пагинированный ответ для статей.
 *
 * @typedef {Object} data
 * @property {number} current_page - Номер текущей страницы.
 * @property {Array<Object>} data - Массив объектов статей.
 * @property {string} first_page_url - URL первой страницы статей.
 * @property {number} from - Начальный индекс текущей страницы.
 * @property {number} last_page - Номер последней страницы.
 * @property {string} last_page_url - URL последней страницы статей.
 * @property {Array<Object>} links - Массив объектов ссылок для пагинации.
 * @property {string|null} next_page_url - URL следующей страницы статей или null, если следующей страницы нет.
 * @property {string} path - Базовый путь для API статей.
 * @property {number} per_page - Количество статей на странице.
 * @property {string|null} prev_page_url - URL предыдущей страницы статей или null, если предыдущей страницы нет.
 * @property {number} to - Конечный индекс текущей страницы.
 * @property {number} total - Общее количество доступных статей.
 */
export function paginator(data) {
    const paginator = document.getElementById('articles_paginator');
    const paginationList = paginator.querySelector('ul');

    paginationList.innerHTML = '';

    const totalPages = data.last_page;
    const currentPage = data.current_page;

    // Кнопка назад
    const prevButton = document.createElement('li');
    prevButton.classList.add('page-item');
    if (currentPage === 1) {
        prevButton.classList.add('disabled');
    }
    prevButton.innerHTML = `<button class="page-link" onclick="loadArticles(${currentPage - 1})">Назад</button>`;
    paginationList.appendChild(prevButton);

    // Количество кнопок
    for (let i = 1; i <= totalPages; i++) {
        const pageItem = document.createElement('li');
        pageItem.classList.add('page-item');
        if (currentPage === i) {
            pageItem.classList.add('active');
        }
        pageItem.innerHTML = `<button class="page-link" onclick="loadArticles(${i})">${i}</button>`;
        paginationList.appendChild(pageItem);
    }

    // Кнопка Далее
    const nextButton = document.createElement('li');
    nextButton.classList.add('page-item');
    if (currentPage === totalPages) {
        nextButton.classList.add('disabled');
    }
    nextButton.innerHTML = `<button class="page-link" onclick="loadArticles(${currentPage + 1})">Вперед</button>`;
    paginationList.appendChild(nextButton);
}

window.paginator = paginator;
