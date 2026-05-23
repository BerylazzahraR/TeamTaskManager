<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Http\Requests\Team\StoreTeamRequest;
use App\Actions\Team\CreateTeamAction;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    protected $createTeamAction;

    // Inject CreateTeamAction ke dalam Controller via Constructor
    public function __construct(CreateTeamAction $createTeamAction)
    {
        $this->createTeamAction = $createTeamAction;
    }

    // 1. Menampilkan form pembuatan Workspace baru
    public function create()
    {
        return view('teams.create');
    }

    // 2. Memproses penyimpanan data Workspace baru
    public function store(StoreTeamRequest $request)
    {
        // Ambil ID user yang sedang login saat ini
        $userId = Auth::id();

        // Eksekusi logic bisnis pembuatan tim di Service Layer
        $this->createTeamAction->execute($userId, $request->validated());

        // Redirect ke dashboard dengan flash message success
        return redirect()->route('dashboard')
                         ->with('success', 'Workspace tim berhasil dibuat, bro!');
    }
}