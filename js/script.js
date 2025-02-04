AOS.init();

$(document).ready(function () {

    // NAV-TOGGLE
    document.querySelector('.menu-toggle').addEventListener('click', () => {
        document.querySelector('.nav').classList.toggle('open');
    });

    // REGISTRATION MODAL
    // Открыть модальное окно с анимацией
    document.getElementById('personalAccount').addEventListener('click', function () {
        const modal = document.getElementById('registrationModal');
        modal.classList.remove('hide'); // Удаляем класс скрытия
        modal.style.display = 'block'; // Показываем окно
    });

    // Закрыть модальное окно с анимацией
    document.querySelector('.modal .close').addEventListener('click', function () {
        const modal = document.getElementById('registrationModal');
        modal.classList.add('hide'); // Добавляем класс скрытия
        setTimeout(() => {
            modal.style.display = 'none'; // Скрываем окно после завершения анимации
        }, 500); // Время должно совпадать с длительностью анимации
    });

    // Закрыть модальное окно при клике вне его с анимацией
    window.addEventListener('click', function (event) {
        const modal = document.getElementById('registrationModal');
        if (event.target === modal) {
            modal.classList.add('hide'); // Добавляем класс скрытия
            setTimeout(() => {
                modal.style.display = 'none'; // Скрываем окно после завершения анимации
            }, 500); // Время должно совпадать с длительностью анимации
        }
    });

    // Обработчик отправки формы
    document.getElementById('registrationForm').addEventListener('submit', function (event) {
        event.preventDefault(); // Предотвратить отправку формы
        alert('Реєстрація успішна!');
        const modal = document.getElementById('registrationModal');
        modal.classList.add('hide'); // Добавляем класс скрытия
        setTimeout(() => {
            modal.style.display = 'none'; // Скрываем окно после завершения анимации
        }, 500); // Время должно совпадать с длительностью анимации
    });

    // AUTHORIZATION MODAL
    // Открыть модальное окно с анимацией
    document.getElementById('authorizationAccount').addEventListener('click', function () {
        const modal = document.getElementById('authorizationModal');
        modal.classList.remove('hide'); // Удаляем класс скрытия
        modal.style.display = 'block'; // Показываем окно
    });

    // Закрыть модальное окно с анимацией
    document.querySelector('.modal .authorizationСlose').addEventListener('click', function () {
        const modal = document.getElementById('authorizationModal');
        modal.classList.add('hide'); // Добавляем класс скрытия
        setTimeout(() => {
            modal.style.display = 'none'; // Скрываем окно после завершения анимации
        }, 500); // Время должно совпадать с длительностью анимации
    });

    // Закрыть модальное окно при клике вне его с анимацией
    window.addEventListener('click', function (event) {
        const modal = document.getElementById('authorizationModal');
        if (event.target === modal) {
            modal.classList.add('hide'); // Добавляем класс скрытия
            setTimeout(() => {
                modal.style.display = 'none'; // Скрываем окно после завершения анимации
            }, 500); // Время должно совпадать с длительностью анимации
        }
    });

    // Обработчик отправки формы
    document.getElementById('authorizationForm').addEventListener('submit', function (event) {
        event.preventDefault(); // Предотвратить отправку формы
        alert('Реєстрація успішна!');
        const modal = document.getElementById('authorizationModal');
        modal.classList.add('hide'); // Добавляем класс скрытия
        setTimeout(() => {
            modal.style.display = 'none'; // Скрываем окно после завершения анимации
        }, 500); // Время должно совпадать с длительностью анимации
    });

});