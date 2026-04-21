const form = document.querySelector('#game_filters');
const cardsContainer = document.querySelector('.cards');
const cards = Array.from(cardsContainer.querySelectorAll('.card'));

function getFilters() {
    const titleEl = form.elements['title_filter'];
    const genreEl = form.elements['genre_filter'];
    const platformEl = form.elements['platform_filter'];
    const sortEl = form.elements['sort_by'];
    return {
        titleFilter: (titleEl.value || '').trim().toLowerCase(),
        genreFilter: genreEl.value || '',
        platformFilter: platformEl.value || '',
        sortBy: sortEl.value || 'title_asc'
    };
}

function cardMatches(card, filters) {
    const title = card.dataset.title.toLowerCase();
    const genre = card.dataset.genre;
    const platforms = card.dataset.platforms;
    const matchTitle =
        filters.titleFilter === '' || title.includes(filters.titleFilter);
    const matchGenre =
        filters.genreFilter === '' || genre === filters.genreFilter;
    const matchPlatform =
        filters.platformFilter === '' || platforms.includes(filters.platformFilter);

    return matchTitle && matchGenre && matchPlatform;
}

function sortCards(cards, sortBy) {
    const list = cards.slice();
    list.sort(function (a, b) {
        const titleA = a.dataset.title.toLowerCase();
        const titleB = b.dataset.title.toLowerCase();
        const releaseDateA = Number(a.dataset.releaseDate);
        const releaseDateB = Number(b.dataset.releaseDate);
        if (sortBy === 'release_date_desc') return releaseDateB - releaseDateA;
        if (sortBy === 'release_date_asc') return releaseDateA - releaseDateB;
        return titleA.localeCompare(titleB);
    });
    return list;
}

function applyFilters() {
    const filters = getFilters();
    cards.forEach(function (card) {
        let match = cardMatches(card, filters);
        card.classList.toggle('hidden', !match);
    });
    const visible = cards.filter(function (card) {
        return !card.classList.contains('hidden');
    });
    const sorted = sortCards(visible, filters.sortBy);
    sorted.forEach(function (card) {
        cardsContainer.appendChild(card);
    });
}

function clearFilters() {
    form.reset();
    cards.forEach(function (card) {
        card.classList.remove('hidden');
    });
    const byYear = sortCards(cards.slice(), 'year_asc');
    byYear.forEach(function (card) {
        cardsContainer.appendChild(card);
    });
}

document.getElementById('apply_filters').addEventListener('click', (e) => {
    e.preventDefault();
    applyFilters();
});
document.getElementById('clear_filters').addEventListener('click', (e) => {
    e.preventDefault();
    clearFilters();
});
