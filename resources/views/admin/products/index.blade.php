<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>Product List - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Siemreap&display=swap" rel="stylesheet">
    <style> body { font-family: 'Siemreap', sans-serif; } </style>
</head>
<body class="bg-gray-100 flex">

    @include('admin.sidebar')

    <main class="ml-64 flex-1 p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-box-open text-blue-600"></i> បញ្ជីទំនិញទាំងអស់</h1>
            <div class="flex gap-4">
                <div class="relative">
                    <input type="text" id="adminSearch" placeholder="ស្វែងរកទំនិញ..." 
                        class="pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-64 shadow-sm">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
                <button onclick="openCreateModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow transition flex items-center gap-2 transform active:scale-95">
                    <i class="fas fa-plus-circle"></i> បង្កើតទំនិញថ្មី
                </button>
            </div>
        </div>

        @if(session('success'))
        <div id="alert-box" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm flex justify-between items-center transition-all duration-500">
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle text-xl"></i> {{ session('success') }}
            </div>
            <button onclick="document.getElementById('alert-box').remove()" class="text-green-700 hover:text-green-900 font-bold">×</button>
        </div>
        @endif

        <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <th class="px-5 py-3">ឈ្មោះទំនិញ</th>
                        <th class="px-5 py-3">Barcode</th>
                        <th class="px-5 py-3">ប្រភេទ</th>
                        <th class="px-5 py-3">ថ្លៃដើម</th>
                        <th class="px-5 py-3">ថ្លៃលក់</th>
                        <th class="px-5 py-3 text-center">ស្តុក</th>
                        <th class="px-5 py-3 text-center">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody id="productTableBody">
                    @foreach($products as $product)
                    <tr class="hover:bg-blue-50 transition border-b border-gray-100 product-row">
                        <td class="px-5 py-4 text-sm bg-white">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 overflow-hidden border border-gray-200">
                                    @if($product->image)
                                        <img src="{{ asset('storage/'.$product->image) }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="fas fa-image"></i>
                                    @endif
                                </div>
                                <div class="ml-3">
                                    <p class="text-gray-900 font-bold whitespace-no-wrap product-name">{{ $product->name }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-500 font-mono product-barcode">{{ $product->barcode }}</td>
                        <td class="px-5 py-4 text-sm">
                            <span class="px-2 py-1 font-semibold text-blue-800 bg-blue-100 rounded-md text-xs">{{ $product->category->name ?? 'N/A' }}</span>
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-500">${{ number_format($product->cost_price, 2) }}</td>
                        <td class="px-5 py-4 text-sm font-bold text-blue-600">${{ number_format($product->sale_price, 2) }}</td>
                        
                        <td class="px-5 py-4 text-sm text-center">
                            @if($product->qty <= 5)
                                <span class="px-2 py-1 font-bold text-red-600 bg-red-100 rounded-full text-xs animate-pulse">
                                    {{ $product->qty }} (Low)
                                </span>
                            @else
                                <span class="px-2 py-1 font-bold text-green-600 bg-green-100 rounded-full text-xs">
                                    {{ $product->qty }}
                                </span>
                            @endif
                        </td>

                        <td class="px-5 py-4 text-sm text-center flex justify-center gap-2">
                            <button onclick="openStockInModal('{{ $product->id }}', '{{ $product->name }}', '{{ $product->qty }}')" 
                                class="text-green-600 hover:bg-green-100 p-2 rounded transition" title="នាំចូលស្តុក (Stock In)">
                                <i class="fas fa-plus-square text-lg"></i>
                            </button>

                            <button onclick="openEditModal(this)" 
                                data-id="{{ $product->id }}"
                                data-name="{{ $product->name }}"
                                data-barcode="{{ $product->barcode }}"
                                data-category="{{ $product->category_id }}"
                                data-cost="{{ $product->cost_price }}"
                                data-sale="{{ $product->sale_price }}"
                                data-qty="{{ $product->qty }}"
                                data-image="{{ $product->image ? asset('storage/'.$product->image) : '' }}"
                                class="text-blue-500 hover:bg-blue-100 p-2 rounded transition" title="កែប្រែ">
                                <i class="fas fa-edit"></i>
                            </button>

                            <button onclick="confirmDelete('{{ $product->id }}', '{{ $product->name }}')" class="text-red-500 hover:bg-red-100 p-2 rounded transition" title="លុប">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>

    <div id="stockInModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 z-50 flex items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden transform scale-100 transition-transform">
            <div class="bg-green-600 px-6 py-4 border-b flex justify-between items-center">
                <h2 class="text-xl font-bold text-white"><i class="fas fa-cubes"></i> នាំចូលស្តុក (Stock In)</h2>
                <button onclick="closeModal('stockInModal')" class="text-white hover:text-gray-200 text-2xl">&times;</button>
            </div>
            
            <form action="/admin/stock/in" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="product_id" id="stockInProductId">
                
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-1">ទំនិញ</label>
                    <input type="text" id="stockInProductName" class="w-full border rounded px-3 py-2 bg-gray-100 text-gray-500 font-bold" readonly>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-bold mb-1">ស្តុកបច្ចុប្បន្ន</label>
                        <input type="text" id="stockInCurrentQty" class="w-full border rounded px-3 py-2 bg-gray-100 text-center" readonly>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold mb-1">បន្ថែមចំនួន (Qty)</label>
                        <input type="number" name="qty" min="1" required class="w-full border-2 border-green-500 rounded px-3 py-2 text-center font-bold text-lg focus:ring-2 focus:ring-green-300">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-1">អ្នកផ្គត់ផ្គង់ (Supplier)</label>
                    <div class="relative">
                        <select name="supplier_id" class="appearance-none w-full border rounded px-3 py-2 bg-white">
                            <option value="">-- គ្មាន --</option>
                            @foreach($suppliers as $sup)
                                <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeModal('stockInModal')" class="px-4 py-2 bg-gray-200 rounded font-bold hover:bg-gray-300">បោះបង់</button>
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded font-bold shadow hover:bg-green-700">
                        <i class="fas fa-save"></i> រក្សាទុក
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="createModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-3xl overflow-hidden max-h-[90vh] overflow-y-auto">
            <div class="bg-white px-6 py-4 border-b flex justify-between items-center sticky top-0 z-10">
                <h2 class="text-xl font-bold text-gray-800"><i class="fas fa-plus-circle text-blue-600"></i> បង្កើតទំនិញថ្មី</h2>
                <button onclick="closeModal('createModal')" class="text-gray-400 hover:text-red-500 text-2xl">&times;</button>
            </div>
            <div class="p-6">
                <form action="/admin/products" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-gray-700 font-bold mb-1">ឈ្មោះទំនិញ *</label>
                            <input type="text" name="name" required class="w-full border rounded px-3 py-2">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-gray-700 font-bold mb-1">រូបភាព</label>
                            <input type="file" name="image" class="w-full border p-1 rounded bg-gray-50">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold mb-1">Barcode *</label>
                            <input type="text" name="barcode" required class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold mb-1">ប្រភេទ *</label>
                            <div class="relative">
                                <select name="category_id" required class="appearance-none w-full border rounded px-3 py-2 bg-white">
                                    <option value="">-- ជ្រើសរើស --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold mb-1">ថ្លៃដើម ($)</label>
                            <input type="number" step="0.01" name="cost_price" required class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold mb-1">ថ្លៃលក់ ($)</label>
                            <input type="number" step="0.01" name="sale_price" required class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold mb-1">ស្តុកដំបូង</label>
                            <input type="number" name="qty" value="0" class="w-full border rounded px-3 py-2 bg-green-50">
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3 pt-4 border-t">
                        <button type="button" onclick="closeModal('createModal')" class="px-4 py-2 bg-gray-200 rounded font-bold">បោះបង់</button>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded font-bold">រក្សាទុក</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="editModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-3xl overflow-hidden max-h-[90vh] overflow-y-auto">
            <div class="bg-white px-6 py-4 border-b flex justify-between items-center sticky top-0 z-10">
                <h2 class="text-xl font-bold text-gray-800"><i class="fas fa-edit text-blue-600"></i> កែប្រែទំនិញ</h2>
                <button onclick="closeModal('editModal')" class="text-gray-400 hover:text-red-500 text-2xl">&times;</button>
            </div>
            
            <div class="p-6">
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-gray-700 font-bold mb-1">ឈ្មោះទំនិញ</label>
                            <input type="text" name="name" id="editName" required class="w-full border rounded px-3 py-2">
                        </div>
                        
                        <div class="md:col-span-2 flex gap-4 items-center">
                            <div class="flex-1">
                                <label class="block text-gray-700 font-bold mb-1">ប្តូររូបភាព</label>
                                <input type="file" name="image" class="w-full border p-1 rounded bg-gray-50">
                            </div>
                            <div class="w-16 h-16 border rounded bg-gray-100 flex items-center justify-center overflow-hidden">
                                <img id="editImagePreview" src="" class="hidden w-full h-full object-cover">
                                <i id="editImagePlaceholder" class="fas fa-image text-gray-400"></i>
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-bold mb-1">Barcode</label>
                            <input type="text" name="barcode" id="editBarcode" required class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold mb-1">ប្រភេទ</label>
                            <div class="relative">
                                <select name="category_id" id="editCategory" required class="appearance-none w-full border rounded px-3 py-2 bg-white">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-bold mb-1">ថ្លៃដើម ($)</label>
                            <input type="number" step="0.01" name="cost_price" id="editCost" required class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold mb-1">ថ្លៃលក់ ($)</label>
                            <input type="number" step="0.01" name="sale_price" id="editSale" required class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold mb-1">ស្តុកបច្ចុប្បន្ន</label>
                            <input type="number" id="editQty" class="w-full border rounded px-3 py-2 bg-gray-100 text-gray-500" readonly>
                            <span class="text-xs text-red-500">* ប្រើប៊ូតុង (+) ដើម្បីនាំចូលស្តុក</span>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3 pt-4 border-t">
                        <button type="button" onclick="closeModal('editModal')" class="px-4 py-2 bg-gray-200 rounded font-bold">បោះបង់</button>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded font-bold shadow">រក្សាទុក</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="deleteModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 z-50 flex items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm p-6 text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4 animate-bounce">
                <i class="fas fa-trash-alt text-2xl text-red-600"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">តើអ្នកប្រាកដទេ?</h3>
            <p class="text-gray-500 mb-6 text-sm">
                អ្នកកំពុងលុបទំនិញ៖ <span id="deleteProductName" class="font-bold text-red-600"></span>
            </p>
            <form id="deleteForm" method="POST">
                @csrf @method('DELETE')
                <div class="flex gap-3 justify-center">
                    <button type="button" onclick="closeModal('deleteModal')" class="flex-1 px-4 py-2 bg-gray-200 rounded-lg font-bold">បោះបង់</button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg font-bold shadow">លុបចោល</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Open Any Modal
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        // 1. Stock In Modal Function
        function openStockInModal(id, name, currentQty) {
            document.getElementById('stockInProductId').value = id;
            document.getElementById('stockInProductName').value = name;
            document.getElementById('stockInCurrentQty').value = currentQty;
            document.getElementById('stockInModal').classList.remove('hidden');
        }

        // 2. Create Modal
        function openCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
        }

        // 3. Edit Modal
        function openEditModal(button) {
            let id = button.getAttribute('data-id');
            document.getElementById('editForm').action = "/admin/products/" + id;
            document.getElementById('editName').value = button.getAttribute('data-name');
            document.getElementById('editBarcode').value = button.getAttribute('data-barcode');
            document.getElementById('editCategory').value = button.getAttribute('data-category');
            document.getElementById('editCost').value = button.getAttribute('data-cost');
            document.getElementById('editSale').value = button.getAttribute('data-sale');
            document.getElementById('editQty').value = button.getAttribute('data-qty');

            let imageUrl = button.getAttribute('data-image');
            let imgPreview = document.getElementById('editImagePreview');
            let imgPlaceholder = document.getElementById('editImagePlaceholder');

            if (imageUrl) {
                imgPreview.src = imageUrl;
                imgPreview.classList.remove('hidden');
                imgPlaceholder.classList.add('hidden');
            } else {
                imgPreview.classList.add('hidden');
                imgPlaceholder.classList.remove('hidden');
            }
            document.getElementById('editModal').classList.remove('hidden');
        }

        // 4. Delete Modal
        function confirmDelete(id, name) {
            document.getElementById('deleteProductName').innerText = name;
            document.getElementById('deleteForm').action = "/admin/products/" + id;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        // 5. Search
        document.getElementById('adminSearch').addEventListener('keyup', function(e) {
            let term = e.target.value.toLowerCase();
            document.querySelectorAll('.product-row').forEach(row => {
                let name = row.querySelector('.product-name').innerText.toLowerCase();
                let barcode = row.querySelector('.product-barcode').innerText.toLowerCase();
                row.style.display = (name.includes(term) || barcode.includes(term)) ? '' : 'none';
            });
        });
    </script>
</body>
</html>