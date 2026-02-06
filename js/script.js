AOS.init();

$(document).ready(function () {

    // NAV-TOGGLE
    document.querySelector('.menu-toggle').addEventListener('click', () => {
        document.querySelector('.nav').classList.toggle('open');
        document.querySelector('.menu-toggle').classList.toggle('open');
    });

    // Функція відкриття модального вікна
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.remove('hide');
        modal.style.display = 'block';
    }

    // Функція закриття модального вікна
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.add('hide');
        setTimeout(() => {
            modal.style.display = 'none';
        }, 500);
    }

    // Відкриття модального вікна реєстрації
    document.getElementById('personalAccount').addEventListener('click', function () {
        openModal('registrationModal');
    });

    // Закриття модального вікна реєстрації
    document.querySelector('#registrationModal .close').addEventListener('click', function () {
        closeModal('registrationModal');
    });

    // Закриття при кліку поза вікном
    window.addEventListener('click', function (event) {
        const modal = document.getElementById('registrationModal');
        if (event.target === modal) {
            closeModal('registrationModal');
        }
    });

    // Відкриття модального вікна авторизації
    document.getElementById('authorizationAccount').addEventListener('click', function () {
        openModal('authorizationModal');
    });

    // Закриття авторизаційного модального вікна
    document.querySelector('#authorizationModal .close').addEventListener('click', function () {
        closeModal('authorizationModal');
    });

    // Закриття при кліку поза вікном
    window.addEventListener('click', function (event) {
        const modal = document.getElementById('authorizationModal');
        if (event.target === modal) {
            closeModal('authorizationModal');
        }
    });

    // Обробка форми реєстрації через AJAX
    document.getElementById("registrationForm").addEventListener("submit", function (e) {
        e.preventDefault();
        let formData = new FormData(this); // отримуємо дані з форми, включаючи email

        if (DEMO_MODE) {
            alert("Реєстрація недоступна у демо-версії.");
            return;
        }

        fetch("login/register.php", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                alert(data.message);  // виведення повідомлення користувачу
                if (data.status === "success") {
                    closeModal("registrationModal");  // закриваємо модальне вікно реєстрації
                }
            });
    });

    // Обробка форми авторизації через AJAX
    document.getElementById("authorizationForm").addEventListener("submit", function (e) {
        e.preventDefault();

        // Створення нового об'єкта FormData з усіма полями форми
        let formData = new FormData(this);

        if (DEMO_MODE) {
            alert("Авторизація недоступна у демо-версії.");
            return;
        }

        // Відправка даних через fetch
        fetch("login/login.php", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                alert(data.message);

                if (data.status === "success") {
                    window.location.href = data.redirect;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Сталася помилка при обробці запиту.");
            });
    });
});