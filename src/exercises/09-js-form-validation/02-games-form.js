// 09-2: Games-style form validation (formHandler pattern)

let submitBtn = document.getElementById('submit_btn');
let gameForm = document.getElementById('game_form');
let errorSummaryTop = document.getElementById('error_summary_top');

let titleInput = document.getElementById('title');
let releaseDateInput = document.getElementById('release_date');
let genreIdInput = document.getElementById('genre_id');
let descriptionInput = document.getElementById('description');
let platformIdsInput = document.getElementsByName('platform_ids[]');
let imageInput = document.getElementById('image');

let titleError = document.getElementById('title_error');
let releaseDateError = document.getElementById('release_date_error');
let genreIdError = document.getElementById('genre_id_error');
let descriptionError = document.getElementById('description_error');
let platformIdsError = document.getElementById('platform_ids_error');
let imageError = document.getElementById('image_error');

submitBtn.addEventListener('click', onSubmitForm);

function onSubmitForm(evt) {
    evt.preventDefault();

    let data = new FormData(gameForm);
    let rules = {
        'title' : 'required|notempty|min:1|max:255',
        'release_date' : 'required|notempty',
        'genre_id' : 'required|integer',
        'description' : 'required|notempty|min:10|max:5000',
        'platform_ids' : 'required|array|min:1|max:10',
        'image' : 'required|file|image|mimes:jpg,jpeg,png|max_file_size:5242880'
    };
    let validator = new Validator(data, rules);

    if (validator.fails()) {
        let errors = validator.allErrors();
        titleError.innerHTML = errors.title ? errors.title[0] : '';
        releaseDateError.innerHTML = errors.release_date ? errors.release_date[0] : '';
        genreIdError.innerHTML = errors.genre_id ? errors.genre_id[0] : '';
        descriptionError.innerHTML = errors.description ? errors.description[0] : '';
        platformIdsError.innerHTML = errors.platform_ids ? errors.platform_ids[0] : '';
        imageError.innerHTML = errors.image ? errors.image[0] : '';
    } else {
        gameForm.submit();
    }
}
