function checkDemo() {
    if (window.DEMO_MODE) {
        alert('Неможливо в демо-версії!');
        return true;
    }
    return false;
}

// Функція відкриття модального вікна
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'block'; // Показуємо елемент (вже має бути видимим)
        setTimeout(() => {
            modal.classList.remove('hide'); // Видаляємо клас, що приховує модальне вікно
            modal.style.visibility = 'visible'; // Робимо його видимим
        }, 10); // Невелика затримка, щоб анімація спрацювала
    }
}

// Функція закриття модального вікна
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hide'); // Додаємо клас для приховування
        setTimeout(() => {
            modal.style.display = 'none'; // Закриваємо модальне вікно після анімації
            modal.style.visibility = 'hidden'; // Ховаємо його, але зберігаємо в потоці
        }, 500); // Затримка, що відповідає тривалості анімації
    }
}

// Ініціалізація модальних вікон
function initModal(modalId, openBtnId, cancelBtnClass, closeBtnClass) {
    const openBtn = document.getElementById(openBtnId);
    const modal = document.getElementById(modalId);

    if (openBtn) {
        openBtn.addEventListener('click', () => {
            openModal(modalId);
        });
    }

    if (modal) {
        // Закриття через хрестик
        const closeBtn = modal.querySelector(`.${closeBtnClass}`);
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                closeModal(modalId);
            });
        }

        // Закриття через кнопку "Скасувати"
        const cancelBtn = modal.querySelector(`.${cancelBtnClass}`);
        if (cancelBtn) {
            cancelBtn.addEventListener('click', () => {
                closeModal(modalId);
            });
        }

        // Закриття при кліку поза вікном
        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeModal(modalId);
            }
        });
    }
}

// Виклик для конкретних модальних вікон
initModal('addUserModal', 'addUserBtn', 'cancel', 'close');
initModal('editUserModal', 'editUserBtn', 'cancel', 'close');

// ==============================
//       Користувачі
// ==============================

// Обробка форми додавання користувача
document.getElementById('addUserForm').addEventListener('submit', function (e) {
    e.preventDefault();
    if (checkDemo()) return;

    const formData = new FormData(this);
    fetch('admin_add_user.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.success) {
            closeModal('addUserModal');
            this.reset();
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
});

// Відкриття модального вікна з даними користувача для редагування
function openEditUserModal(userId, name, surname, patronymic, email, role) {
    document.getElementById('editUserId').value = userId;
    document.getElementById('editName').value = name;
    document.getElementById('editSurname').value = surname;
    document.getElementById('editPatronymic').value = patronymic;
    document.getElementById('editEmail').value = email;
    document.getElementById('editRole').value = role;
    openModal('editUserModal'); // Викликаємо відкриття модального вікна
}

// Обробка форми редагування користувача
document.getElementById('editUserForm').addEventListener('submit', function (e) {
    e.preventDefault();
    if (checkDemo()) return;

    const formData = new FormData(this);
    fetch('admin_edit_user.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.success) {
            closeModal('editUserModal');
            this.reset();
            location.reload();
        }
    })
    .catch(error => console.error('Помилка:', error));
});

// Видалення користувача
function deleteUser(userId) {
    if (checkDemo()) return;

    if (confirm("Ви впевнені, що хочете видалити цього користувача та всі його замовлення?")) {
        fetch('admin_delete_user.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${userId}`
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.success) location.reload();
        })
        .catch(error => console.error('Помилка:', error));
    }
}

// ==============================
//       Замовлення
// ==============================
// Зміна статусу замовлення
document.querySelectorAll('.change-status').forEach(select => {
    select.addEventListener('change', function () {
        if (window.DEMO_MODE) {
            alert('Неможливо в демо-режимі!');
            location.reload(); // Скидаємо вибір у селекті
            return;
        }

        const card = this.closest('.card');
        const id = card.dataset.id;
        const newStatus = this.value;

        const formData = new FormData();
        formData.append('id', id);
        formData.append('status', newStatus);

        fetch('admin_edit_appointment.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.text())
        .then(() => {
            card.querySelector('.card-circle').textContent = newStatus;
        });
    });
});

// Видалення замовлення
document.querySelectorAll('.delete-appointment').forEach(btn => {
    btn.addEventListener('click', function () {
        if (checkDemo()) return;

        if (!confirm("Видалити це замовлення?")) return;

        const card = this.closest('.card');
        const id = card.dataset.id;

        const formData = new FormData();
        formData.append('id', id);

        fetch('admin_delete_appointment.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.text())
        .then(() => {
            card.remove();
        });
    });
});