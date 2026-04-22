const form = document.querySelector('#game_filters');
const cardsContainer = document.querySelector('.cards');
const cards = Array.from(cardsContainer.querySelectorAll('.card'));

function getFilters() {
    const titleEl = form.elements['title_filter'];
    const genreEl = form.elements['genre_filter'];
    const dropdown = document.querySelector('tri-state-dropdown');
    const { included: includedPlatforms, excluded: excludedPlatforms } = dropdown.getSelections();    const sortEl = form.elements['sort_by'];

    return {
        titleFilter: (titleEl.value || '').trim().toLowerCase(),
        genreFilter: genreEl.value || '',
        includedPlatforms,
        excludedPlatforms,
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
    
    const gamePlatforms = card.dataset.platforms.split(',');

    // Include ANY: show if game is on at least one included platform
    const matchInclude =
        filters.includedPlatforms.length === 0 ||
        filters.includedPlatforms.some(id => gamePlatforms.includes(id));

    // Exclude ANY: hide if game is on at least one excluded platform
    const matchExclude =
        filters.excludedPlatforms.length === 0 ||
        !filters.excludedPlatforms.some(id => gamePlatforms.includes(id));

    return matchTitle && matchGenre && matchInclude && matchExclude;
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
    document.querySelector('tri-state-dropdown').reset();
    cards.forEach(function (card) {
        card.classList.remove('hidden');
    });
    const byYear = sortCards(cards.slice(), 'release_date_asc');
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
