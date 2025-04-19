const favouritesBtn = document.querySelector('.favourites-btn')

favouritesBtn.addEventListener('click', async (e) => {
    const userId = favouritesBtn.dataset.user
    const jobId = favouritesBtn.dataset.job

    await fetch('http://localhost/praksa-projekat-1/src/Pages/favouritesEndpoints.php', {
        method: 'POST',
        body: JSON.stringify({
            userId: userId,
            jobId: jobId,
        })
    })

    if (favouritesBtn.innerHTML.includes('favorite')) {
        favouritesBtn.innerHTML = '<span class="material-symbols-outlined">heart_plus</span>'
    }
    else {
        favouritesBtn.innerHTML = '<span class="material-symbols-outlined">favorite</span>'
    }
})