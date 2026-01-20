<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Siemreap&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Siemreap', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-xl shadow-2xl w-full max-w-3xl overflow-hidden">
        
        <div class="bg-white border-b px-8 py-5 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-700 flex items-center gap-2">
                <i class="fas fa-box text-blue-600"></i> បន្ថែមទំនិញថ្មី
            </h1>
            <a href="/admin/products" class="text-gray-400 hover:text-red-500 transition text-xl">
                <i class="fas fa-times"></i>
            </a>
        </div>

        <form action="/admin/products" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf 
            
            @if ($errors->any())
                <div class="bg-red-50 text-red-600 p-4 rounded-lg mb-6 border border-red-200">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-bold mb-2">ឈ្មោះទំនិញ <span class="text-red-500">*</span></label>
                    <input type="text" name="name" placeholder="ឧ. Sting, Coca Cola..." value="{{ old('name') }}"
                        class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-bold mb-2">រូបភាពទំនិញ (Image)</label>
                    <div class="flex items-center gap-4">
                        <div class="flex-1">
                            <input type="file" name="image" id="imageInput" accept="image/*" onchange="previewImage(event)"
                                class="w-full border border-gray-300 p-2 rounded-lg bg-white focus:outline-none text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-xs text-gray-400 mt-1">អនុញ្ញាតតែ file: jpg, png, jpeg (Max: 2MB)</p>
                        </div>
                        <div id="imagePreviewContainer" class="hidden w-20 h-20 border rounded-lg overflow-hidden shadow-sm">
                            <img id="imagePreview" src="#" alt="Preview" class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Barcode <span class="text-red-500">*</span></label>
                    <input type="text" name="barcode" placeholder="Scan ឬ វាយលេខកូដ" value="{{ old('barcode') }}"
                        class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">ប្រភេទ <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="category_id" class="w-full border border-gray-300 px-4 py-3 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">-- ជ្រើសរើសប្រភេទ --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">អ្នកផ្គត់ផ្គង់</label>
                    <div class="relative">
                        <select name="supplier_id" class="w-full border border-gray-300 px-4 py-3 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">-- ជ្រើសរើសអ្នកផ្គត់ផ្គង់ --</option>
                            @foreach($suppliers as $sup)
                                <option value="{{ $sup->id }}" {{ old('supplier_id') == $sup->id ? 'selected' : '' }}>
                                    {{ $sup->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">ចំនួនក្នុងស្តុក (Qty)</label>
                    <input type="number" name="qty" value="{{ old('qty', 0) }}"
                        class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 bg-green-50 focus:border-transparent transition">
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">តម្លៃដើម ($) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="cost_price" placeholder="0.00" value="{{ old('cost_price') }}"
                        class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">តម្លៃលក់ ($) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="sale_price" placeholder="0.00" value="{{ old('sale_price') }}"
                        class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>

            </div>

            <div class="mt-8 pt-6 border-t flex items-center justify-end gap-4">
                <a href="/admin/products" class="px-6 py-3 rounded-lg text-gray-600 hover:bg-gray-100 font-bold transition">
                    បោះបង់
                </a>
                <button type="submit" class="px-8 py-3 rounded-lg bg-blue-600 text-white hover:bg-blue-700 shadow-lg font-bold transition flex items-center gap-2">
                    <i class="fas fa-save"></i> រក្សាទុក
                </button>
            </div>

        </form>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('imagePreview');
                output.src = reader.result;
                document.getElementById('imagePreviewContainer').classList.remove('hidden');
            };
            if(event.target.files[0]){
                reader.readAsDataURL(event.target.files[0]);
            }
        }
    </script>

</body>
</html>