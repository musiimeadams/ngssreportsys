<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Ngarama Girl's Secondary School E-Reports System</title>
    @vite(['resources/css/app.css'])

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            background-image: radial-gradient(circle at top center, rgba(79, 70, 229, 0.05) 0%, transparent 60%);
        }
        .header-title {
            font-family: 'Cinzel', serif;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between items-center p-6 text-slate-900 selection:bg-indigo-600 selection:text-white">

    <!-- Top Spacer -->
    <div></div>

    <!-- Main Welcome Card Container -->
    <div class="w-full max-w-3xl text-center space-y-8 py-12 px-6">
        <!-- Logo -->
        <div class="flex justify-center">
            <div class="w-36 h-36 rounded-full border border-slate-800 bg-slate-900/60 p-4 shadow-xl flex items-center justify-center animate-bounce duration-1000">
                <img src="/images/logo.png" alt="School Logo" class="w-28 h-28 object-contain">
            </div>
        </div>

        <!-- Upper Case Banner Title -->
        <div class="space-y-4">
            <h1 class="header-title text-4xl md:text-5xl font-black tracking-wider text-white uppercase leading-tight">
                WELCOME TO NGARAMA GIRLS SECONDARY SCHOOL E-REPORTS SYSTEMS
            </h1>
            <p class="text-sm md:text-base text-red-500 font-semibold tracking-widest uppercase">
                "Develop a girl, Develop a nation"
            </p>
            <p class="text-xs text-slate-500 tracking-wide">
                P.O. Box 1020, Mbarara • Telephone: 0752935405
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
            @auth
                <a href="{{ route('dashboard') }}" 
                    class="w-full sm:w-auto px-8 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-600/20 hover:shadow-indigo-500/30 transition duration-300">
                    Go to Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" 
                    class="w-full sm:w-auto px-8 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-600/20 hover:shadow-indigo-500/30 transition duration-300">
                    Sign In to Portal
                </a>
                <a href="{{ route('register') }}" 
                    class="w-full sm:w-auto px-8 py-3.5 bg-slate-900 hover:bg-slate-850 text-slate-300 hover:text-white font-bold rounded-xl border border-slate-800 hover:border-slate-700 transition duration-300">
                    Register Account
                </a>
            @endauth
        </div>
    </div>

    <!-- Developer Credits Footer -->
    <div class="text-center py-4">
        <p class="text-[10px] font-bold text-slate-500 tracking-widest uppercase">
            Developed by MUSIIME ADAMZ
        </p>
    </div>

</body>
</html>
