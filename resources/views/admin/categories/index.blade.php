<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>Categories Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Siemreap&display=swap" rel="stylesheet">
    <style> body { font-family: 'Siemreap', sans-serif; } </style>
</head>
<body class="bg-gray-100 flex">

    @include('admin.sidebar')

    <main class="ml-64 flex-1 p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-tags text-blue-600"></i> គ្រប់គ្រងប្រភេទ (Categories)</h1>
            <button onclick="openCreateModal()" 
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow flex items-center gap-2 transform active:scale-95 transition">
                <i class="fas fa-plus-circle"></i> បន្ថែមថ្មី
            </button>
        </div>

        @if(session('success'))
            <div id="alert-box" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm flex justify-between items-center">
                <span><i class="fas fa-check-circle"></i> {{ session('success') }}</span>
                <button onclick="document.getElementById('alert-box').remove()" class="text-green-700 font-bold">&times;</button>
            </div>
        @endif

        <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
            <table class="min-w-full leading-normal">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">ឈ្មោះប្រភេទ</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">ពិពណ៌នា (Description)</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($categories as $cat)
                    <tr class="hover:bg-blue-50 transition">
                        <td class="px-6 py-4 text-sm font-bold text-gray-800">{{ $cat->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $cat->description ?? '-' }}</td>
                        <td class="px-6 py-4 text-center flex justify-center gap-3">
                            <button onclick="openEditModal('{{ $cat->id }}', '{{ $cat->name }}', '{{ $cat->description }}')" 
                                class="text-blue-500 hover:bg-blue-100 p-2 rounded transition" title="កែប្រែ">
                                <i class="fas fa-edit"></i>
                            </button>
                            
                            <button onclick="confirmDelete('{{ $cat->id }}', '{{ $cat->name }}')" class="text-red-500 hover:bg-red-100 p-2 rounded transition" title="លុប">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>

    <div id="createModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6 transform scale-100 transition-transform">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">បង្កើតប្រភេទថ្មី</h2>
                <button onclick="closeModal('createModal')" class="text-gray-400 hover:text-red-500 text-2xl">&times;</button>
            </div>
            <form action="/admin/categories" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">ឈ្មោះប្រភេទ <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">ពិពណ៌នា</label>
                    <textarea name="description" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none h-24"></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal('createModal')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg font-bold text-gray-700">បោះបង់</button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold shadow">រក្សាទុក</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6 transform scale-100 transition-transform">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">កែប្រែប្រភេទ</h2>
                <button onclick="closeModal('editModal')" class="text-gray-400 hover:text-red-500 text-2xl">&times;</button>
            </div>
            <form id="editForm" method="POST">
                @csrf @method('PUT')
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">ឈ្មោះប្រភេទ <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="editName" required class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">ពិពណ៌នា</label>
                    <textarea name="description" id="editDescription" class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 h-24"></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal('editModal')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg font-bold text-gray-700">បោះបង់</button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold shadow">រក្សាទុក</button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 z-50 flex items-center justify-center backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm p-6 text-center transform scale-100 transition-transform">
            
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4 animate-bounce">
                <i class="fas fa-trash-alt text-2xl text-red-600"></i>
            </div>
            
            <h3 class="text-xl font-bold text-gray-800 mb-2">តើអ្នកប្រាកដទេ?</h3>
            <p class="text-gray-500 mb-6 text-sm">
                អ្នកកំពុងលុបប្រភេទ៖ <span id="deleteCategoryName" class="font-bold text-red-600"></span><br>
                សកម្មភាពនេះមិនអាចត្រឡប់វិញបានទេ។
            </p>

            <form id="deleteForm" method="POST">
                @csrf @method('DELETE')
                <div class="flex gap-3 justify-center">
                    <button type="button" onclick="closeModal('deleteModal')" class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-bold transition">
                        បោះបង់
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold shadow-lg transition transform active:scale-95">
                        លុបចោល
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Create Modal
        function openCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
        }

        // Edit Modal
        function openEditModal(id, name, desc) {
            document.getElementById('editForm').action = '/admin/categories/' + id;
            document.getElementById('editName').value = name;
            document.getElementById('editDescription').value = desc || '';
            document.getElementById('editModal').classList.remove('hidden');
        }

        // Close Any Modal
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        // Delete Modal (New & Clean)
        function confirmDelete(id, name) {
            document.getElementById('deleteCategoryName').innerText = name;
            document.getElementById('deleteForm').action = '/admin/categories/' + id;
            document.getElementById('deleteModal').classList.remove('hidden');
        }
    </script>
</body>
</html>