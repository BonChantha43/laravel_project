<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>Sales Report</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Siemreap&display=swap" rel="stylesheet">
    <style> body { font-family: 'Siemreap', sans-serif; } </style>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-700"><i class="fas fa-chart-line"></i> របាយការណ៍លក់</h1>
            <a href="/admin/dashboard" class="text-gray-500 hover:text-blue-600"><i class="fas fa-arrow-left"></i> ត្រឡប់ក្រោយ</a>
        </div>

        <form class="bg-white p-4 rounded shadow mb-6 flex gap-4 items-end">
            <div>
                <label class="block text-sm font-bold mb-1">ចាប់ពីថ្ងៃ</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="border p-2 rounded w-40">
            </div>
            <div>
                <label class="block text-sm font-bold mb-1">ដល់ថ្ងៃ</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="border p-2 rounded w-40">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                <i class="fas fa-filter"></i> បង្ហាញ
            </button>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-blue-500 text-white p-6 rounded-lg shadow">
                <h3 class="text-lg">សរុបការលក់ (Total Sales)</h3>
                <p class="text-3xl font-bold">${{ number_format($totalSale, 2) }}</p>
            </div>
            <div class="bg-green-500 text-white p-6 rounded-lg shadow">
                <h3 class="text-lg">ចំនួនវិក្កយបត្រ (Invoices)</h3>
                <p class="text-3xl font-bold">{{ $sales->count() }}</p>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 border-b">កាលបរិច្ឆេទ</th>
                        <th class="p-3 border-b">លេខវិក្កយបត្រ</th>
                        <th class="p-3 border-b">អ្នកលក់</th>
                        <th class="p-3 border-b">ការបង់ប្រាក់</th>
                        <th class="p-3 border-b text-right">សរុបទឹកប្រាក់</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sales as $sale)
                    <tr class="hover:bg-gray-50 border-b">
                        <td class="p-3">{{ $sale->created_at->format('d/m/Y H:i A') }}</td>
                        <td class="p-3 font-bold text-blue-600">{{ $sale->invoice_number }}</td>
                        <td class="p-3">{{ $sale->user->name ?? 'N/A' }}</td>
                        <td class="p-3 uppercase text-xs font-bold">{{ $sale->payment_type }}</td>
                        <td class="p-3 text-right font-bold">${{ number_format($sale->final_total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>