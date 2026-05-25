<div class="bg-white p-3 rounded-xl shadow-sm border border-slate-200 flex flex-col justify-between gap-2">
    <div>
        <div class="flex justify-between items-start">
            <h5 class="font-bold text-slate-800 text-xs">{{ $task->title }}</h5>
            <span class="text-[9px] uppercase font-mono px-1.5 py-0.5 rounded font-bold 
                {{ $task->priority == 'high' ? 'bg-red-100 text-red-700' : ($task->priority == 'medium' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">
                {{ $task->priority }}
            </span>
        </div>
        @if($task->description)
            <p class="text-[11px] text-slate-500 mt-1 line-clamp-2">{{ $task->description }}</p>
        @endif
        <p class="text-[10px] text-slate-400 font-mono mt-2">📅 {{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }}</p>
    </div>

    <div class="flex justify-between items-center pt-2 border-t border-slate-100 mt-1">
        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Hapus tugas ini, bro?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-slate-400 hover:text-red-500 text-[10px] transition">Hapus 🗑️</button>
        </form>

        @if($nextStatus)
            <form action="{{ route('tasks.updateStatus', $task->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="{{ $nextStatus }}">
                <button type="submit" class="bg-indigo-50 hover:bg-indigo-600 text-[10px] font-bold text-white px-2 py-1 rounded-md transition">
                    {{ $btnText }}
                </button>
            </form>
        @else
            <span class="text-[9px] bg-emerald-100 text-emerald-700 font-bold px-1.5 py-0.5 rounded-md">Selesai ✨</span>
        @endif
    </div>
</div>