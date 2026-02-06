// Керівництво
const leaders = [
    {
        name: "Ім'я Прізвище",
        job: 'Керівник підприємства',
        text: 'Забезпечує ефективну роботу КП "Бюро технічної інвентаризації", приймає стратегічні рішення, контролює діяльність усіх підрозділів.',
        image: 'https://i.pinimg.com/1200x/d8/b1/9e/d8b19e387ec59574ec9c1db509bb35fe.jpg'
    },
    {
        name: "Ім'я Прізвище",
        job: 'Головний бухгалтер',
        text: 'Веде бухгалтерський облік, контролює фінансові операції, готує звіти та забезпечує дотримання фінансових правил.',
        image: 'https://i.pinimg.com/1200x/2a/23/96/2a2396b47eb0b141f8d80accfb64fab6.jpg'
    },
    {
        name: "Ім'я Прізвище",
        job: 'Головний технік',
        text: 'Координує технічні процеси, відповідає за якість технічної документації та дотримання стандартів на підприємстві.',
        image: ''
    }
];

// Працівники
const employees = [
    {
        name: "Ім'я Прізвище",
        job: 'Інженер',
        text: 'Виконує інвентаризацію нерухомого майна, складає технічні паспорти та забезпечує точність вимірювань.',
        image: ''
    },
    {
        name: "Ім'я Прізвище",
        job: 'Бухгалтер',
        text: 'Веде бухгалтерський облік, здійснює фінансові розрахунки, контролює податкову звітність та забезпечує точність фінансових даних.',
        image: ''
    }
];

// Відповідальні за сайт
const webResponsibles = [
    {
        name: "Mute shadow",
        job: 'Frontend Developer',
        text: 'Відповідає за дизайн та верстку.',
        image: 'https://avatars.githubusercontent.com/u/145163620?v=4'
    }
];

function renderCards(containerId, data) {
    const container = document.getElementById(containerId);
    if (!container || !Array.isArray(data)) return;

    container.innerHTML = "";

    data.forEach(item => {
        const card = document.createElement("div");
        card.className = "card";
        card.setAttribute("data-aos", "fade-up");
        card.setAttribute("data-aos-duration", "1000");

        // circle
        const circle = document.createElement("div");
        circle.className = "card-circle";

        const icon = document.createElement("i");
        icon.className = "fa-solid fa-user";

        if (item.image) {
            const img = document.createElement("img");
            img.src = item.image;
            img.alt = item.name;

            img.onerror = () => {
                img.remove();
                icon.style.display = "block";
            };

            icon.style.display = "none";
            circle.appendChild(img);
        }

        circle.appendChild(icon);

        // name
        const title = document.createElement("h4");
        title.className = "card_title";
        title.textContent = item.name;

        // job
        const job = document.createElement("div");
        job.className = "card_job";
        job.textContent = item.job;

        // text
        const text = document.createElement("p");
        text.className = "card_text";
        text.textContent = item.text;

        card.append(circle, title, job, text);
        container.appendChild(card);
    });

    AOS.refresh();
}