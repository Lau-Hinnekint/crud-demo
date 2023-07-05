document.querySelectorAll('.js-btn-increase').forEach(btn => {
    btn.addEventListener('click', e => {
        increasePrice(e.target.dataset.id)
            .then(apiResponse => {
                if (!apiResponse.result) {
                    console.error('Problème avec la requête.');
                    return;
                }

                updatePrice(apiResponse.idArticle, apiResponse.price);
            });
    });
});

document.querySelectorAll('.js-btn-rename').forEach(btn => {
    btn.addEventListener('click', e => {
        e.target.classList.add('hidden');
        const li = e.target.closest('li');
        const id = e.target.dataset.id;
        const name = li.querySelector(`[data-name-id="${id}"]`).innerText;

        const form = createForm(id, name);
        li.appendChild(form);

        form.addEventListener('submit', e => {
            e.preventDefault();
            renameArticle(e.target.dataset.formId, form.querySelector('input[name="articleName"]').value)
                .then(apiResponse => {
                    if (apiResponse.result) updateArticleName(apiResponse.idArticle, apiResponse.articleName);
                    else console.error('Erreur lors du renommage.');
                    
                    form.remove();
                    document.querySelector(`.js-btn-rename[data-id="${apiResponse.idArticle}"]`).classList.remove('hidden');
                });
        });
    });
});

function updateArticleName(id, name) {
    document.querySelector(`[data-name-id="${id}"]`).innerText = name;
}

function createForm(id, name) {
    const form = document.querySelector("#renameFormTemplate").content.cloneNode(true);

    form.querySelector('[name="articleName"]').value = name;
    form.querySelector('[name="idArticle"]').value = id;
    form.querySelector('form').dataset.formId = id;
    return form.querySelector('form');
}

function updatePrice(idArticle, price) {
    document.querySelector('[data-price-id="' + idArticle + '"]').innerText = price;
}

function getCsrfToken() {
    return document.querySelector('#token-csrf').value;
}

function increasePrice(idArticle) {
    return callAPI('PUT', {
        action: 'increase',
        idArticle: idArticle,
        token: getCsrfToken()
    });
}

function renameArticle(idArticle, articleName) {
    return callAPI('PUT', {
        action: 'rename',
        idArticle: idArticle,
        articleName: articleName,
        token: getCsrfToken()
    });
}

async function callAPI(method, data) {
    try {
        const response = await fetch("api.php", {
            method: method,
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        return response.json();
    }
    catch (error) {
        console.error("Unable to load datas from the server : " + error);
    }
}