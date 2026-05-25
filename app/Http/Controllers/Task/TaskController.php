<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Actions\Task\CreateTaskAction;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected $createTaskAction;
    protected $taskRepository;

    // Inject Action & Repository ke dalam Controller via Constructor
    public function __construct(
        CreateTaskAction $createTaskAction,
        TaskRepositoryInterface $taskRepository
    ) {
        $this->createTaskAction = $createTaskAction;
        $this->taskRepository = $taskRepository;
    }

    /**
     * 1. Menyimpan data tugas baru ke database MySQL via Service Layer
     */
    public function store(StoreTaskRequest $request)
    {
        // Jalankan Service Action dengan data form yang sudah tervalidasi bersih
        $this->createTaskAction->execute($request->validated());

        return redirect()->back()->with('success', 'Tugas baru berhasil ditambahkan ke tim, bro!');
    }

    /**
     * 2. Mengubah status tugas secara instan (Untuk fitur Kanban Board / Toggle Status)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', 'string', 'in:todo,in_progress,done']
        ]);

        // Perintahkan Repository untuk langsung meng-update status barunya saja di MySQL
        $this->taskRepository->update($id, ['status' => $request->status]);

        return redirect()->back()->with('success', 'Status tugas berhasil diperbarui, bro!');
    }

    /**
     * 3. Menghapus tugas dari sistem
     */
    public function destroy($id)
    {
        $this->taskRepository->delete($id);

        return redirect()->back()->with('success', 'Tugas berhasil didelete dari workspace.');
    }
}