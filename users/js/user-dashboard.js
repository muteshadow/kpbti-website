document.addEventListener('DOMContentLoaded', () => {
    if (window.AOS) {
        AOS.init({ duration: 1000, once: false, mirror: true });
    }

    const currentUserId = 1; 
    const workingHours = ["09:00", "10:00", "11:00", "12:00", "14:00", "15:00", "16:00"];

    // 1. Ініціалізація юзера
    const user = demoUsers.find(u => u.id === currentUserId);
    if (user) document.getElementById('userFullName').innerText = `${user.surname} ${user.name} ${user.patronymic}!`;

    // 2. Вибір дати + Фільтр часу + Підсвітка
    window.selectDate = function(dateStr, element) {
        // --- ЛОГІКА ПІДСВІТКИ ---
        if (element) {
            // Видаляємо клас у всіх комірок календаря
            document.querySelectorAll('.calendar td').forEach(td => td.classList.remove('active-day'));
            // Додаємо активний клас обраній комірці
            element.classList.add('active-day');
        }

        // --- ВАША ІСНУЮЧА ЛОГІКА ---
        const display = document.getElementById('selected-date-display');
        if (display) display.innerText = dateStr;

        // Конвертація DD.MM.YYYY -> YYYY-MM-DD
        const parts = dateStr.split('.');
        const isoDate = `${parts[2]}-${parts[1]}-${parts[0]}`;

        const timeSelect = document.getElementById('timeSelect');
        if (timeSelect) {
            timeSelect.innerHTML = '<option value="">Оберіть годину</option>';

            // Шукаємо зайняті години
            const bookedHours = demoAppointments
                .filter(appt => appt.date === isoDate && appt.status !== 'скасовано')
                .map(appt => appt.time.substring(0, 5));

            workingHours.forEach(hour => {
                if (!bookedHours.includes(hour)) {
                    const opt = document.createElement('option');
                    opt.value = hour; 
                    opt.textContent = hour;
                    timeSelect.appendChild(opt);
                }
            });
            
            // Якщо всі години зайняті
            if (timeSelect.options.length === 1 && isoDate) {
                const opt = document.createElement('option');
                opt.textContent = "Немає вільного часу";
                opt.disabled = true;
                timeSelect.appendChild(opt);
            }
        }
    };

    // 3. Завантаження категорій (аналог get_services.php)
    window.loadCategories = function() {
        const type = document.getElementById('personType').value;
        const grid = document.getElementById('serviceContainers'); // Використовуємо ваш ID
        const docsContainer = document.getElementById('documentsContainer');
        
        if (!grid) return;
        grid.innerHTML = ''; // Очищуємо контейнер
        if (docsContainer) docsContainer.innerHTML = 'Оберіть послугу вище';

        if (!type || !servicesData[type]) return;

        // 1. Групуємо послуги за назвою категорії
        const categories = {};
        servicesData[type].forEach((item, index) => {
            if (!categories[item.category_name]) {
                categories[item.category_name] = {
                    image: item.image,
                    services: []
                };
            }
            categories[item.category_name].services.push({
                id: index,
                name: item.service_name
            });
        });

        // 2. Малюємо за вашою структурою
        Object.keys(categories).forEach(catName => {
            const cat = categories[catName];
            const categoryDiv = document.createElement('div');
            
            // Додаємо ваші класи та атрибути AOS
            categoryDiv.className = 'category-container';
            categoryDiv.setAttribute('data-aos', 'fade-up');
            categoryDiv.setAttribute('data-aos-duration', '1000');
            
            // Формуємо список опцій
            let servicesOptions = cat.services.map(s => 
                `<option value="${s.id}">${s.name}</option>`
            ).join('');

            // Вставляємо внутрішню структуру (label -> select-container -> img + select)
            categoryDiv.innerHTML = `
                <label>${catName}</label>
                <div class="select-container">
                    <img src="../${cat.image}" alt="${catName}" class="category-image">
                    <select onchange="loadDocuments(this, '${type}')">
                        <option value="">Оберіть послугу</option>
                        ${servicesOptions}
                    </select>
                </div>
            `;
            
            grid.appendChild(categoryDiv);
        });
    };

    // 4. Завантаження документів (аналог get_documents.php)
    window.loadDocuments = function(selectElement, type) {
        const serviceId = selectElement.value;
        const container = document.getElementById('documentsContainer');
        
        if (!container) return;

        // 1. Візуально виділяємо активний контейнер категорії
        document.querySelectorAll('.category-container').forEach(c => c.classList.remove('active'));
        if (serviceId) {
            selectElement.closest('.category-container').classList.add('active');
        }

        // 2. Якщо послугу не обрано — очищуємо контейнер
        if (!serviceId) {
            container.innerHTML = 'Оберіть послугу вище';
            window.currentSelection = null;
            return;
        }

        const service = servicesData[type][serviceId];
        
        // 3. Формуємо HTML за вашою структурою
        let html = `<div class="service-documents" data-service-id="${serviceId}">`;
        
        service.document_ids.forEach(docId => {
            const docTitle = servicesData.documents[docId];
            if (docTitle) {
                // Створюємо унікальний ID для чекбокса (наприклад: doc_0_1)
                const uniqueId = `doc_${serviceId}_${docId}`;
                
                html += `
                    <div>
                        <input type="checkbox" id="${uniqueId}" value="${docId}">
                        <label for="${uniqueId}">${docTitle}</label>
                    </div>
                `;
            }
        });
        
        html += '</div>';
        
        // Вставляємо результат в контейнер
        container.innerHTML = html;
        
        // 4. Оновлюємо дані для фінальної відправки форми
        window.currentSelection = { 
            category: service.category_name, 
            service: service.service_name,
            serviceId: serviceId
        };
    };

    // 5. Рендер історії
    // Допоміжні функції для рендеру (копіюємо з адмінки)
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

    // Оновлена функція рендеру історії
    function renderHistory() {
        const grid = document.getElementById('userAppointmentsGrid');
        if (!grid) return;

        const myAppts = demoAppointments.filter(a => a.user_id === currentUserId);

        grid.innerHTML = myAppts.map(appt => {
            const docs = getDocumentNames(appt.document_ids || []);
            
            return `
                <div class="appointment-card" data-aos="fade-up" data-aos-duration="1000">
                    <div class="appointment_date">${formatDate(appt.date)}</div>
                    
                    <div class="time_type_container">
                        <div class="appointment_time">${appt.time.substring(0,5)}</div>
                        <div class="appointment_type">${appt.person_type === 'individual' ? 'Фізична особа' : 'Юридична особа'}</div>
                    </div>

                    <div class="appointment-service">
                        <strong>${appt.category ? appt.category + ': ' : ''}</strong>${appt.service}
                    </div>

                    ${docs.length ? `
                        <div class="appointment-documents">
                            ${docs.map(doc => `<p class="doc-item-history">-> ${doc}</p>`).join('')}
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
                </div>
            `;
        }).join('');
    }

    window.submitAppointment = function() {
        const time = document.getElementById('timeSelect').value;
        if (!time || !window.currentSelection) {
            alert('Оберіть дату, час та послугу!');
            return;
        }
        alert('Неможливо в демо-версії!');
    };

    renderHistory();

});
