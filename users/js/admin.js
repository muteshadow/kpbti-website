document.addEventListener('DOMContentLoaded', () => {
    if (window.AOS) {
        AOS.init({ duration: 1000, once: false, mirror: true });
    }

    const usersGrid = document.getElementById('adminUsersGrid');
    const searchInput = document.getElementById('adminSearchSurname');
    const searchBtn = document.getElementById('adminSearchBtn');
    const roleFilter = document.getElementById('adminRoleFilter');
    const resetBtn = document.getElementById('adminResetFilters');

    let currentFilterSurname = '';
    let currentFilterRole = '';

    const roleLabels = {};

    function renderUsers() {
        usersGrid.innerHTML = '';
        const filteredUsers = demoUsers.filter(user => {
            const nameMatch = (user.surname + " " + user.name).toLowerCase().includes(currentFilterSurname.toLowerCase());
            const roleMatch = currentFilterRole === '' || user.role === currentFilterRole;
            return nameMatch && roleMatch;
        });

        if (filteredUsers.length === 0) {
            usersGrid.innerHTML = '<p class="title_center">Користувачів не знайдено</p>';
            return;
        }

        filteredUsers.forEach(user => {
            const card = document.createElement('div');
            card.className = 'card';
            card.setAttribute('data-aos', 'fade-up');
            
            card.innerHTML = `
                <div class="card-circle">${roleLabels[user.role] || user.role}</div>
                <h4 class="card_title">${user.surname} ${user.name} ${user.patronymic}</h4>
                <div class="card_job">${user.email}</div>
                <div class="modal_btn_container">
                    <button class="btn_documents edit-btn-trigger" data-id="${user.id}">Редагувати</button>
                    <button class="btn_documents" onclick="deleteUserDemo()">Видалити</button>
                </div>
            `;
            usersGrid.appendChild(card);
        });

        // Навішуємо події на щойно створені кнопки редагування
        document.querySelectorAll('.edit-btn-trigger').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const userId = e.target.getAttribute('data-id');
                const user = demoUsers.find(u => u.id == userId);
                if (user) fillEditModal(user);
                openModal('editUserModal');
            });
        });
    }

    // Функція заповнення полів редагування
    function fillEditModal(user) {
        document.getElementById('editUserId').value = user.id;
        document.getElementById('editName').value = user.name;
        document.getElementById('editSurname').value = user.surname;
        document.getElementById('editPatronymic').value = user.patronymic;
        document.getElementById('editEmail').value = user.email;
        document.getElementById('editRole').value = user.role;
    }

    // ЛОГІКА МОДАЛОК
    window.openModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'block'; // Показуємо елемент (вже має бути видимим)
            setTimeout(() => {
                modal.classList.remove('hide'); // Видаляємо клас, що приховує модальне вікно
                modal.style.visibility = 'visible'; // Робимо його видимим
            }, 10); // Невелика затримка, щоб анімація спрацювала
        }
    }

    window.closeModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hide'); // Додаємо клас для приховування
            setTimeout(() => {
                modal.style.display = 'none'; // Закриваємо модальне вікно після анімації
                modal.style.visibility = 'hidden'; // Ховаємо його, але зберігаємо в потоці
            }, 500); // Затримка, що відповідає тривалості анімації
        }
    }

    function setupModalEvents(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        // Хрестик
        modal.querySelector('.close')?.addEventListener('click', () => closeModal(modalId));
        
        // Кнопка Скасувати
        modal.querySelector('.cancel')?.addEventListener('click', (e) => {
            e.preventDefault();
            closeModal(modalId);
        });

        // Клік поза вікном
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal(modalId);
        });
    }

    // Ініціалізація
    document.getElementById('addUserBtn')?.addEventListener('click', () => openModal('addUserModal'));
    setupModalEvents('addUserModal');
    setupModalEvents('editUserModal');

    // Фільтри
    searchBtn.addEventListener('click', () => { currentFilterSurname = searchInput.value; renderUsers(); });
    roleFilter.addEventListener('change', () => { currentFilterRole = roleFilter.value; renderUsers(); });
    resetBtn.addEventListener('click', () => {
        searchInput.value = ''; roleFilter.value = '';
        currentFilterSurname = ''; currentFilterRole = '';
        renderUsers();
    });

    // ПЕРЕХОПЛЕННЯ ВІДПРАВКИ ФОРМ (Демо-режим)
    const handleFormSubmit = (e) => {
        e.preventDefault(); // Зупиняємо відправку на сервер
        
        // Виводимо повідомлення
        alert("Неможливо в демо-версії");
        
        // Знаходимо батьківську модалку та закриваємо її
        const modal = e.target.closest('.modal');
        if (modal) {
            closeModal(modal.id);
        }
        
        // Очищаємо форму (опціонально)
        e.target.reset();
    };

    // Прив'язуємо обробник до обох форм
    const addForm = document.getElementById('addUserForm');
    const editForm = document.getElementById('editUserForm');

    if (addForm) addForm.addEventListener('submit', handleFormSubmit);
    if (editForm) editForm.addEventListener('submit', handleFormSubmit);

    // Також додамо для кнопок "Видалити"
    window.deleteUserDemo = () => {
        alert("Неможливо видалити користувача у демо-версії.");
    };

    renderUsers();
});