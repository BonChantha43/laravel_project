<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>Stock In</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Siemreap&display=swap" rel="stylesheet">
    <style> body { font-family: 'Siemreap', sans-serif; } </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl overflow-hidden">
        <div class="bg-blue-600 px-8 py-5 flex justify-between items-center text-white">
            <h1 class="text-2xl font-bold flex items-center gap-2">
                <i class="fas fa-cubes"></i> នាំចូលស្តុក (Stock In)
            </h1>
            <a href="/admin/dashboard" class="hover:text-gray-200 text-xl"><i class="fas fa-times"></i></a>
        </div>

        <form action="/admin/stock/in" method="POST" class="p-8">
            @csrf
            
            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-4 rounded mb-6 border border-green-200 flex items-center gap-2">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <div class="space-y-6">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">ជ្រើសរើសទំនិញ</label>
                    <div class="relative">
                        <select name="product_id" class="appearance-none w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white" required>
                            <option value="">-- សូមជ្រើសរើស --</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}">
                                    {{ $p->name }} (ស្តុកបច្ចុប្បន្ន: {{ $p->qty }})
                                </option>
                            @endforeach
                        </select>
                        
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">ចំនួនបន្ថែម (Qty)</label>
                        <input type="number" name="qty" min="1" placeholder="0" class="w-full border px-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">នាំចូលពី (Supplier)</label>
                        <div class="relative">
                            <select name="supplier_id" class="appearance-none w-full border border-gray-300 px-4 py-3 rounded-lg bg-white focus:ring-2 focus:ring-blue-500">
                                <option value="">-- គ្មាន --</option>
                                @foreach($suppliers as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                            
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t flex justify-end">
                <button type="submit" class="px-8 py-3 rounded-lg bg-blue-600 text-white hover:bg-blue-700 shadow-lg font-bold flex items-center gap-2 transition transform active:scale-95">
                    <i class="fas fa-save"></i> រក្សាទុកការនាំចូល
                </button>
            </div>
        </form>
    </div>

</body>
</html>