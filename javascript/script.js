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