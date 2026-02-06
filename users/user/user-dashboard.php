<?php
$parentPageTitle = 'Вихід';
$parentPageUrl = '../logout.php';
$pageTitle = "Особистий кабінет";

require "../user-header.php";
preventCaching();

// Ініціалізація даних для JS
$demoUsersJson = '[]';
$demoApptsJson = '[]';
$servicesJson  = '[]';

if (DEMO_MODE) {
    require_once '../js/demo-data/users.php';
    require_once '../../assets/demo-data/services.php';
    
    $demoUsersJson = json_encode($demoUsers, JSON_UNESCAPED_UNICODE);
    $demoApptsJson = json_encode($demoAppointments, JSON_UNESCAPED_UNICODE);
    $servicesJson  = json_encode($demoServices, JSON_UNESCAPED_UNICODE);
} else {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
        header("Location: ../../index.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];

    // ПІБ
    list($surname, $name, $patronymic) = getUserFullName($conn, $user_id);
    $demoUsersJson = json_encode([['id'=>$user_id, 'surname'=>$surname, 'name'=>$name, 'patronymic'=>$patronymic]], JSON_UNESCAPED_UNICODE);

    // Історія замовлень
    $res = getUserAppointments($conn, $user_id);
    $appointmentsData = [];
    while($row = $res->fetch_assoc()) { 
        $appointmentsData[] = [
            'user_id'     => $user_id,
            'date'        => $row['date'],
            'time'        => $row['time'],
            'person_type' => $row['person_type'],
            'status'      => $row['status'],
            'category'    => $row['category_name'],
            'service'     => $row['service_name'],
            'document_ids' => (!empty($row['documents'])) ? explode(', ', $row['documents']) : []
        ];
    }
    $demoApptsJson = json_encode($appointmentsData, JSON_UNESCAPED_UNICODE);

    // Збір послуг
    $allServices = ['individual' => [], 'legal' => [], 'documents' => []];
    
    // Документи
    $dRes = $conn->query("SELECT id, title FROM documents");
    while($r = $dRes->fetch_assoc()) {
        $allServices['documents'][$r['id']] = $r['title'];
    }

    // Послуги (individual_services та legal_services)
    $tables = ['individual' => 'individual_services', 'legal' => 'legal_services'];
    foreach($tables as $key => $table) {
        // Таблиця categories, з зображеннями 
        $sql = "SELECT s.service_name, s.document_id, c.title as category_name, c.image 
                FROM $table s 
                JOIN categories c ON s.category_id = c.id";
        $sRes = $conn->query($sql);
        while($r = $sRes->fetch_assoc()) {
            $allServices[$key][] = [
                'service_name'  => $r['service_name'],
                'category_name' => $r['category_name'],
                'image'         => $r['image'],
                'document_ids'  => array_map('trim', explode(',', $r['document_id']))
            ];
        }
    }
    $servicesJson = json_encode($allServices, JSON_UNESCAPED_UNICODE);
}
?>

<section class="section section-short">
    <div class="container">
        <div data-aos="fade-up" data-aos-duration="1000">
            <h3 class="section_title welcome">Ласкаво просимо,</h3>
            <div id="userFullName" class="section_subtitle">Завантаження...</div>
        </div>

        <div class="inner_section calendar_container">
            <div id="calendarContainer" data-aos="fade-up" data-aos-duration="1000"></div>

            <div class="selection_container">
                <div class="selection-block" data-aos="fade-up" data-aos-duration="1000">
                    <p class="select-label">Оберіть дату:</p>
                    <div id="selected-date-display" class="selected-value">...</div>
                </div>

                <div class="selection-block" data-aos="fade-up" data-aos-duration="1000">
                    <p class="select-label">Оберіть годину:</p>
                    <select id="timeSelect">
                        <option value="">Спочатку оберіть дату</option>
                    </select>
                </div>

                <div class="selection-block" data-aos="fade-up" data-aos-duration="1000">
                    <p class="select-label">Оберіть тип особи:</p>
                    <select id="personType" onchange="loadCategories()">
                        <option value="">Оберіть тип особи:</option>
                        <option value="individual">Фізична особа</option>
                        <option value="legal">Юридична особа</option>
                    </select>
                </div>
            </div>
        </div>

        <h3 class="section_subtitle" data-aos="fade-up" data-aos-duration="1000">Оберіть послугу:</h3>
        <div class="selection-services">
            <div id="serviceContainers" class="services-grid">
                </div>
        </div>

        <h3 class="section_subtitle" data-aos="fade-up" data-aos-duration="1000">Необхідні документи:</h3>
        <div class="selection-services">
            <div id="documentsContainer" class="documents-container" data-aos="fade-up" data-aos-duration="1000">
                Оберіть послугу вище
            </div>
        </div>

        <div data-aos="fade-up" class="btn-container">
            <button class="btn form-btn" onclick="submitAppointment()">Відправити замовлення</button>
        </div>
    </div>
</section>

<section class="section section-short">
    <div class="container">
        <h3 class="section_title title_center" data-aos="fade-up" data-aos-duration="1000">Ваші замовлення</h3>
        <div id="userAppointmentsGrid" class="appointments-grid"></div>
    </div>
</section>

<script>
// Дані передані з PHP
const demoUsers = <?= $demoUsersJson ?>;
const demoAppointments = <?= $demoApptsJson ?>;
const servicesData = <?= $servicesJson ?>;

let currentDate = new Date();

// Беремо ID
const currentUserId = <?= isset($user_id) ? $user_id : (count(json_decode($demoUsersJson)) > 0 ? json_decode($demoUsersJson)[0]->id : 0) ?>; 

const workingHours = ["09:00", "10:00", "11:00", "12:00", "14:00", "15:00", "16:00"];
const monthsUa = ["Січень", "Лютий", "Березень", "Квітень", "Травень", "Червень", "Липень", "Серпень", "Вересень", "Жовтень", "Листопад", "Грудень"];

document.addEventListener('DOMContentLoaded', () => {
    // 1. Ініціалізація AOS та Календаря
    if (window.AOS) AOS.init({ duration: 1000, once: false });
    renderCalendar();
    renderHistory();

    // 2. Вивід імені юзера
    const user = demoUsers.find(u => u.id === currentUserId);
    if (user) {
        document.getElementById('userFullName').innerText = `${user.surname} ${user.name} ${user.patronymic}!`;
    }
});

// --- ЛОГІКА КАЛЕНДАРЯ ---
function renderCalendar() {
    const container = document.getElementById('calendarContainer');
    if(!container) return;
    
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    const today = new Date().setHours(0,0,0,0);

    let html = `
        <div class="calendar-navigation">
            <a href="javascript:void(0)" onclick="changeMonth(-1)">← Попередній місяць</a>
            <span>${monthsUa[month]} ${year}</span>
            <a href="javascript:void(0)" onclick="changeMonth(1)">Наступний місяць →</a>
        </div>
        <table class="calendar">
            <tr><th>Пн</th><th>Вт</th><th>Ср</th><th>Чт</th><th>Пт</th><th>Сб</th><th>Нд</th></tr><tr>`;

    const firstDay = (new Date(year, month, 1).getDay() || 7) - 1;
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    for (let i = 0; i < firstDay; i++) html += '<td></td>';

    for (let day = 1; day <= daysInMonth; day++) {
        const dateStr = `${String(day).padStart(2, '0')}.${String(month + 1).padStart(2, '0')}.${year}`;
        const iterDate = new Date(year, month, day).getTime();
        const dayOfWeek = (new Date(year, month, day).getDay() || 7);
        
        let classes = dayOfWeek > 5 ? 'weekend' : 'workday';
        if (dayOfWeek <= 5 && iterDate < today) {
            classes += ' disabled';
        }

        const clickAttr = (dayOfWeek <= 5 && iterDate >= today) 
            ? `onclick="selectDate('${dateStr}', this)"` 
            : '';
                
        // Для тесту вчорашніх дат
        // const clickAttr = (dayOfWeek <= 5) ? `onclick="selectDate('${dateStr}', this)"` : '';

        html += `<td class="${classes}" ${clickAttr}>${day}</td>`;
        if ((day + firstDay) % 7 === 0) html += '</tr><tr>';
    }
    html += '</tr></table>';
    container.innerHTML = html;
}

function changeMonth(step) {
    currentDate.setMonth(currentDate.getMonth() + step);
    renderCalendar();
}

// --- ЛОГІКА ВИБОРУ ДАТИ ТА ЧАСУ ---
window.selectDate = function(dateStr, element) {
    if (element) {
        document.querySelectorAll('.calendar td').forEach(td => td.classList.remove('active-day'));
        element.classList.add('active-day');
    }

    document.getElementById('selected-date-display').innerText = dateStr;

    // Конвертація для фільтрації DD.MM.YYYY -> YYYY-MM-DD
    const parts = dateStr.split('.');
    const isoDate = `${parts[2]}-${parts[1]}-${parts[0]}`;

    const timeSelect = document.getElementById('timeSelect');
    timeSelect.innerHTML = '<option value="">Оберіть годину</option>';

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
};

// --- ЛОГІКА КАТЕГОРІЙ ТА ПОСЛУГ ---
window.loadCategories = function() {
    const type = document.getElementById('personType').value;
    const grid = document.getElementById('serviceContainers');
    const docsContainer = document.getElementById('documentsContainer');
    
    if (!grid) return;
    grid.innerHTML = ''; 
    docsContainer.innerHTML = 'Оберіть послугу вище';

    if (!type || !servicesData[type]) return;

    // Групуємо послуги за назвою категорії
    const categories = {};
    servicesData[type].forEach((item, index) => {
        if (!categories[item.category_name]) {
            categories[item.category_name] = { image: item.image, services: [] };
        }
        categories[item.category_name].services.push({ id: index, name: item.service_name });
    });

    Object.keys(categories).forEach(catName => {
        const cat = categories[catName];
        const categoryDiv = document.createElement('div');
        categoryDiv.className = 'category-container';
        categoryDiv.setAttribute('data-aos', 'fade-up');
        
        let servicesOptions = cat.services.map(s => `<option value="${s.id}">${s.name}</option>`).join('');

        categoryDiv.innerHTML = `
            <label>${catName}</label>
            <div class="select-container">
                <img src="../../${cat.image}" 
                     alt="${catName}" 
                     class="category-image" 
                     onerror="this.onerror=null; this.src='../../img/no-image.jpg';">
                <select onchange="loadDocuments(this, '${type}')">
                    <option value="">Оберіть послугу</option>
                    ${servicesOptions}
                </select>
            </div>
        `;
        grid.appendChild(categoryDiv);
    });
};

// --- ЛОГІКА ДОКУМЕНТІВ ---
window.loadDocuments = function(selectElement, type) {
    const serviceId = selectElement.value;
    const container = document.getElementById('documentsContainer');
    
    document.querySelectorAll('.category-container').forEach(c => c.classList.remove('active'));
    if (!serviceId) {
        container.innerHTML = 'Оберіть послугу вище';
        window.currentSelection = null;
        return;
    }

    selectElement.closest('.category-container').classList.add('active');
    const service = servicesData[type][serviceId];
    
    let html = `<div class="service-documents" data-service-id="${serviceId}">`;
    service.document_ids.forEach(docId => {
        const docTitle = servicesData.documents[docId];
        if (docTitle) {
            const uniqueId = `doc_${serviceId}_${docId}`;
            html += `<div><input type="checkbox" id="${uniqueId}" value="${docId}"> <label for="${uniqueId}">${docTitle}</label></div>`;
        }
    });
    html += '</div>';
    
    container.innerHTML = html;
    window.currentSelection = { category: service.category_name, service: service.service_name };
};

// --- ЛОГІКА ІСТОРІЇ ---
function renderHistory() {
    const grid = document.getElementById('userAppointmentsGrid');
    if (!grid) return;

    const myAppts = demoAppointments.filter(a => a.user_id === currentUserId);
    if (myAppts.length === 0) {
        grid.innerHTML = '<p data-aos="fade-up" data-aos-duration="1000">У вас ще немає замовлень.</p>';
        return;
    }

    grid.innerHTML = myAppts.map(appt => {
        const statusClass = appt.status === 'в процесі' ? 'status-process' : (appt.status === 'скасовано' ? 'status-cancelled' : 'status-completed');
        const icon = appt.status === 'в процесі' ? 'fa-clock' : (appt.status === 'скасовано' ? 'fa-circle-xmark' : 'fa-circle-check');
        
        // Мапимо ID документів у назви

        const docNames = Array.isArray(appt.document_ids) 
            ? appt.document_ids.map(id => {
                return servicesData.documents[id] || id; 
            }).filter(Boolean)
            : [];

        return `
            <div class="appointment-card" data-aos="fade-up">
                <div class="appointment_date">${formatDateUa(appt.date)}</div>
                <div class="time_type_container">
                    <div class="appointment_time">${appt.time.substring(0,5)}</div>
                    <div class="appointment_type">${appt.person_type === 'individual' ? 'Фіз. особа' : 'Юр. особа'}</div>
                </div>
                <div class="appointment-service">
                    <strong>${appt.category}:</strong> ${appt.service}
                </div>
                <div class="appointment-documents">
                    ${docNames.map(d => `<p>-> ${d}</p>`).join('')}
                </div>
                <div class="appointment-status ${statusClass}">
                    <span class="status-icon"><i class="fa-regular ${icon}"></i></span>
                    ${appt.status}
                </div>
            </div>
        `;
    }).join('');
}

function formatDateUa(dateStr) {
    const d = new Date(dateStr);
    const months = ["січня", "лютого", "березня", "квітня", "травня", "червня", "липня", "серпня", "вересня", "жовтня", "листопада", "грудня"];
    return `${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`;
}

window.submitAppointment = function() {
    const isDemo = <?= json_encode(DEMO_MODE) ?>

    const dateDisplay = document.getElementById('selected-date-display').innerText;
    const time = document.getElementById('timeSelect').value;
    const personType = document.getElementById('personType').value;

    if (dateDisplay === '...' || !time || !window.currentSelection) {
        alert('Оберіть дату, час та послугу!');
        return;
    }

    if (isDemo) {
        alert('Неможливо в демо-режимі!');
        return; 
    }

    // Форматуємо дату з DD.MM.YYYY у YYYY-MM-DD для MySQL
    const parts = dateDisplay.split('.');
    const isoDate = `${parts[2]}-${parts[1]}-${parts[0]}`;

    const formData = new FormData();
    formData.append('date', isoDate);
    formData.append('time', time);
    formData.append('person_type', personType);
    formData.append('service_name', window.currentSelection.service);

    fetch('save-appointment.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Замовлення успішно створено!');
            location.reload(); // Оновлюємо сторінку, щоб побачити нове замовлення в списку
        } else {
            alert('Помилка: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
};
</script>

<?php require "../user-footer.php"; ?>