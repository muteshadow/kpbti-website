document.addEventListener('DOMContentLoaded', () => {
    AOS.init();

    const usersContainer = document.getElementById('usersContainer');
    const surnameInput = document.querySelector('.selection_container input[type="text"]');
    const surnameBtn = document.querySelector('.selection_container .search-button');
    const statusSelect = document.querySelector('.selection_container select');
    const statusBtn = document.querySelectorAll('.selection_container .search-button')[1];
    const clearBtn = document.querySelector('.selection_container .btn');

    let filterSurname = '';
    let filterStatus = '';
    let filterDate = '';

    function formatDate(dateStr) {
        const monthsUa = [
            "січня", "лютого", "березня", "квітня", "травня", "червня",
            "липня", "серпня", "вересня", "жовтня", "листопада", "грудня"
        ];
        const d = new Date(dateStr);
        return `${String(d.getDate()).padStart(2,'0')} ${monthsUa[d.getMonth()]} ${d.getFullYear()}`;
    }

    function getDocumentNames(documentIds = []) {
        return documentIds
            .map(id => servicesData.documents[id])
            .filter(Boolean); // Отримуємо об'єкти документів
    }

    function getStatusClass(status) {
        switch(status) {
            case 'в процесі': return 'status-process';
            case 'скасовано': return 'status-cancelled';
            case 'завершено': return 'status-completed';
            default: return '';
        }
    }

    function renderAppointments() {
        usersContainer.innerHTML = '';

        let appointments = DB.getAppointmentsWithUsers();

        // Фільтр по прізвищу
        if (filterSurname) {
            const userIds = demoUsers
                .filter(u => u.surname.toLowerCase().includes(filterSurname.toLowerCase()))
                .map(u => u.id);
            appointments = appointments.filter(a => userIds.includes(a.user.id));
        }

        // Фільтр по статусу
        if (filterStatus) {
            appointments = appointments.filter(a => a.status === filterStatus);
        }

        // Фільтр по даті
        if (filterDate) {
            appointments = appointments.filter(a => a.date === filterDate);
        }

        if (appointments.length === 0) {
            usersContainer.innerHTML = `<p class="section_subtitle title_center" data-aos="fade-up" data-aos-duration="1000">
                Замовлень не знайдено.
            </p>`;
            return;
        }

        // Групування по користувачах
        const usersMap = {};
        appointments.forEach(appt => {
            const key = appt.user.email;
            if (!usersMap[key]) {
                usersMap[key] = { ...appt.user, appointments: [] };
            }
            usersMap[key].appointments.push(appt);
        });

        Object.values(usersMap).forEach(user => {
            const userSection = document.createElement('div');
            userSection.classList.add('section', 'section-short');

            let html = `
            <div class="container">
                <h3 class="section_subtitle" data-aos="fade-up" data-aos-duration="1000">
                    ${user.surname} ${user.name} ${user.patronymic} <br>
                    <span class="user_email">email: ${user.email}</span>
                </h3>
                <div class="appointments-grid appointments-grid-workers">
            `;

            user.appointments.forEach(appt => {
                html += `
                <div class="appointment-card" data-aos="fade-up" data-aos-duration="1000">
                    <div class="appointment_date">${formatDate(appt.date)}</div>
                    <div class="time_type_container">
                        <div class="appointment_time">${appt.time.slice(0,5)}</div>
                        <div class="appointment_type">${appt.person_type === 'individual' ? 'Фізична особа' : 'Юридична особа'}</div>
                    </div>
                    <div class="appointment-service">
                        ${appt.category ? appt.category + ': ' : ''}${appt.service}
                    </div>
                    ${appt.document_ids && appt.document_ids.length ? `
                        <div class="appointment-documents">
                            ${getDocumentNames(appt.document_ids)
                                .map(name => `<p>-> ${name}</p>`)
                                .join('')}
                        </div>
                    ` : ''}
                    <div class="appointment-status ${getStatusClass(appt.status)}">
                        <span class="status-icon">
                            ${appt.status === 'в процесі' ? '<i class="fa-regular fa-clock"></i>' :
                              appt.status === 'скасовано' ? '<i class="fa-regular fa-circle-xmark"></i>' :
                              '<i class="fa-regular fa-circle-check"></i>'}
                        </span>
                        ${appt.status.charAt(0).toUpperCase() + appt.status.slice(1)}
                    </div>
                    <form class="status-form">
                        <select>
                            <option value="в процесі" ${appt.status === 'в процесі' ? 'selected' : ''}>В процесі</option>
                            <option value="скасовано" ${appt.status === 'скасовано' ? 'selected' : ''}>Скасовано</option>
                            <option value="завершено" ${appt.status === 'завершено' ? 'selected' : ''}>Завершено</option>
                        </select>
                        <button class="btn_documents" type="button">Зберегти</button>
                    </form>
                </div>
                `;
            });

            html += '</div></div>';
            userSection.innerHTML = html;
            usersContainer.appendChild(userSection);
        });
    }

    // Події фільтрів
    surnameBtn.addEventListener('click', e => {
        e.preventDefault();
        filterSurname = surnameInput.value.trim();
        renderAppointments();
    });

    statusBtn.addEventListener('click', e => {
        e.preventDefault();
        filterStatus = statusSelect.value;
        renderAppointments();
    });

    clearBtn.addEventListener('click', e => {
        e.preventDefault();
        filterSurname = '';
        filterStatus = '';
        filterDate = '';
        surnameInput.value = '';
        statusSelect.value = '';
        
        document.querySelectorAll('.calendar td.active-day').forEach(td => td.classList.remove('active-day'));
        
        renderAppointments();
    });

    // Фільтр по календарю
    window.selectDate = function(dateStr, element) {
        // 1. Логіка фільтрації
        const [dd, mm, yyyy] = dateStr.split('.');
        filterDate = `${yyyy}-${mm}-${dd}`;
        renderAppointments();

        // 2. Логіка підсвітки (якщо переданий елемент)
        if (element) {
            document.querySelectorAll('.calendar td').forEach(td => td.classList.remove('active-day'));
            element.classList.add('active-day');
        }
    }

    // Перший рендер
    renderAppointments();
    AOS.refresh(); 
});
