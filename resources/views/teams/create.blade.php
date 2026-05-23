<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Workspace Baru - Team Task Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-2xl shadow-xl border border-slate-100 max-w-md w-full mx-4">
        <div class="mb-6">
            <a href="{{ route('dashboard') }}" class="text-xs font-bold text-indigo-600 hover:underline">← Kembali ke Dashboard</a>
        </div>
        
        <h2 class="text-2xl font-extrabold text-slate-900 mb-2">Buat Workspace</h2>
        <p class="text-slate-500 text-sm mb-6">Buat ruang kerja baru untuk memisahkan manajemen tugas tim projekmu.</p>

        <form action="{{ route('teams.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Nama Workspace / Tim</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Projek Mandiri Beryl" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none @error('name') border-red-500 @enderror" required>
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition">
                Buat Ruang Kerja
            </button>
        </form>
    </div>
</body>
</html>