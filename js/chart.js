function renderOrderStatusChart(elementId, data) {
    var ctx = document.getElementById(elementId).getContext('2d');

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['В процесі', 'Скасовано', 'Завершено'],
            datasets: [{
                data: data,
                backgroundColor: ['#FFA965', '#EFF2F4', '#3B66C3'],
                borderWidth: 1,
                borderColor: '#000'
            }]
        },
        options: {
            responsive: false,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    align: 'center',
                    labels: {
                        boxWidth: 20,
                        padding: 15,
                        font: {
                            family: 'Work Sans, serif',
                            size: 16
                        },
                        color: '#000'
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function (tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw + ' замовлень';
                        }
                    }
                }
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const counters = document.querySelectorAll('.number');

    counters.forEach(counter => {
        const updateCount = () => {
            const target = +counter.getAttribute('data-target');
            const current = +counter.innerText;
            const increment = Math.ceil(target / 100); // Збільшення

            if (current < target) {
                counter.innerText = current + increment;
                setTimeout(updateCount, 30);
            } else {
                counter.innerText = target;
            }
        };

        updateCount();
    });
});
