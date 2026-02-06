// Демо-дані користувачів
const demoUsers = [
    { id: 1, surname: 'Іваненко', name: 'Іван', patronymic: 'Іванович', email: 'ivan@example.com', role: 'user' },
    { id: 2, surname: 'Петренко', name: 'Петро', patronymic: 'Петрович', email: 'petro@example.com', role: 'worker' },
    { id: 3, surname: 'Ковальчук', name: 'Евеліна', patronymic: 'Богданівна', email: 'evelina@example.com', role: 'admin' },
    { id: 4, surname: 'Коваленко', name: 'Олена', patronymic: 'Олексіївна', email: 'olena@example.com', role: 'user' }
];

// Демо-дані замовлень
const demoAppointments = [
    { 
        id: 1, 
        user_id: 1, 
        date: '2025-05-22', 
        time: '15:00:00', 
        person_type: 'individual', 
        category: 'Квартира', 
        service: 'Інформаційна довідка', 
        status: 'завершено',
        document_ids: [1,2],
    },
    { 
        id: 2, 
        user_id: 2, 
        date: '2025-05-22', 
        time: '15:00:00', 
        person_type: 'individual', 
        category: 'Квартира', 
        service: 'Інформаційна довідка', 
        status: 'завершено',
        document_ids: [1,2],
    },
    { 
        id: 3, 
        user_id: 3, 
        date: '2025-05-22', 
        time: '15:00:00', 
        person_type: 'individual', 
        category: 'Квартира', 
        service: 'Інформаційна довідка', 
        status: 'в процесі',
        document_ids: [1,2],
    }
];

/**
 * Допоміжні функції для роботи з "базою"
 */
const DB = {
    // Отримати замовлення разом з даними про користувача (JOIN)
    getAppointmentsWithUsers: function() {
        return demoAppointments.map(app => ({
            ...app,
            user: demoUsers.find(u => u.id === app.user_id)
        }));
    },

    // Пошук за прізвищем
    filterBySurname: function(surname) {
        const users = demoUsers.filter(u => u.surname.toLowerCase().includes(surname.toLowerCase()));
        const userIds = users.map(u => u.id);
        return this.getAppointmentsWithUsers().filter(app => userIds.includes(app.user_id));
    },

    // Фільтр за статусом
    filterByStatus: function(status) {
        if (!status) return this.getAppointmentsWithUsers();
        return this.getAppointmentsWithUsers().filter(app => app.status === status);
    }
};