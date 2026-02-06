document.addEventListener("DOMContentLoaded", async () => {
    // await load("header", "assets/header.html");

    const page = document.body.dataset.page;

    if (page === 'home') {
        await load("intro", "assets/intro.html");
    }

    if (page === 'inner') {
        await load("intro-short", "assets/intro-short.html");
        document.dispatchEvent(new Event('introShortLoaded'));
        // завжди ініціалізуємо коротке інтро
        if (typeof initIntroShort === 'function') {
            initIntroShort();
        }

        // initServiceIntro тільки для service.html і якщо функція визначена
        if (window.location.pathname.endsWith('service.html') && typeof initServiceIntro === 'function') {
            initServiceIntro();
        }
    }

    await load("footer", "assets/footer.html");

    initHeaderEvents();
    initFooter();
    renderNews();
    renderAllNews();

    // --- Тільки після всіх вставок ініціалізуємо AOS ---
    if (window.AOS) {
        AOS.init({ duration: 1000, once: false, mirror: true });
    }

    // --- активні посилання ---
    const currentPage = window.location.pathname.split('/').pop();
    document.querySelectorAll('.nav a').forEach(link => {
        const linkPage = link.getAttribute('href');
        if (linkPage === currentPage) link.classList.add('active');
    });

    // NAV-TOGGLE
    document.querySelector('.menu-toggle').addEventListener('click', () => {
        document.querySelector('.nav').classList.toggle('open');
        document.querySelector('.menu-toggle').classList.toggle('open');
    });
});

async function load(id, file) { 
    const container = document.getElementById(id);
    if (!container) return;

    const res = await fetch(file);
    container.innerHTML = await res.text();
}

function initHeaderEvents() {
    const header = document.getElementById('header');
    if (!header) return;

    // меню
    const toggle = header.querySelector('.menu-toggle');
    const nav = header.querySelector('.nav');
    if (toggle && nav) {
        toggle.addEventListener('click', () => {
            nav.classList.toggle('open');
            toggle.classList.toggle('open');
            document.body.classList.toggle('menu-open');
        });
    }

    // модалки
    header.querySelectorAll('[data-modal]').forEach(btn => {
        btn.addEventListener('click', e => {
            const modalId = btn.dataset.modal;
            const modal = document.getElementById(modalId);
            if (modal) modal.style.display = 'block';
        });
    });

    // закриття модалок
    header.querySelectorAll('.close').forEach(closeBtn => {
        closeBtn.addEventListener('click', () => {
            const modal = closeBtn.closest('.modal');
            if (modal) modal.style.display = 'none';
        });
    });
}

function initFooter() {
    const year = document.getElementById('year');
    if (year) {
        year.textContent = new Date().getFullYear();
    }
}

// Intro-short
function initIntroShort() {
    const body = document.body;

    const title = body.dataset.pageTitle;
    const parentTitle = body.dataset.parentTitle;
    const parentUrl = body.dataset.parentUrl;
    const date = body.dataset.pageDate;

    const titleEl = document.getElementById('introTitle');
    const dateEl = document.getElementById('introDate');
    const breadcrumbs = document.getElementById('breadcrumbs');

    if (titleEl && title) {
        titleEl.textContent = title;
    }

    if (dateEl && date) {
        dateEl.textContent = formatDate(date);
        dateEl.hidden = false;
    }

    if (breadcrumbs) {
        if (parentTitle && parentUrl) {
            const parentLink = document.createElement('a');
            parentLink.href = parentUrl;
            parentLink.textContent = parentTitle + '->';
            breadcrumbs.appendChild(parentLink);
        }

        if (title) {
            const span = document.createElement('span');
            span.textContent = title;
            breadcrumbs.appendChild(span);
        }
    }
}

// Intro-short для новини
function initIntroShortForNews(item, id) {
    const titleEl = document.getElementById('introTitle');
    const dateEl = document.getElementById('introDate');
    const breadcrumbs = document.getElementById('breadcrumbs');

    if (titleEl) {
        titleEl.textContent = `Новини: ${item.title}`;
    }

    if (dateEl) {
        dateEl.textContent = formatDate(item.date);
        dateEl.hidden = false;
    }

    if (breadcrumbs) {
        const newsLink = document.createElement('a');
        newsLink.href = 'news.html';
        newsLink.textContent = 'Новини->';

        const current = document.createElement('span');
        current.textContent = item.title;

        breadcrumbs.appendChild(newsLink);
        breadcrumbs.appendChild(current);
    }
}

// --- функція для підстановки даних у HTML ---
function renderNews() {
    const newsContainer = document.getElementById('newsContainer');
    if (!newsContainer) return;

    const shortNews = getShortNews();
    const template = newsContainer.querySelector('.new_item');

    newsContainer.innerHTML = ''; // очищаємо контейнер, щоб динамічно додавати всі

    shortNews.forEach((item) => {
        const clone = template.cloneNode(true);

        clone.querySelector('img').src = item.image;
        clone.querySelector('h4').textContent = item.title;
        clone.querySelector('.new_data').textContent = item.formattedDate;
        clone.querySelector('.new_text').textContent = item.shortText;
        clone.querySelector('a').href = `new.html?id=${item.id}`;

        newsContainer.appendChild(clone);
    });

    AOS.refresh();
}

// News
function renderAllNews() {
    const container = document.getElementById('allNewsContainer');
    if (!container) return;

    const template = container.querySelector('.new_item');
    container.innerHTML = '';

    news.forEach((item, index) => {
        const clone = template.cloneNode(true);

        clone.querySelector('img').src = item.image;
        clone.querySelector('img').alt = item.title;
        clone.querySelector('.new_title').textContent = item.title;
        clone.querySelector('.new_data').textContent = formatDate(item.date);
        clone.querySelector('.new_text').textContent = truncateText(item.text || item.full, 50);
        clone.querySelector('a').href = `new.html?id=${index}`;

        container.appendChild(clone);
    });

    AOS.refresh();
}

// About
document.addEventListener("DOMContentLoaded", () => {
    renderCards("leaders", leaders);
    renderCards("employees", employees);
    renderCards("webResponsibles", webResponsibles);

    AOS.refresh();
});

// Documents
document.addEventListener('DOMContentLoaded', () => {
  renderDocuments('normativeDocs', normativeDocs);
  renderDocuments('orderSamples', orderSamples);
  renderDocuments('servicePrices', servicePrices);

  if (window.AOS) {
    AOS.init();
  }
});

// Individual / Legal
document.addEventListener('DOMContentLoaded', () => {
    const pageTitle = document.body.dataset.pageTitle;

    if (!pageTitle || typeof renderServicesByType !== 'function') return;

    if (pageTitle === 'Для фізичних осіб') {
        renderServicesByType({
            type: 'individual',
            containerId: 'servicesContainer',
            personType: 'individual'
        });
    }

    if (pageTitle === 'Для юридичних осіб') {
        renderServicesByType({
            type: 'legal',
            containerId: 'servicesContainer',
            personType: 'legal'
        });
    }
});

