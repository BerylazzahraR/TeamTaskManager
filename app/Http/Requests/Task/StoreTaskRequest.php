<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Izinkan semua user yang sudah login untuk submit data task
    }

    public function rules(): array
    {
        return [
            'team_id'     => ['required', 'integer', 'exists:teams,id'], // Wajib ada di tabel teams
            'title'       => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'status'      => ['required', 'string', 'in:todo,in_progress,done'], // Hanya boleh pilih 3 status ini
            'assignee_id' => ['nullable', 'integer', 'exists:users,id'], // Boleh kosong (jika belum didelegasikan)
            'deadline'    => ['required', 'date'], // Wajib berformat tanggal penayangan tugas
        ];
    }
}