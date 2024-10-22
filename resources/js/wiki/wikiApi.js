/**
 * ф-ия поиска статьи и передачи данных для сохранения в БД
 *
 * @param {MouseEvent} event - событие при клике на отправку формы
 * @return {Promise<void>}
 */
export async function handleClickWiki(event) {
    event.preventDefault();

    const keyWord = document.getElementById('keyWord').value;

    let data;
    const startTime = performance.now(); // Начало отсчета времени

    progressBar('0'); //прогресс бар

    const resultDiv = document.getElementById('result');
    resultDiv.innerHTML = showParseWikiResult('', 0, 0, 0, true, true);

    try {
        const response = (await axios.get('/api/wiki', {
            params: {
                action: 'query',
                list: 'search',
                srsearch: keyWord, // Используем ключевое слово как название статьи
                format: 'json',
                origin: '*'
            }
        }));
        data = response.data
    } catch (error) {
        console.error('Ошибка при запросе:', error);
    }

    progressBar('20'); //прогресс бар

    // Обработка ответа
    if (data && data.query && data.query.search.length > 0) {
        const articleTitle = data.query.search[0].title; // Берем первую найденную статью

        // Запрос для получения полного текста статьи
        try {
            const contentResponse = await axios.get('/api/wiki/parse', {
                params: {
                    page: articleTitle,
                    origin: '*', // Необходимо для CORS
                }
            });
            const contentData = contentResponse.data;

            progressBar('40'); //прогресс бар

            // Извлечение "голого" текста
            const plainText = contentData.parse.text['*'];

            // очистка строки от ненужных элементов
            const finalText = cleanParserString(plainText);

            // Разбор текста на слова
            const words = finalText
                .toLowerCase() // Приводим текст к нижнему регистру для унификации
                .replace(/[.,\/#!$%\^&\*;:{}=\-\—_`~()\-]/g, '') // Удаляем знаки препинания и дефисы
                .split(/\s+/) // Разделяем текст на слова
                .filter(Boolean); // Фильтруем пустые строки

            // Получение информации о статье
            const articleUrl = `https://ru.wikipedia.org/wiki/${encodeURIComponent(articleTitle)}`; // Формируем URL статьи
            const processingTime = ((performance.now() - startTime) / 1000).toFixed(2); // Время обработки в секундах
            const articleSizeInBytes = finalText.length; // Размер статьи в символах (байтах)
            const articleSizeInKBytes = (articleSizeInBytes / 1024).toFixed(2); // Размер статьи в КБ
            const wordCount = words.length;

            progressBar('60'); //прогресс бар
            // сохранения filteredText в базу данных
            const resultSaveData = await saveToDatabase(articleTitle, articleUrl, finalText, words, articleSizeInKBytes, wordCount);

            // Отображение списка статей
            await loadArticles();

            // Отображение результата
            resultDiv.innerHTML = showParseWikiResult(articleUrl, processingTime, articleSizeInKBytes, wordCount, resultSaveData);

            resultSaveData ? progressBar('100') : progressBar('100', true); //прогресс бар
        } catch (error) {
            console.error('Ошибка при получении текста статьи:', error);
            progressBar('100', true); //прогресс бар
        }
    } else {
        console.log('Нет данных в ответе.');
        progressBar('100', true); //прогресс бар
        resultDiv.innerHTML = showParseWikiResult('', 0, 0, 0, false, false);
    }
}

window.handleClickWiki = handleClickWiki;
