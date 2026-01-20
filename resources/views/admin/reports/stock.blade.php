<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>Stock Report</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Siemreap&display=swap" rel="stylesheet">
    <style> body { font-family: 'Siemreap', sans-serif; } </style>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-700"><i class="fas fa-boxes"></i> របាយការណ៍ស្តុកគង់វង្ស</h1>
            <a href="/admin/dashboard" class="text-gray-500 hover:text-blue-600"><i class="fas fa-arrow-left"></i> ត្រឡប់ក្រោយ</a>
        </div>

        <div class="grid grid-cols-2 gap-6 mb-6">
            <div class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-500">
                <h3 class="text-gray-500">តម្លៃដើមសរុបក្នុងស្តុក (Total Cost Value)</h3>
                <p class="text-2xl font-bold text-gray-800">${{ number_format($totalCost, 2) }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
                <h3 class="text-gray-500">តម្លៃលក់សរុប (Potential Sale Value)</h3>
                <p class="text-2xl font-bold text-gray-800">${{ number_format($totalSaleValue, 2) }}</p>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 border-b">ទំនិញ</th>
                        <th class="p-3 border-b">ប្រភេទ</th>
                        <th class="p-3 border-b text-center">ចំនួនស្តុក</th>
                        <th class="p-3 border-b">ថ្លៃដើម</th>
                        <th class="p-3 border-b">ថ្លៃលក់</th>
                        <th class="p-3 border-b text-right">តម្លៃសរុប (Cost)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr class="hover:bg-gray-50 border-b">
                        <td class="p-3 font-bold">{{ $product->name }}</td>
                        <td class="p-3 text-sm text-gray-500">{{ $product->category->name ?? '-' }}</td>
                        <td class="p-3 text-center">
                            @if($product->qty <= 5)
                                <span class="bg-red-100 text-red-600 px-2 py-1 rounded text-xs font-bold">{{ $product->qty }}</span>
                            @else
                                <span class="bg-green-100 text-green-600 px-2 py-1 rounded text-xs font-bold">{{ $product->qty }}</span>
                            @endif
                        </td>
                        <td class="p-3">${{ number_format($product->cost_price, 2) }}</td>
                        <td class="p-3">${{ number_format($product->sale_price, 2) }}</td>
                        <td class="p-3 text-right font-bold text-blue-600">
                            ${{ number_format($product->cost_price * $product->qty, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>