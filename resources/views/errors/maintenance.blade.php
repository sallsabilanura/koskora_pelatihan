<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Sedang Maintenance</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=2">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-4">
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8 md:p-12 max-w-lg w-full text-center">
        <div class="w-20 h-20 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-tools text-3xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-slate-800 mb-3">Sistem Sedang Perbaikan</h1>
        <p class="text-slate-600 mb-8 leading-relaxed">
            Mohon maaf, saat ini sistem sedang dalam masa pemeliharaan rutin untuk meningkatkan layanan kami. Silakan coba kembali dalam beberapa saat.
        </p>
        <button onclick="window.location.reload()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-8 rounded-xl transition-colors">
            Coba Muat Ulang
        </button>
        <div class="mt-8 text-sm text-slate-400">
            Tim IT Koskora
        </div>
    </div>
</body>
</html>
