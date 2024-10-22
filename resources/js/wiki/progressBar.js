/**
 * Компонент прогресс бара
 *
 * @param {string} number - число - показатель прогресса
 * @param {boolean=false} error - ошибка, если есть
 */
export function progressBar(number, error = false) {
    const progressBar = document.getElementById('progressWikiFetch');

    progressBar.setAttribute('aria-valuenow', number);
    progressBar.style.width = `${number}%`;

    if (error) {
        progressBar.classList.add('bg-danger');
    } else {
        progressBar.classList.remove('bg-danger');
    }
}

window.progressBar = progressBar;
