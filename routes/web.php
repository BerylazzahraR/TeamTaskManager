<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Halaman utama welcome default Laravel
Route::get('/', function () {
    return view('welcome');
});
Route::middleware('guest')->group(function () {
    // Halaman & Proses Login Custom
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    // Halaman & Proses Daftar Akun Custom
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    // Proses Logout Custom
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    

// Halaman Dashboard Utama (Mengambil semua workspace milik user)
// Halaman Dashboard Utama (Menampilkan Form + Daftar Tugas Aktif)
Route::get('/dashboard', function (\Illuminate\Http\Request $request) {
    $user = auth()->user();
    
    // 1. Ambil semua workspace tempat user ini bergabung
    $teams = \Illuminate\Support\Facades\DB::table('team_members')
        ->join('teams', 'team_members.team_id', '=', 'teams.id')
        ->where('team_members.user_id', $user->id)
        ->select('teams.*', 'team_members.role')
        ->get();

    // 2. Tentukan workspace mana yang sedang aktif dilihat (diambil dari parameter URL ?team_id=xxx)
    // Jika tidak ada parameter di URL, default pakai workspace pertama
    $selectedTeamId = $request->query('team_id', $teams->first()?->id);

    $currentTeam = null;
    $tasks = collect();
    $teamMembers = collect();

    if ($selectedTeamId) {
        // Ambil detail workspace yang sedang dipilih
        $currentTeam = $teams->where('id', $selectedTeamId)->first();

        if ($currentTeam) {
            // Ambil daftar tugas di workspace ini lewat Task Repository kita
            $taskRepo = app(\App\Repositories\Contracts\TaskRepositoryInterface::class);
            $tasks = $taskRepo->getFiltered($currentTeam->id, []);

            // Ambil daftar anggota tim untuk dropdown Assignee
            $teamMembers = \Illuminate\Support\Facades\DB::table('team_members')
                ->join('users', 'team_members.user_id', '=', 'users.id')
                ->where('team_members.team_id', $currentTeam->id)
                ->select('users.id', 'users.name', 'users.email')
                ->get();
        }
    }

    return view('dashboard', compact('teams', 'currentTeam', 'tasks', 'teamMembers'));
})->name('dashboard');

// RUTE API BARU: Untuk mengambil data anggota tim secara dinamis lewat JavaScript
Route::get('/api/teams/{teamId}/members', function ($teamId) {
    // Pastikan user yang ngakses emang anggota di tim itu biar aman
    $isMember = \Illuminate\Support\Facades\DB::table('team_members')
        ->where('team_id', $teamId)
        ->where('user_id', auth()->id())
        ->exists();

    if (!$isMember) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $members = \Illuminate\Support\Facades\DB::table('team_members')
        ->join('users', 'team_members.user_id', '=', 'users.id')
        ->where('team_members.team_id', $teamId)
        ->select('users.id', 'users.name', 'users.email')
        ->get();

    return response()->json($members);
})->name('api.teams.members');

    //Team Management (Minggu 1)
    Route::get('/teams/create', [\App\Http\Controllers\Team\TeamController::class, 'create'])->name('teams.create');
    Route::post('/teams', [\App\Http\Controllers\Team\TeamController::class, 'store'])->name('teams.store');
    //Task
    Route::post('/tasks', [\App\Http\Controllers\Task\TaskController::class, 'store'])->name('tasks.store');
    Route::patch('/tasks/{id}/status', [\App\Http\Controllers\Task\TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
    Route::delete('/tasks/{id}', [\App\Http\Controllers\Task\TaskController::class, 'destroy'])->name('tasks.destroy');
    // ============================================================================

    // Rute Profile bawaan
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});