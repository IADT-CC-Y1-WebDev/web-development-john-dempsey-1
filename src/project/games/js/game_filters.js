const form = document.querySelector('#game_filters');
const cardsContainer = document.querySelector('.cards');
const cards = Array.from(cardsContainer.querySelectorAll('.card'));

const dropdownToggle = document.getElementById('platform_dropdown_toggle');
const dropdownPanel = document.getElementById('platform_dropdown_panel');
const dropdownWrapper = dropdownToggle.closest('.platform-dropdown-wrapper');

function updateDropdownLabel() {
    const included = document.querySelectorAll('.platform-tri-checkbox[data-state="1"]').length;
    const excluded = document.querySelectorAll('.platform-tri-checkbox[data-state="2"]').length;
 
    let label = 'Platforms';
    if (included > 0 || excluded > 0) {
        const parts = [];
        if (included > 0) parts.push(included + ' ✓');
        if (excluded > 0) parts.push(excluded + ' ✗');
        label += ' (' + parts.join(' ') + ')';
    }
    dropdownToggle.textContent = label;
}

dropdownToggle.addEventListener('click', function() {
    dropdownPanel.classList.toggle('open');
});

document.addEventListener('click', function(e) {
    if (!dropdownWrapper.contains(e.target)) {
        dropdownPanel.classList.remove('open');
    }
});

function getFilters() {
    const titleEl = form.elements['title_filter'];
    const genreEl = form.elements['genre_filter'];
    const includedPlatforms = [];
    const excludedPlatforms = [];
    document.querySelectorAll('.platform-tri-checkbox').forEach(function(label) {
        const state = parseInt(label.dataset.state);
        if (state === 1) includedPlatforms.push(label.dataset.platformId);
        if (state === 2) excludedPlatforms.push(label.dataset.platformId);
    });
    const sortEl = form.elements['sort_by'];
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
    document.querySelectorAll('.platform-tri-checkbox').forEach(function(pill) {
        pill.dataset.state = '0';
    });
    updateDropdownLabel();
    cards.forEach(function (card) {
        card.classList.remove('hidden');
    });
    const byYear = sortCards(cards.slice(), 'release_date_asc');
    byYear.forEach(function (card) {
        cardsContainer.appendChild(card);
    });
}

document.querySelectorAll('.platform-tri-checkbox').forEach(function(label) {
    label.addEventListener('click', function(e) {
        e.preventDefault();
        const current = parseInt(label.dataset.state);
        label.dataset.state = (current + 1) % 3;
        updateDropdownLabel();
    });
});

document.getElementById('apply_filters').addEventListener('click', (e) => {
    e.preventDefault();
    applyFilters();
});
document.getElementById('clear_filters').addEventListener('click', (e) => {
    e.preventDefault();
    clearFilters();
});
