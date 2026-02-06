// ЛОГІКА ГЕНЕРАЦІЇ КАЛЕНДАРЯ
let currentDate = new Date();

const monthsUa = [
    "Січень", "Лютий", "Березень", "Квітень", "Травень", "Червень",
    "Липень", "Серпень", "Вересень", "Жовтень", "Листопад", "Грудень"
];

function renderCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();

    // Перевіряємо, чи ми на сторінці користувача (де треба блокувати минуле)
    // Якщо елемента 'timeSelect' немає, значить це кабінет працівника
    const isUserDashboard = !!document.getElementById('timeSelect');

    const monthYearElement = document.getElementById('calendar-month-year');
    if (monthYearElement) {
        monthYearElement.innerText = `${monthsUa[month]} ${year}`;
    }

    const firstDayIndex = new Date(year, month, 1).getDay();
    const firstDay = firstDayIndex === 0 ? 6 : firstDayIndex - 1;
    
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const calendarBody = document.getElementById('calendar-body');
    if (!calendarBody) return;
    
    calendarBody.innerHTML = '';

    const today = new Date();
    today.setHours(0, 0, 0, 0);

    let date = 1;
    for (let i = 0; i < 6; i++) {
        let row = document.createElement('tr');
        
        for (let j = 0; j < 7; j++) {
            let cell = document.createElement('td');
            
            if (i === 0 && j < firstDay) {
                cell.innerText = '';
            } else if (date > daysInMonth) {
                cell.innerText = '';
            } else {
                const cellDate = new Date(year, month, date);
                cell.innerText = date;
                
                const isWeekend = (j === 5 || j === 6);
                const isPast = cellDate < today;

                // ЛОГІКА КЛАСІВ:
                if (isWeekend) {
                    cell.className = 'weekend';
                } else {
                    // Додаємо disabled ТІЛЬКИ якщо це сторінка користувача і дата минула
                    const shouldDisable = isUserDashboard && isPast;
                    cell.className = shouldDisable ? 'workday disabled' : 'workday';
                }
                
                // ЛОГІКА КЛІКУ:
                // Працівник може клікати на все (крім вихідних). 
                // Користувач — тільки на майбутні дні.
                const canClick = isUserDashboard ? (!isWeekend && !isPast) : !isWeekend;

                if (canClick) {
                    const fullDate = `${String(date).padStart(2, '0')}.${String(month + 1).padStart(2, '0')}.${year}`;
                    cell.onclick = (e) => selectDate(fullDate, e.target);
                }

                date++;
            }
            row.appendChild(cell);
        }
        calendarBody.appendChild(row);
        if (date > daysInMonth) break;
    }
}

function changeMonth(offset) {
    currentDate.setMonth(currentDate.getMonth() + offset);
    renderCalendar();
}

// Функція вибору дати тепер приймає element напряму
function selectDate(dateStr, element) {
    const display = document.getElementById('selected-date-display');
    if (display) display.innerText = dateStr;
    
    // Візуальний ефект вибору
    document.querySelectorAll('.calendar td').forEach(td => td.classList.remove('active-day'));
    if (element) {
        element.classList.add('active-day');
    }

    // Викликаємо зовнішню логіку фільтрації/завантаження годин, якщо вона існує
    if (typeof window.selectDateLogic === 'function') {
        window.selectDateLogic(dateStr);
    }
    
    console.log("Обрана дата:", dateStr);
}

document.addEventListener('DOMContentLoaded', renderCalendar);