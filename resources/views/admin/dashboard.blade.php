<!DOCTYPE html>
<html lang="km">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Siemreap&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body { font-family: 'Siemreap', sans-serif; }
    </style>
</head>

<body class="bg-gray-100 flex">

    @include('admin.sidebar')

    <main class="ml-64 flex-1 p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">ទិដ្ឋភាពទូទៅ (Overview)</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-blue-500 flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">លក់បានថ្ងៃនេះ</p>
                    <h2 class="text-2xl font-bold text-blue-600">${{ number_format($todaySales ?? 0, 2) }}</h2>
                </div>
                <div class="bg-blue-100 p-3 rounded-full text-blue-600">
                    <i class="fas fa-dollar-sign text-xl"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-green-500 flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">ចំណូលសរុប</p>
                    <h2 class="text-2xl font-bold text-green-600">${{ number_format($totalSales ?? 0, 2) }}</h2>
                </div>
                <div class="bg-green-100 p-3 rounded-full text-green-600">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-purple-500 flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">ទំនិញសរុប</p>
                    <h2 class="text-2xl font-bold text-purple-600">{{ $totalProducts ?? 0 }}</h2>
                </div>
                <div class="bg-purple-100 p-3 rounded-full text-purple-600">
                    <i class="fas fa-box text-xl"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-red-500 flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">ជិតអស់ស្តុក</p>
                    <h2 class="text-2xl font-bold text-red-600">{{ $lowStockCount ?? 0 }}</h2>
                </div>
                <div class="bg-red-100 p-3 rounded-full text-red-600">
                    <i class="fas fa-exclamation-triangle text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-700 mb-4">ក្រាហ្វិកការលក់ ៧ ថ្ងៃចុងក្រោយ</h3>
            <div class="h-80 w-full relative">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </main>

    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');

        // Pass data from Controller to JavaScript
        const dates = @json($dates);
        const totals = @json($totals);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Income ($)',
                    data: totals,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#2563eb',
                    pointRadius: 5,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f3f4f6' },
                        ticks: {
                            callback: function(value) { return '$' + value; }
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    </script>
</body>
</html>