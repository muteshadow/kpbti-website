const servicesData = {
    individual: [
        {
            category_name: 'Квартира',
            service_name: 'Інформаційна довідка',
            image: 'img/apartment.jpg',
            document_ids: [1,2,3,4]
        },
        {
            category_name: 'Квартира',
            service_name: 'Технічний паспорт',
            image: 'img/apartment.jpg',
            document_ids: [15,17]
        },
        {
            category_name: 'Гараж',
            service_name: 'Інформаційна довідка',
            image: 'img/garage.jpg',
            document_ids: [1,2,3,4]
        },
        {
            category_name: 'Гараж',
            service_name: 'Технічний паспорт',
            image: 'img/garage.jpg',
            document_ids: [11,2,4]
        },
        {
            category_name: 'Машиномісце',
            service_name: 'Інформаційна довідка',
            image: 'img/parking.jpg',
            document_ids: [1,2,3,4]
        },
        {
            category_name: 'Машиномісце',
            service_name: 'Технічний паспорт',
            image: 'img/parking.jpg',
            document_ids: [11,2,4,13]
        },
        {
            category_name: 'Садовий будинок',
            service_name: 'Інформаційна довідка',
            image: 'img/garden.jpg',
            document_ids: [1,2,3,4]
        },
        {
            category_name: 'Садовий будинок',
            service_name: 'Технічний паспорт',
            image: 'img/garden.jpg',
            document_ids: [11,12,2,4]
        },
        {
            category_name: 'Приватний будинок',
            service_name: 'Інформаційна довідка',
            image: 'img/house.jpg',
            document_ids: [1,2,3,4]
        },
        {
            category_name: 'Приватний будинок',
            service_name: 'Технічний паспорт',
            image: 'img/house.jpg',
            document_ids: [11,12,2,4]
        },
        {
            category_name: 'Нежиле приміщення',
            service_name: 'Інформаційна довідка',
            image: 'img/warehouse.jpg',
            document_ids: [1,2,3,4]
        },
        {
            category_name: 'Нежиле приміщення',
            service_name: 'Технічний паспорт',
            image: 'img/warehouse.jpg',
            document_ids: [11,2,4]
        },
        {
            category_name: 'Захисна споруда',
            service_name: 'Інформаційна довідка',
            image: 'img/shelter.jpg',
            document_ids: [1,2,3,4]
        },
        {
            category_name: 'Захисна споруда',
            service_name: 'Технічний паспорт',
            image: 'img/shelter.jpg',
            document_ids: [11,2,4]
        }
    ],

    legal: [
        {
            category_name: 'Квартира',
            service_name: 'Інформаційна довідка',
            image: 'img/apartment.jpg',
            document_ids: [1,2,3,4,5,6,7,8,9,10]
        },
        {
            category_name: 'Квартира',
            service_name: 'Технічний паспорт',
            image: 'img/apartment.jpg',
            document_ids: [11,2,4,21,22,23,24]
        },
        {
            category_name: 'Гараж',
            service_name: 'Інформаційна довідка',
            image: 'img/garage.jpg',
            document_ids: [1,2,3,4,5,6,7,8,9,10]
        },
        {
            category_name: 'Гараж',
            service_name: 'Технічний паспорт',
            image: 'img/garage.jpg',
            document_ids: [11,2,4,21,22,23,25]
        },
        {
            category_name: 'Машиномісце',
            service_name: 'Інформаційна довідка',
            image: 'img/parking.jpg',
            document_ids: [1,2,3,4,5,6,7,8,9,10]
        },
        {
            category_name: 'Машиномісце',
            service_name: 'Технічний паспорт',
            image: 'img/parking.jpg',
            document_ids: [11,2,4,13,21,22,23,24]
        },
        {
            category_name: 'Садовий будинок',
            service_name: 'Інформаційна довідка',
            image: 'img/garden.jpg',
            document_ids: [1,2,3,4,5,6,7,8,9,10]
        },
        {
            category_name: 'Садовий будинок',
            service_name: 'Технічний паспорт',
            image: 'img/garden.jpg',
            document_ids: [11,12,2,4,21,22,23,24]
        },
        {
            category_name: 'Приватний будинок',
            service_name: 'Інформаційна довідка',
            image: 'img/house.jpg',
            document_ids: [1,2,3,4,5,6,7,8,9,10]
        },
        {
            category_name: 'Приватний будинок',
            service_name: 'Технічний паспорт',
            image: 'img/house.jpg',
            document_ids: [11,12,2,4,21,22,23,24]
        },
        {
            category_name: 'Нежиле приміщення',
            service_name: 'Інформаційна довідка',
            image: 'img/warehouse.jpg',
            document_ids: [1,2,3,4,5,6,7,8,9,10]
        },
        {
            category_name: 'Нежиле приміщення',
            service_name: 'Технічний паспорт',
            image: 'img/warehouse.jpg',
            document_ids: [11,2,4,21,22,23,24,26]
        },
        {
            category_name: 'Захисна споруда',
            service_name: 'Інформаційна довідка',
            image: 'img/shelter.jpg',
            document_ids: [1,2,3,4,5,6,7,8,9,10]
        },
        {
            category_name: 'Захисна споруда',
            service_name: 'Технічний паспорт',
            image: 'img/shelter.jpg',
            document_ids: [11,2,14,21,22,23,27,28]
        }
    ],

    documents: {
        1: 'Копія правовстановлюючого документу (документу який підтверджує право власності)',
        2: 'Документ що посвідчує особу (громадянський паспорт) замовника',
        3: 'Довіреність на представника замовника',
        4: 'Запит нотаріуса у випадку необхідності (напр. вступ в спадщину)',
        5: "Лист з проханням виготовити інформаційну довідку підписаний керівником юридичної особи яка є власником зазначеного об'єкта нерухомого майна",
        6: 'Довіреність на представника юридичної особи',
        7: 'Документ що посвідчує особу (громадянський паспорт) представника',
        8: "Документ (та його копію) що підтверджує право власності на об'єкт (свідоцтво про право власності договір купівлі-продажу міни дарування рішення суду свідоцтво про право на спадщину тощо) або право управляти майном (наказ або розпорядження чи рішення про передачу на баланс (закріплення) акт прийому-передачі чи перелік майна) (його копія) завірена печаткою та підписом керівника",
        9: 'Інформація (довідка виписка витяг) з Єдиного державного реєстру юридичних осіб фізичних осіб - підприємців та громадських формувань завірена печаткою та підписом керівника',
        10: 'При зміні назви - копію сторінок з статуту - завірений печаткою та підписом керівника',
        11: 'Оригінал правовстановлюючого документу та його копія',
        12: 'Копія акта на землю (при наявності)',
        13: 'Довідка про балансову вартість (для багатоповерхових та окремо розташованих паркінгів)',
        14: 'Ідентифікаційний код та його копія',
        15: 'Звернення органу приватизації',
        16: 'Лист заява від уповноваженого органу на виготовлення технічного паспорту',
        17: 'Громадянський паспорт власника або довірена особа з довіреністю (оригінал та копія)',
        18: 'Дві довідки від голови правління гаражного автокооперативу встановленої форми',
        19: 'План-схема приміщень які підлягають інвентаризації завірена уповноваженим органом',
        20: 'Довідка ЖКБ встановленої форми',
        21: 'Лист з проханням виготовити технічний паспорт',
        22: 'Довіреність від організації або копія протоколу призначення керівником завірені належним чином',
        23: 'Копія свідоцтва про державну реєстрацію підприємства',
        24: 'Довідка з ЄДПРОУ (статистичного управління)',
        25: 'Довідка про балансову вартість (для гаражів в багатоповерхових комплексах)'
    }
};

// =======================
//       SERVICES
// =======================

function renderServicesByType({
    type,
    containerId,
    personType
}) {
    const container = document.getElementById(containerId);
    if (!container) return;

    const services = servicesData[type];
    if (!services) return;

    const grouped = {};

    // групування
    services.forEach(item => {
        if (!grouped[item.category_name]) {
            grouped[item.category_name] = {
                image: item.image,
                services: []
            };
        }

        grouped[item.category_name].services.push(item.service_name);
    });

    // рендер
    Object.entries(grouped).forEach(([categoryName, data]) => {
        container.appendChild(
            createServiceCard({
                categoryName,
                image: data.image,
                services: data.services,
                personType
            })
        );
    });

    if (window.AOS) {
        AOS.refreshHard();
    }
}

function createServiceCard({ categoryName, image, services, personType }) {
    const card = document.createElement('div');
    card.classList.add('card');
    card.dataset.aos = 'fade-up';

    card.append(
        createCardCircle(image, categoryName),
        createCardTitle(categoryName),
        ...services.map(service =>
            createServiceLink({
                categoryName,
                service,
                personType
            })
        )
    );

    return card;
}

function createCardCircle(imageSrc, alt) {
    const circle = document.createElement('div');
    circle.classList.add('card-circle');

    const img = document.createElement('img');
    img.src = imageSrc;
    img.alt = alt;

    circle.appendChild(img);
    return circle;
}

function createCardTitle(text) {
    const title = document.createElement('h4');
    title.classList.add('card_title');
    title.textContent = text;

    return title;
}

function createServiceLink({ categoryName, service, personType }) {
    const link = document.createElement('a');
    link.classList.add('card_service');

    const url = new URL('service.html', window.location.href);
    url.searchParams.set('property', categoryName);
    url.searchParams.set('service', service);
    url.searchParams.set('personType', personType);

    link.href = url.toString();
    link.textContent = `-> ${service}`;

    return link;
}

// =======================
//       SERVICE
// =======================

function getQueryParams() {
    const params = new URLSearchParams(window.location.search);

    return {
        property: params.get('property'),
        service: params.get('service'),
        personType: params.get('personType')
    };
}

function findService({ property, service, personType }) {
    if (!servicesData[personType]) return null;

    return servicesData[personType].find(item =>
        item.category_name === property &&
        item.service_name === service
    );
}

function getDocumentsByIds(ids = []) {
    return ids
        .map(id => servicesData.documents[id])
        .filter(Boolean);
}

function renderDocuments(documents) {
    const container = document.getElementById('documentsList');
    if (!container) return;

    container.innerHTML = '';

    if (!documents.length) {
        container.textContent = 'Документи для цієї послуги не знайдено.';
        return;
    }

    documents.forEach(text => {
        container.appendChild(document.createTextNode(`-> ${text}`));
        container.appendChild(document.createElement('br'));
        container.appendChild(document.createElement('br'));
    });
}

function renderServiceImage(image) {
    const img = document.getElementById('serviceImage');
    if (!img) return;

    img.src = image || 'img/no-image.jpg';
    img.alt = 'service image';
}

document.addEventListener('DOMContentLoaded', () => {
    const params = getQueryParams();
    if (!params.property || !params.service || !params.personType) return;

    const service = findService(params);
    if (!service) return;

    const documents = getDocumentsByIds(service.document_ids);

    renderDocuments(documents);
    renderServiceImage(service.image);

    if (window.AOS) {
        AOS.refresh();
    }
});

// =======================
//       INTRO SERVICE
// =======================

function initServiceIntro() {
    const params = getQueryParams(); // { property, service, personType }
    const titleEl = document.getElementById('introTitle');
    const breadcrumbs = document.getElementById('breadcrumbs');

    if (!params.property || !params.service || !params.personType) return;

    // Заголовок сторінки
    if (titleEl) {
        titleEl.textContent = `${params.property}: ${params.service}`;
    }

    // Хлібні крихти
    if (breadcrumbs) {
        breadcrumbs.innerHTML = ''; // очищаємо контейнер

        // 1. Головна
        const homeLink = document.createElement('a');
        homeLink.href = 'index.html';
        homeLink.textContent = 'Головна ->';
        breadcrumbs.appendChild(homeLink);

        // 2. Тип особи
        const personTitle = params.personType === 'individual' ? 'Фізична особа' : 'Юридична особа';
        const personUrl = params.personType === 'individual' ? 'individual.html' : 'legal.html';
        const personLink = document.createElement('a');
        personLink.href = personUrl;
        personLink.textContent = personTitle + ' ->';
        breadcrumbs.appendChild(personLink);

        // 3. Категорія + послуга (як заголовок)
        const currentSpan = document.createElement('span');
        currentSpan.textContent = `${params.property}: ${params.service}`;
        breadcrumbs.appendChild(currentSpan);
    }
}

