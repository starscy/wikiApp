<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home"
           aria-selected="true">Импорт статей</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile"
           aria-selected="false">Поиск</a>
    </li>
</ul>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
        <form class="mt-10" onsubmit="handleClickWiki(event)">
            <div class="mb-3 flex gap-3">
                <input type="text" class="form-control w-25" id="keyWord" placeholder="Ключевое слово">
                <button type="submit" class="btn btn-primary">Скопировать</button>
            </div>
            <div class="progress">
                <div id="progressWikiFetch" class="progress-bar w-0" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </form>

        <!-- Элемент для отображения результата -->
        <div id="result" class="mt-4 mb-4"></div>

        <!-- Данные статей-->
        <table class="table" id="articlesTableBody">
            <!-- Здесь будет содержимое таблицы -->
        </table>
        <!--пагинатор-->
        <x-paginator/>
    </div>

    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
        <form class="mt-10" onsubmit="handleClickFindArticle(event)">
            <div class="mb-3 flex gap-3">
                <input id="searchInput" type="text" class="form-control w-25"  placeholder="Название статьи">
                <button type="submit" class="btn btn-primary">Найти</button>
            </div>
        </form>
        <div class="flex gap-10">
            <div id="resultsSearchContainer" class="flex-grow-0" style="min-width: 400px;"></div>
            <div id="resultsTextContainer" class="flex-grow-1"></div>
        </div>

    </div>
</div>

<script>
    // Вызов функции loadArticles после загрузки скрипта
    document.addEventListener('DOMContentLoaded', () => {
        loadArticles();
    });
</script>
