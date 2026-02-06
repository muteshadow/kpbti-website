<?php
$parentPageTitle = 'Вихід';
$parentPageUrl = '../logout.php';
$pageTitle = "Кабінет працівника";

require "../user-header.php";
preventCaching();

$surname = isset($_GET['surname']) ? trim($_GET['surname']) : '';
$status = isset($_GET['status']) ? trim($_GET['status']) : '';
$dateExact = isset($_GET['date_exact']) ? $_GET['date_exact'] : null;

// DEMO / LIVE MODE
if (DEMO_MODE) {
    require '../js/demo-data/users.php';
    require '../../assets/demo-data/services.php';

    $rawAppointments = DemoDB::getAppointmentsWithUsers();

    foreach ($rawAppointments as $appt) {
        // Збираємо документи
        $docs = [];
        if (!empty($appt['document_ids'])) {
            foreach ($appt['document_ids'] as $docId) {
                if (isset($demoServices['documents'][$docId])) {
                    $docs[] = $demoServices['documents'][$docId];
                }
            }
        }

        $email = $appt['user_data']['email'] ?? 'demo@example.com';
        
        if (!isset($usersData[$email])) {
            $usersData[$email] = [
                'name' => $appt['user_full_name'],
                'email' => $email,
                'appointments' => []
            ];
        }

        $usersData[$email]['appointments'][] = [
            'id' => $appt['id'],
            'date' => $appt['date'],
            'time' => $appt['time'],
            'person_type' => $appt['person_type'],
            'category_name' => $appt['category'],
            'service_name' => $appt['service'],
            'status' => $appt['status'],
            'documents' => implode(', ', $docs)
        ];
    }
} else {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'worker') {
        header("Location: ../../index.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_id'], $_POST['status'])) {
        $appointment_id = intval($_POST['appointment_id']);
        $new_status = trim($_POST['status']);
        updateAppointmentStatus($conn, $appointment_id, $new_status);
    }

    $usersData = getUsersAndAppointments($conn, $surname, $status, $dateExact);
}

// Підготовка для JS
$jsonUsersData = json_encode(array_values($usersData), JSON_UNESCAPED_UNICODE);
?>

<div class="section section-short">
    <div class="container">
        <div class="inner_section calendar_container">
            <div class="calendar-block" id="calendarContainer" data-aos="fade-up" data-aos-duration="1000"></div>

            <div class="selection_container">
                <div data-aos="fade-up" data-aos-duration="1000">
                    <p class="select-label">Пошук за прізвищем:</p>
                    <div class="search-block">
                        <input type="text" id="surnameInput" placeholder="Введіть прізвище" value="<?= htmlspecialchars($surname) ?>">
                        <button class="search-button" id="surnameBtn" type="button"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </div>

                <div data-aos="fade-up" data-aos-duration="1000">
                    <p class="select-label">Фільтр за статусом:</p>
                    <div class="search-block">
                        <select id="statusSelect">
                            <option value="">Всі</option>
                            <option value="в процесі" <?= $status==='в процесі'?'selected':'' ?>>В процесі</option>
                            <option value="скасовано" <?= $status==='скасовано'?'selected':'' ?>>Скасовано</option>
                            <option value="завершено" <?= $status==='завершено'?'selected':'' ?>>Завершено</option>
                        </select>
                        <button class="search-button" id="statusBtn" type="button"><i class="fa-solid fa-filter"></i></button>
                    </div>
                </div>

                <div data-aos="fade-up" data-aos-duration="1000">
                    <button class="btn" id="clearBtn" type="button">Усі замовлення</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div id="usersContainer"></div>
</div>

<script>
// Ініціалізація анімацій
AOS.init();

// Дані з PHP
const usersData = <?= $jsonUsersData ?>;
let filterSurname = "<?= addslashes($surname) ?>";
let filterStatus = "<?= addslashes($status) ?>";
let filterDate = "<?= addslashes($dateExact) ?>";
let currentDate = new Date();

const monthsUa = ["Січень", "Лютий", "Березень", "Квітень", "Травень", "Червень", "Липень", "Серпень", "Вересень", "Жовтень", "Листопад", "Грудень"];

// Календар
function renderCalendar() {
    const container = document.getElementById('calendarContainer');
    if(!container) return;
    container.innerHTML = '';

    const nav = document.createElement('div');
    nav.classList.add('calendar-navigation');
    nav.innerHTML = `
        <a href="#" onclick="changeMonth(-1);return false;">← Попередній місяць</a>
        <span>${monthsUa[currentDate.getMonth()]} ${currentDate.getFullYear()}</span>
        <a href="#" onclick="changeMonth(1);return false;">Наступний місяць →</a>`;
    container.appendChild(nav);

    const table = document.createElement('table');
    table.classList.add('calendar');
    let html = '<thead><tr><th>Пн</th><th>Вт</th><th>Ср</th><th>Чт</th><th>Пт</th><th>Сб</th><th>Нд</th></tr></thead><tbody>';

    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    const firstDay = (new Date(year, month, 1).getDay() || 7) - 1;
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    let date = 1;
    for (let i = 0; i < 6; i++) {
        html += '<tr>';
        for (let j = 0; j < 7; j++) {
            if (i === 0 && j < firstDay || date > daysInMonth) {
                html += '<td></td>';
            } else {
                const isWeekend = j >= 5;
                const fullDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;
                html += `<td class="${isWeekend ? 'weekend' : 'workday'} ${filterDate === fullDate ? 'active-day' : ''}" 
                            onclick="selectDate('${fullDate}', this)">${date}</td>`;
                date++;
            }
        }
        html += '</tr>';
        if (date > daysInMonth) break;
    }
    table.innerHTML = html + '</tbody>';
    container.appendChild(table);
}

function changeMonth(offset) {
    currentDate.setMonth(currentDate.getMonth() + offset);
    renderCalendar();
}

function selectDate(dateStr, element) {
    filterDate = (filterDate === dateStr) ? '' : dateStr;
    renderCalendar();
    renderAppointments();
}

// Форматування дати
function formatDate(dateStr) {
    const d = new Date(dateStr);
    const m = ["січня","лютого","березня","квітня","травня","червня","липня","серпня","вересня","жовтня","листопада","грудня"];
    return `${String(d.getDate()).padStart(2, '0')} ${m[d.getMonth()]} ${d.getFullYear()}`;
}

// Статус-іконки та класи
function getStatusDetails(status) {
    switch(status) {
        case 'в процесі': return { class: 'status-process', icon: '<i class="fa-regular fa-clock"></i>' };
        case 'скасовано': return { class: 'status-cancelled', icon: '<i class="fa-regular fa-circle-xmark"></i>' };
        case 'завершено': return { class: 'status-completed', icon: '<i class="fa-regular fa-circle-check"></i>' };
        default: return { class: '', icon: '' };
    }
}

// Рендер карток
function renderAppointments() {
    const container = document.getElementById('usersContainer');
    if (!container) return;
    container.innerHTML = '';

    const filtered = usersData.filter(user => {
        // Пошук по повному імені (Прізвище + Ім'я + По батькові)
        const fullName = `${user.surname || ''} ${user.name || ''} ${user.patronymic || ''}`.toLowerCase();
        const matchSurname = !filterSurname || fullName.includes(filterSurname.toLowerCase());
        
        const hasMatch = user.appointments.some(a => {
            const matchStatus = !filterStatus || a.status === filterStatus;
            const matchDate = !filterDate || a.date === filterDate;
            return matchStatus && matchDate;
        });
        return matchSurname && hasMatch;
    });

    if (filtered.length === 0) {
        container.innerHTML = '<p class="section_subtitle title_center" data-aos="fade-up" data-aos-duration="1000">Замовлень не знайдено.</p>';
        return;
    }

    filtered.forEach(user => {
        let apptsHtml = '';
        user.appointments.forEach(appt => {
            if (filterStatus && appt.status !== filterStatus) return;
            if (filterDate && appt.date !== filterDate) return;

            const statusInfo = getStatusDetails(appt.status);

            apptsHtml += `
                <div class="appointment-card" data-aos="fade-up" data-aos-duration="1000">
                    <div class="appointment_date">${formatDate(appt.date)}</div>
                    <div class="time_type_container">
                        <div class="appointment_time">${appt.time.slice(0,5)}</div>
                        <div class="appointment_type">${appt.person_type === 'individual' ? 'Фізична особа' : 'Юридична особа'}</div>
                    </div>
                    <div class="appointment-service">
                        <b>${appt.category_name ? appt.category_name + ': ' : ''}${appt.service_name}</b>
                    </div>
                    <div class="appointment-documents">
                        ${appt.documents ? appt.documents.split(',').map(d => `<p>-> ${d.trim()}</p>`).join('') : ''}
                    </div>
                    <div class="appointment-status ${statusInfo.class}">
                        <span class="status-icon">${statusInfo.icon}</span>
                        ${appt.status.charAt(0).toUpperCase() + appt.status.slice(1)}
                    </div>
                    <form method="POST" class="status-form">
                        <input type="hidden" name="appointment_id" value="${appt.id}">
                        <select name="status">
                            <option value="в процесі" ${appt.status === 'в процесі' ? 'selected' : ''}>В процесі</option>
                            <option value="скасовано" ${appt.status === 'скасовано' ? 'selected' : ''}>Скасовано</option>
                            <option value="завершено" ${appt.status === 'завершено' ? 'selected' : ''}>Завершено</option>
                        </select>
                        <button class="btn_documents" type="submit" ${window.DEMO_MODE ? 'onclick="alert(\'Неможливо в демо-версії\'); return false;"' : ''}>Зберегти</button>
                    </form>
                </div>`;
        });

        container.innerHTML += `
            <div class="section-short" style="margin-bottom: 40px;">
                <div class="container">
                    <h3 class="section_subtitle" data-aos="fade-up" data-aos-duration="1000">
                        ${user.surname || ''} ${user.name || ''} ${user.patronymic || ''} <br>
                        <span class="user_email">email: ${user.email}</span>
                    </h3>
                    <div class="appointments-grid appointments-grid-workers">${apptsHtml}</div>
                </div>
            </div>`;
    });
    
    // Оновлюємо анімації після додавання нових елементів у DOM
    AOS.refresh();
}

// Event Listeners
document.addEventListener('DOMContentLoaded', () => {
    renderCalendar();
    renderAppointments();

    const surnameInput = document.getElementById('surnameInput');
    const statusSelect = document.getElementById('statusSelect');

    document.getElementById('surnameBtn').onclick = (e) => { 
        e.preventDefault();
        filterSurname = surnameInput.value; 
        renderAppointments(); 
    };

    document.getElementById('statusBtn').onclick = (e) => { 
        e.preventDefault();
        filterStatus = statusSelect.value; 
        renderAppointments(); 
    };

    document.getElementById('clearBtn').onclick = (e) => { 
        e.preventDefault();
        filterSurname = ''; 
        filterStatus = ''; 
        filterDate = '';
        if(surnameInput) surnameInput.value = '';
        if(statusSelect) statusSelect.value = '';
        renderCalendar();
        renderAppointments();
    };
});
</script>

<?php require "../user-footer.php"; ?>
