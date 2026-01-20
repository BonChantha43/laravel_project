<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Siemreap&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Siemreap', sans-serif; }
        /* ខ្ញុំបានលុប Code .bg-pattern ចោលហើយ */
    </style>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center p-4">

    <div class="bg-white w-full max-w-md rounded-2xl shadow-[0_20px_50px_rgba(8,_112,_184,_0.7)] overflow-hidden transform transition-all hover:scale-[1.01] duration-300 border border-gray-100">
        
        <div class="bg-blue-600 p-8 text-center relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full bg-blue-700 opacity-20 transform -skew-y-6 origin-top-right"></div>
            
            <div class="relative z-10">
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <i class="fas fa-user-shield text-4xl text-blue-600"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-1">ប្រព័ន្ធគ្រប់គ្រង</h1>
                <p class="text-blue-100 text-sm">សូមស្វាគមន៍មកកាន់ Admin Panel</p>
            </div>
        </div>

        <div class="p-8 pt-6">
            
            @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r shadow-sm flex items-center gap-3 animate-pulse">
                    <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                    <div>
                        <p class="font-bold text-red-700 text-sm">បរាជ័យ!</p>
                        <p class="text-red-600 text-xs">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2 ml-1">អ៊ីមែល (Email)</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                        </div>
                        <input type="email" name="email" required placeholder="example@email.com"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all shadow-sm bg-gray-50 focus:bg-white text-gray-700">
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2 ml-1">ពាក្យសម្ងាត់ (Password)</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                        </div>
                        <input type="password" name="password" required placeholder="••••••••"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all shadow-sm bg-gray-50 focus:bg-white text-gray-700">
                    </div>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center text-gray-600 cursor-pointer hover:text-gray-800">
                        <input type="checkbox" class="mr-2 rounded text-blue-600 focus:ring-blue-500">
                        ចងចាំខ្ញុំ
                    </label>
                    <a href="#" class="text-blue-600 hover:text-blue-800 font-bold hover:underline">ភ្លេចលេខកូដ?</a>
                </div>

                <button type="submit" 
                    class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold py-3.5 rounded-lg shadow-lg hover:shadow-xl hover:from-blue-700 hover:to-blue-800 focus:ring-4 focus:ring-blue-300 transition-all transform active:scale-95 flex items-center justify-center gap-2">
                    <i class="fas fa-sign-in-alt"></i> ចូលប្រព័ន្ធ (Login)
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-gray-400 text-xs">
                    &copy; {{ date('Y') }} POS System. All rights reserved.
                </p>
            </div>
        </div>
    </div>

</body>
</html>