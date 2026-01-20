<aside class="w-64 bg-slate-800 text-white min-h-screen flex flex-col shadow-xl fixed z-50 overflow-y-auto">
    
    <div class="p-6 text-2xl font-bold text-center border-b border-slate-700">
        <i class="fas fa-cube text-blue-400"></i> Admin Panel
    </div>

    <nav class="flex-1 p-4 space-y-2">

        <a href="/admin/dashboard"
            class="flex items-center gap-3 p-3 rounded-lg transition {{ Request::is('admin/dashboard') ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-300 hover:bg-slate-700 hover:text-white' }}">
            <i class="fas fa-chart-line w-5"></i> Dashboard
        </a>

        <a href="/pos" target="_blank"
            class="flex items-center gap-3 p-3 rounded-lg transition text-green-400 hover:bg-slate-700 hover:text-green-300">
            <i class="fas fa-cash-register w-5"></i> ទៅកាន់ POS
        </a>

        <div class="mt-4 text-xs font-bold text-gray-500 uppercase px-4 pt-4 border-t border-slate-700">ការគ្រប់គ្រង</div>

        <a href="/admin/products"
            class="flex items-center gap-3 p-3 rounded-lg transition {{ Request::is('admin/products*') ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-300 hover:bg-slate-700 hover:text-white' }}">
            <i class="fas fa-box w-5"></i> ទំនិញ (Products)
        </a>

        <a href="/admin/categories"
            class="flex items-center gap-3 p-3 rounded-lg transition {{ Request::is('admin/categories*') ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-300 hover:bg-slate-700 hover:text-white' }}">
            <i class="fas fa-tags w-5"></i> ប្រភេទ (Categories)
        </a>

        <a href="/admin/employees"
            class="flex items-center gap-3 p-3 rounded-lg transition {{ Request::is('admin/employees*') ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-300 hover:bg-slate-700 hover:text-white' }}">
            <i class="fas fa-users w-5"></i> បុគ្គលិក (Employees)
        </a>

        <a href="/admin/suppliers"
            class="flex items-center gap-3 p-3 rounded-lg transition {{ Request::is('admin/suppliers*') ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-300 hover:bg-slate-700 hover:text-white' }}">
            <i class="fas fa-truck w-5"></i> អ្នកផ្គត់ផ្គង់ (Suppliers)
        </a>

        <div class="mt-4 text-xs font-bold text-gray-500 uppercase px-4 pt-4 border-t border-slate-700">របាយការណ៍</div>

        <a href="/admin/reports/sales"
            class="flex items-center gap-3 p-3 rounded-lg transition {{ Request::is('admin/reports/sales') ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-300 hover:bg-slate-700 hover:text-white' }}">
            <i class="fas fa-file-invoice-dollar w-5"></i> របាយការណ៍លក់
        </a>
        <a href="/admin/reports/stock"
            class="flex items-center gap-3 p-3 rounded-lg transition {{ Request::is('admin/reports/stock') ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-300 hover:bg-slate-700 hover:text-white' }}">
            <i class="fas fa-cubes w-5"></i> របាយការណ៍ស្តុក
        </a>
        <a href="/admin/reports/transactions"
            class="flex items-center gap-3 p-3 rounded-lg transition {{ Request::is('admin/reports/transactions') ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-300 hover:bg-slate-700 hover:text-white' }}">
            <i class="fas fa-exchange-alt w-5"></i> ប្រវត្តិផ្ទេរ/នាំចូល
        </a>

        <div class="mt-4 text-xs font-bold text-gray-500 uppercase px-4 pt-4 border-t border-slate-700">ប្រតិបត្តិការស្តុក</div>
        <a href="/admin/stock/in"
            class="flex items-center gap-3 p-3 rounded-lg transition text-yellow-400 hover:bg-slate-700 hover:text-yellow-300">
            <i class="fas fa-truck-loading w-5"></i> នាំចូលស្តុក (Stock In)
        </a>
    </nav>

    <div class="p-4 border-t border-slate-700">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="flex items-center gap-3 p-3 w-full hover:bg-red-600 rounded-lg transition text-red-300 hover:text-white">
                <i class="fas fa-sign-out-alt"></i> ចាកចេញ
            </button>
        </form>
    </div>
</aside>