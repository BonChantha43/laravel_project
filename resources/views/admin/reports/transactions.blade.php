<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>Transaction Report</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Siemreap&display=swap" rel="stylesheet">
    <style> body { font-family: 'Siemreap', sans-serif; } </style>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-700"><i class="fas fa-history"></i> របាយការណ៍ចលនាស្តុក</h1>
            <a href="/admin/dashboard" class="text-gray-500 hover:text-blue-600"><i class="fas fa-arrow-left"></i> ត្រឡប់ក្រោយ</a>
        </div>

        <form class="bg-white p-4 rounded shadow mb-6 flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-bold mb-1">ប្រភេទ (Type)</label>
                <select name="type" class="border p-2 rounded w-40 bg-white">
                    <option value="">ទាំងអស់</option>
                    <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>នាំចូល (Stock In)</option>
                    <option value="sale" {{ request('type') == 'sale' ? 'selected' : '' }}>លក់ចេញ (Sale)</option>
                    <option value="broken" {{ request('type') == 'broken' ? 'selected' : '' }}>ខូច (Broken)</option>
                    <option value="return" {{ request('type') == 'return' ? 'selected' : '' }}>ត្រឡប់វិញ (Return)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold mb-1">ចាប់ពីថ្ងៃ</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="border p-2 rounded w-40">
            </div>
            <div>
                <label class="block text-sm font-bold mb-1">ដល់ថ្ងៃ</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="border p-2 rounded w-40">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                <i class="fas fa-filter"></i> Filter
            </button>
        </form>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 border-b">កាលបរិច្ឆេទ</th>
                        <th class="p-3 border-b">ទំនិញ</th>
                        <th class="p-3 border-b">ប្រភេទ</th>
                        <th class="p-3 border-b text-center">ចំនួន (Qty)</th>
                        <th class="p-3 border-b">អ្នកធ្វើប្រតិបត្តិការ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $tr)
                    <tr class="hover:bg-gray-50 border-b">
                        <td class="p-3">{{ \Carbon\Carbon::parse($tr->date)->format('d/m/Y H:i') }}</td>
                        <td class="p-3 font-bold">{{ $tr->product->name ?? 'Deleted' }}</td>
                        <td class="p-3">
                            @if($tr->type == 'in')
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs uppercase font-bold">Stock In</span>
                            @elseif($tr->type == 'sale')
                                <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs uppercase font-bold">Sale</span>
                            @elseif($tr->type == 'broken')
                                <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs uppercase font-bold">Broken</span>
                            @else
                                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs uppercase font-bold">{{ $tr->type }}</span>
                            @endif
                        </td>
                        <td class="p-3 text-center font-bold {{ $tr->qty > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $tr->qty > 0 ? '+'.$tr->qty : $tr->qty }}
                        </td>
                        <td class="p-3 text-sm text-gray-500">{{ $tr->user->name ?? 'System' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>