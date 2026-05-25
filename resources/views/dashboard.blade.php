<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Team Task Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 p-6 font-sans">
    <div class="max-w-6xl mx-auto space-y-6">
        
        <div class="bg-white p-6 rounded-2xl shadow-md border border-slate-200 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-black text-indigo-600">TeamTaskManager 🚀</h1>
                <p class="text-xs text-slate-500 mt-1">Halo, <span class="font-bold text-slate-700">{{ auth()->user()->name }}</span></p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('teams.create') }}" class="bg-slate-800 hover:bg-slate-700 text-white text-xs font-semibold py-2 px-4 rounded-xl transition">
                    + Workspace Baru
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-xs font-bold py-2 px-4 rounded-xl transition">Logout</button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-100 border border-emerald-200 text-emerald-800 p-4 rounded-xl text-sm font-medium">
                ✨ {{ session('success') }}
            </div>
        @endif

        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="w-full md:w-72">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Pilih Workspace Aktif</label>
                <select id="workspaceFilter" class="w-full px-3 py-2 border rounded-xl outline-none text-sm bg-slate-50 font-bold text-indigo-600 cursor-pointer" onchange="window.location.href='?team_id=' + this.value">
                    <option value="">-- Pilih Workspace --</option>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}" {{ $currentTeam && $currentTeam->id == $team->id ? 'selected' : '' }}>
                            💼 {{ $team->name }} ({{ $team->role }})
                        </option>
                    @endforeach
                </select>
            </div>
            @if($currentTeam)
                <div class="text-sm text-slate-500 font-medium">Total Tugas di Tim Ini: <span class="text-indigo-600 font-bold">{{ $tasks->count() }}</span></div>
            @endif
        </div>

        @if($currentTeam)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            
            <div class="bg-white p-6 rounded-2xl shadow-md border border-slate-200 space-y-4">
                <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2">📋 Buat Tugas Baru</h3>
                
                <form action="{{ route('tasks.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="team_id" value="{{ $currentTeam->id }}">
                    <input type="hidden" name="status" value="todo">

                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">JUDUL TUGAS</label>
                        <input type="text" name="title" class="w-full px-3 py-2 border rounded-xl outline-none text-xs focus:ring-2 focus:ring-indigo-500" placeholder="Misal: Setup routing API" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">DESKRIPSI</label>
                        <textarea name="description" class="w-full px-3 py-2 border rounded-xl outline-none text-xs focus:ring-2 focus:ring-indigo-500" rows="2" placeholder="Instruksi pengerjaan..."></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">PRIORITAS</label>
                            <select name="priority" class="w-full px-3 py-2 border rounded-xl outline-none text-xs bg-white" required>
                                <option value="low">🟢 Low</option>
                                <option value="medium" selected>🟡 Medium</option>
                                <option value="high">🔴 High</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">DEADLINE</label>
                            <input type="date" name="deadline" class="w-full px-3 py-2 border rounded-xl outline-none text-xs" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">PELAKSANA (ASSIGNEE)</label>
                        <select name="assignee_id" class="w-full px-3 py-2 border rounded-xl outline-none text-xs bg-white">
                            <option value="">-- Belum Ditunjuk --</option>
                            @foreach($teamMembers as $member)
                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 rounded-xl text-xs transition shadow-md shadow-indigo-600/10">
                        Simpan & Kirim ke Papan
                    </button>
                </form>
            </div>

            <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                
                <div class="bg-slate-200/60 p-4 rounded-2xl border border-slate-300/40">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-600 mb-3 flex justify-between">📋 TO-DO <span>({{ $tasks->where('status', 'todo')->count() }})</span></h4>
                    <div class="space-y-3">
                        @foreach($tasks->where('status', 'todo') as $task)
                            @include('dashboard-card', ['task' => $task, 'nextStatus' => 'in_progress', 'btnText' => 'Kerjakan ⚡'])
                        @endforeach
                    </div>
                </div>

                <div class="bg-slate-200/60 p-4 rounded-2xl border border-slate-300/40">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-600 mb-3 flex justify-between">⚡ IN PROGRESS <span>({{ $tasks->where('status', 'in_progress')->count() }})</span></h4>
                    <div class="space-y-3">
                        @foreach($tasks->where('status', 'in_progress') as $task)
                            @include('dashboard-card', ['task' => $task, 'nextStatus' => 'done', 'btnText' => 'Selesai ✅'])
                        @endforeach
                    </div>
                </div>

                <div class="bg-slate-200/60 p-4 rounded-2xl border border-slate-300/40">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-600 mb-3 flex justify-between">✅ DONE <span>({{ $tasks->where('status', 'done')->count() }})</span></h4>
                    <div class="space-y-3">
                        @foreach($tasks->where('status', 'done') as $task)
                            @include('dashboard-card', ['task' => $task, 'nextStatus' => null])
                        @endforeach
                    </div>
                </div>

            </div>

        </div>
        @else
            <div class="bg-amber-50 border border-amber-200 text-amber-800 p-6 rounded-2xl text-center font-medium">
                Lu belum memiliki atau bergabung ke workspace apa pun, bro. Silakan buat tim dulu lewat tombol di atas!
            </div>
        @endif

    </div>
</body>
</html>