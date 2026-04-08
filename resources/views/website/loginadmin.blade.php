<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - DIGIBARANGAY</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden">
        
        <div class="bg-gradient-to-b from-[#4facfe] to-[#72c667] p-10 text-center text-white">
            <div class="flex justify-center mb-4">
                <img src="{{ asset('img/logo_zed.png') }}" alt="DIGIBARANGAY logo" class="w-20 h-20 object-contain" />
            </div>
            <h1 class="text-4xl font-bold tracking-tight">Admin</h1>
            <p class="text-sm opacity-90 mt-2">Login to access the admin dashboard</p>
        </div>

        <div class="p-8">
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
              
                
                <div>
                    <label for="email" class="block text-gray-800 font-bold mb-2">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="Enter your Email Address" 
                        class="w-full px-4 py-3 bg-[#f1f4f9] border-none rounded-xl focus:ring-2 focus:ring-blue-400 outline-none transition-all placeholder:text-gray-400 text-gray-700"
                        required
                    >
                </div>

                <div>
                    <label for="password" class="block text-gray-800 font-bold mb-2">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Enter your Password" 
                        class="w-full px-4 py-3 bg-[#f1f4f9] border-none rounded-xl focus:ring-2 focus:ring-blue-400 outline-none transition-all placeholder:text-gray-400 text-gray-700"
                        required
                    >
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-[#4facfe] hover:bg-blue-600 text-white font-bold py-3 rounded-xl transition-colors duration-300 shadow-md"
                >
                    Login
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="/" class="text-sm text-gray-500 hover:text-blue-500 transition-colors">
                    &larr; Back to Website
                </a>
            </div>
        </div>
    </div>

</body>
</html>