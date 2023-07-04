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

async function increasePrice(idArticle) {
    const data = {
        action: 'increase',
        idArticle: idArticle
    };

    try {
        const response = await fetch("api.php", {
            method: 'PUT',
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
