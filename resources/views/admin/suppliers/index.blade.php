<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>Suppliers List</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Siemreap&display=swap" rel="stylesheet">
    <style> body { font-family: 'Siemreap', sans-serif; } </style>
</head>
<body class="bg-gray-100 flex">

    @include('admin.sidebar')

    <main class="ml-64 flex-1 p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-truck text-blue-600"></i> អ្នកផ្គត់ផ្គង់ (Suppliers)
            </h1>
            <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow flex items-center gap-2 transition transform hover:scale-105">
                <i class="fas fa-plus-circle"></i> បន្ថែមថ្មី
            </button>
        </div>

        @if(session('success'))
        <div id="alert-box" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm flex justify-between items-center">
            <div class="flex items-center gap-2"><i class="fas fa-check-circle text-xl"></i> {{ session('success') }}</div>
            <button onclick="document.getElementById('alert-box').remove()" class="font-bold">×</button>
        </div>
        @endif

        <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-gray-100">
            <table class="min-w-full leading-normal">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">ឈ្មោះក្រុមហ៊ុន</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">អ្នកទំនាក់ទំនង</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">លេខទូរស័ព្ទ</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">អាសយដ្ឋាន</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($suppliers as $sup)
                    <tr class="hover:bg-blue-50 transition duration-200">
                        <td class="px-6 py-4 font-bold text-gray-800">{{ $sup->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600"><i class="fas fa-user-tie text-gray-400 mr-1"></i> {{ $sup->contact_person }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $sup->phone }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 truncate max-w-xs">{{ $sup->address }}</td>
                        <td class="px-6 py-4 text-center text-sm font-medium flex justify-center gap-2">
                            <button onclick="editSupplier({{ json_encode($sup) }})" class="text-blue-500 hover:bg-blue-100 p-2 rounded transition"><i class="fas fa-edit"></i></button>
                            
                            <button onclick="confirmDelete('{{ $sup->id }}', '{{ addslashes($sup->name) }}')" class="text-red-500 hover:bg-red-100 p-2 rounded transition">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>

    <div id="createModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 z-50 flex items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-5 border-b pb-3">
                <h2 id="modalTitle" class="text-xl font-bold text-gray-800">បន្ថែមអ្នកផ្គត់ផ្គង់</h2>
                <button onclick="closeModal('createModal')" class="text-gray-400 hover:text-red-500 text-2xl">&times;</button>
            </div>
            <form id="supplierForm" action="/admin/suppliers" method="POST">
                @csrf <div id="methodField"></div>
                <div class="space-y-4">
                    <div><label class="block text-gray-700 font-bold mb-1">ឈ្មោះក្រុមហ៊ុន *</label><input type="text" name="name" id="name" required class="w-full border rounded px-3 py-2"></div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="block text-gray-700 font-bold mb-1">អ្នកទំនាក់ទំនង</label><input type="text" name="contact_person" id="contact_person" class="w-full border rounded px-3 py-2"></div>
                        <div><label class="block text-gray-700 font-bold mb-1">លេខទូរស័ព្ទ</label><input type="text" name="phone" id="phone" class="w-full border rounded px-3 py-2"></div>
                    </div>
                    <div><label class="block text-gray-700 font-bold mb-1">អាសយដ្ឋាន</label><textarea name="address" id="address" rows="3" class="w-full border rounded px-3 py-2"></textarea></div>
                </div>
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                    <button type="button" onclick="closeModal('createModal')" class="px-4 py-2 bg-gray-200 rounded font-bold">បោះបង់</button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded font-bold">រក្សាទុក</button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 z-50 flex items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm p-6 text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4 animate-bounce">
                <i class="fas fa-trash-alt text-2xl text-red-600"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">តើអ្នកប្រាកដទេ?</h3>
            <p class="text-gray-500 mb-6 text-sm">
                អ្នកកំពុងលុបអ្នកផ្គត់ផ្គង់៖ <span id="deleteName" class="font-bold text-red-600"></span>
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
        function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
        function openModal() {
            document.getElementById('createModal').classList.remove('hidden');
            document.getElementById('supplierForm').action = '/admin/suppliers';
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('supplierForm').reset();
            document.getElementById('modalTitle').innerText = 'បន្ថែមអ្នកផ្គត់ផ្គង់';
        }
        function editSupplier(sup) {
            document.getElementById('createModal').classList.remove('hidden');
            document.getElementById('supplierForm').action = '/admin/suppliers/' + sup.id;
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('modalTitle').innerText = 'កែប្រែអ្នកផ្គត់ផ្គង់';
            document.getElementById('name').value = sup.name;
            document.getElementById('contact_person').value = sup.contact_person;
            document.getElementById('phone').value = sup.phone;
            document.getElementById('address').value = sup.address;
        }

        // ✅ Confirm Delete Function
        function confirmDelete(id, name) {
            document.getElementById('deleteName').innerText = name;
            document.getElementById('deleteForm').action = "/admin/suppliers/" + id;
            document.getElementById('deleteModal').classList.remove('hidden');
        }
    </script>
</body>
</html>