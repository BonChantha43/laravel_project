<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Siemreap&display=swap" rel="stylesheet">
    <style> body { font-family: 'Siemreap', sans-serif; } </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-xl shadow-2xl w-full max-w-3xl overflow-hidden">
        <div class="bg-white border-b px-8 py-5 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-700 flex items-center gap-2">
                <i class="fas fa-edit text-blue-600"></i> កែប្រែទំនិញ: {{ $product->name }}
            </h1>
            <a href="/admin/products" class="text-gray-400 hover:text-red-500 text-xl"><i class="fas fa-times"></i></a>
        </div>

        <form action="/admin/products/{{ $product->id }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            @method('PUT')

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
                    <label class="block text-gray-700 font-bold mb-2">ឈ្មោះទំនិញ</label>
                    <input type="text" name="name" value="{{ $product->name }}" class="w-full border px-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-bold mb-2">រូបភាពទំនិញ (ប្តូររូបថ្មី)</label>
                    <div class="flex items-center gap-4">
                        <div class="flex-1">
                            <input type="file" name="image" onchange="previewImage(event)"
                                class="w-full border p-2 rounded-lg bg-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-xs text-gray-400 mt-1">ទុកចោលបើមិនចង់ប្តូររូប</p>
                        </div>
                        
                        <div class="w-20 h-20 border rounded-lg overflow-hidden shadow-sm relative bg-gray-100">
                            @if($product->image)
                                <img id="imagePreview" src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
                            @else
                                <img id="imagePreview" src="#" class="hidden w-full h-full object-cover">
                                <div id="placeholderIcon" class="flex items-center justify-center h-full text-gray-400"><i class="fas fa-image"></i></div>
                            @endif
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Barcode</label>
                    <input type="text" name="barcode" value="{{ $product->barcode }}" class="w-full border px-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">ប្រភេទ</label>
                    <select name="category_id" class="w-full border px-4 py-3 rounded-lg bg-white">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">អ្នកផ្គត់ផ្គង់</label>
                    <select name="supplier_id" class="w-full border px-4 py-3 rounded-lg bg-white">
                        <option value="">-- គ្មាន --</option>
                        @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}" {{ $product->supplier_id == $sup->id ? 'selected' : '' }}>
                                {{ $sup->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">ចំនួនស្តុក</label>
                    <input type="number" name="qty" value="{{ $product->qty }}" class="w-full border px-4 py-3 rounded-lg bg-gray-100" readonly>
                    <span class="text-xs text-red-500">* ស្តុកត្រូវនាំចូលតាម Stock In</span>
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">តម្លៃដើម ($)</label>
                    <input type="number" step="0.01" name="cost_price" value="{{ $product->cost_price }}" class="w-full border px-4 py-3 rounded-lg">
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">តម្លៃលក់ ($)</label>
                    <input type="number" step="0.01" name="sale_price" value="{{ $product->sale_price }}" class="w-full border px-4 py-3 rounded-lg">
                </div>
            </div>

            <div class="mt-8 pt-6 border-t flex justify-end gap-4">
                <a href="/admin/products" class="px-6 py-3 rounded-lg text-gray-600 hover:bg-gray-100 font-bold">បោះបង់</a>
                <button type="submit" class="px-8 py-3 rounded-lg bg-blue-600 text-white hover:bg-blue-700 font-bold">
                    <i class="fas fa-save"></i> រក្សាទុកការកែប្រែ
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
                output.classList.remove('hidden');
                
                const placeholder = document.getElementById('placeholderIcon');
                if(placeholder) placeholder.style.display = 'none';
            };
            if(event.target.files[0]){
                reader.readAsDataURL(event.target.files[0]);
            }
        }
    </script>
</body>
</html>