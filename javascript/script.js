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
        const li = e.target.closest('li');
        const id = e.target.dataset.id;
        const name = li.querySelector('[data-name-id="' + id + '"]').innerText;

        li.innerHTML += createForm(id, name);

        const form = li.querySelector('[data-form-id="' + id + '"]');

        form.addEventListener('submit', e => {
            e.preventDefault();
            renameArticle(e.target.dataset.formId, form.querySelector('input[name="articleName"]').value)
                .then(apiResponse => {
                    if (!apiResponse.result) {
                        console.error(' Erreur lors du renommage.');
                        form.remove();
                        return;
                    }

                    updateArticleName(apiResponse.idArticle, apiResponse.articleName);
                    form.remove();
                });
        });
    });
});

function updateArticleName(id, name) {
    document.querySelector(`[data-name-id="${id}"]`).innerText = name;
}


function createForm(id, name) {
    return '<form action="" method="post" data-form-id="' + id + '">'
        + '<input type="text" name="articleName" value="' + name + '">'
        + '<input type="hidden" name="idArticle" value="' + id + '">'
        + '<input type="submit" value="valider">'
        + '</form>';
}


function updatePrice(idArticle, price) {
    document.querySelector('[data-price-id="' + idArticle + '"]').innerText = price;
}

function getCsrfToken() {
    return document.querySelector('#token-csrf').value;
}

function increasePrice(idArticle) {
    const data = {
        action: 'increase',
        idArticle: idArticle,
        token: getCsrfToken()
    };

    return callAPI('PUT', data);
}

function renameArticle(idArticle, articleName) {
    const data = {
        action: 'rename',
        idArticle: idArticle,
        articleName: articleName,
        token: getCsrfToken()
    };

    return callAPI('PUT', data);
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