document.addEventListener('introShortLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    const newsId = params.get('id'); // отримуємо id

    if (newsId !== null && news[newsId]) {
        const item = news[newsId];

        initIntroShortForNews(item);
        
        document.getElementById('newsTitle').textContent = item.title;
        document.getElementById('newsDate').textContent = formatDate(item.date);
        document.getElementById('newsImage').src = item.image;
        document.getElementById('newsText').textContent = item.full || item.text;
    }
});
