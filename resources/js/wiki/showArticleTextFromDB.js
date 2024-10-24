/**
 * ф-ия находит в БД статьи по id и отображает их
 *
 * @param {number} id
 * @return {Promise<void>}
 */
export async function showArticleTextFromDB(id) {
    if (id === undefined) {
        return;
    }
    const resultContainer = document.getElementById('resultsTextContainer');
    resultContainer.style.display = 'flex';
    resultContainer.style.justifyContent = 'center';
    resultContainer.innerHTML = `
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    `;

    const contentResponse = await axios.get('/api/articles/show', {
        params: {
            id: id
        }
    });

    resultContainer.innerHTML =
        `
            <div>${contentResponse?.data?.text}</div>
        `;

}

window.showArticleTextFromDB = showArticleTextFromDB;

