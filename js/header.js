// OPEN MODAL
document.addEventListener('click', (e) => {
    if (e.target.closest('#personalAccount')) {
        openModal('registrationModal');
    }

    if (e.target.closest('#authorizationAccount')) {
        openModal('authorizationModal');
    }

    if (e.target.closest('.modal .close')) {
        const modal = e.target.closest('.modal');
        closeModal(modal.id);
    }
});

// CLOSE ON BACKDROP
document.addEventListener('click', (e) => {
    if (e.target.classList.contains('modal')) {
        closeModal(e.target.id);
    }
});

// FUNCTIONS
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    modal.classList.remove('hide');
    modal.style.display = 'block';
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    modal.classList.add('hide');
    setTimeout(() => {
        modal.style.display = 'none';
    }, 500);
}

// Alert for buttons
document.addEventListener('submit', (e) => {
    if (
        e.target.id === 'registrationForm' ||
        e.target.id === 'authorizationForm'
    ) {
        e.preventDefault();

        alert('Функціонал авторизації та реєстрації наразі недоступний у демо-версії.');
    }
});

// Зміна тексту кнопки
document.addEventListener('submit', (e) => {
    if (e.target.matches('.modalForm')) {
        e.preventDefault();

        const btn = e.target.querySelector('button[type="submit"]');
        btn.textContent = 'Недоступно в демо';
        btn.disabled = true;
    }
});
