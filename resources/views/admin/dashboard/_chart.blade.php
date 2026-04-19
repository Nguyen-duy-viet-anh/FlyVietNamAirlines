<div class="card chart-container-card" data-url="{{ route('admin.chart_data') }}" id="booking-chart-container">
    <div class="chart-header">
        <h3 class="m-0">Lịch sử đặt vé</h3>
        <div id="chart-filters" class="d-flex gap-10">
            <button class="btn-filter" data-range="today">Hôm nay</button>
            <button class="btn-filter active" data-range="week">1 Tuần</button>
            <button class="btn-filter" data-range="month">1 Tháng</button>
            <button class="btn-filter" data-range="6_months">6 Tháng</button>
            <button class="btn-filter" data-range="year">1 Năm</button>
        </div>
    </div>
    <div class="chart-wrapper">
        <canvas id="bookingChart"></canvas>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartContainer = document.getElementById('booking-chart-container');
    if (!chartContainer) return;

    const apiUrl = chartContainer.dataset.url;
    const ctx = document.getElementById('bookingChart').getContext('2d');
    let chart;

    function initChart(labels, data) {
        if (chart) {
            chart.destroy();
        }
        chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Số lượng đơn đặt',
                    data: data,
                    fill: true,
                    borderColor: '#3498db',
                    backgroundColor: 'rgba(52, 152, 219, 0.1)',
                    tension: 0.3,
                    pointRadius: 4,
                    pointBackgroundColor: '#3498db'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    function updateChart(range) {
        fetch(`${apiUrl}?range=${range}`)
            .then(response => response.json())
            .then(data => {
                initChart(data.labels, data.data);
            })
            .catch(error => console.error('Error fetching chart data:', error));
    }

    // Load default (1 week)
    updateChart('week');

    // Filter event listeners
    document.querySelectorAll('.btn-filter').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.btn-filter').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            updateChart(this.dataset.range);
        });
    });
});
</script>
@endpush
