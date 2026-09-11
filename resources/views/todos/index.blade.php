<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo List</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen py-12 px-4 sm:px-6">
    <div class="max-w-2xl mx-auto">
        
        <!-- Header Section -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Daftar Tugas</h1>
                <p class="text-slate-500 text-sm mt-1">Kelola dan pantau progres tugas harianmu.</p>
            </div>
            <a href="{{ route('todos.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2.5 rounded-xl shadow-sm hover:shadow transition-all duration-200 text-sm">
                <i class="fa-solid fa-plus text-xs"></i>
                Tambah ToDo
            </a>
        </div>

        <!-- Alert Success -->
        @if(session('success'))
            <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6 text-sm">
                <i class="fa-solid fa-circle-check text-emerald-500"></i>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Card List Container -->
        <div class="space-y-3">
            @forelse($todos as $todo)
                <!-- Individual ToDo Card -->
                <div class="group relative bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm hover:shadow-md hover:border-slate-300 transition-all duration-200 flex items-start justify-between gap-4">
                    
                    <div class="flex items-start gap-4 flex-1">
                        <!-- Custom Animated Checkbox -->
                        <form action="{{ route('todos.toggle', $todo->id) }}" method="POST" class="pt-0.5">
                            @csrf
                            @method('PATCH')
                            <label class="relative flex items-center justify-center w-6 h-6 cursor-pointer">
                                <input type="checkbox" onchange="this.form.submit()" {{ $todo->is_completed ? 'checked' : '' }} class="peer sr-only">
                                <div class="w-6 h-6 border-2 border-slate-300 rounded-lg peer-checked:bg-emerald-500 peer-checked:border-emerald-500 transition-all flex items-center justify-center group-hover:border-slate-400">
                                    <i class="fa-solid fa-check text-white text-xs opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                </div>
                            </label>
                        </form>

                        <!-- Content Area -->
                        <div class="space-y-1.5 flex-1">
                            <div class="flex items-center gap-2">
                                <h3 class="font-semibold text-base {{ $todo->is_completed ? 'line-through text-slate-400' : 'text-slate-800' }} transition-colors">
                                    {{ $todo->title }}
                                </h3>
                                
                                <!-- Status Badge -->
                                @if($todo->is_completed)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                        <i class="fa-solid fa-check text-[9px]"></i> Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium bg-amber-50 text-amber-700 border border-amber-200/60">
                                        <i class="fa-regular fa-clock text-[9px]"></i> Pending
                                    </span>
                                @endif
                            </div>

                            @if($todo->description)
                                <p class="text-slate-500 text-sm leading-relaxed {{ $todo->is_completed ? 'text-slate-400' : '' }}">
                                    {{ $todo->description }}
                                </p>
                            @endif

                            <!-- Timestamp Info -->
                            <div class="flex items-center gap-4 pt-1 text-xs text-slate-400">
                                <span class="flex items-center gap-1.5">
                                    <i class="fa-regular fa-calendar text-slate-300"></i>
                                    Dibuat: {{ $todo->created_at->format('d M Y') }}
                                </span>

                                @if($todo->is_completed && $todo->completed_at)
                                    <span class="flex items-center gap-1.5 text-emerald-600 font-medium">
                                        <i class="fa-solid fa-circle-check text-emerald-500"></i>
                                        Selesai: {{ $todo->completed_at->format('d M Y, H:i') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Action Button (Delete) -->
                    <form action="{{ route('todos.destroy', $todo->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus ToDo ini?')" 
                                class="opacity-0 group-hover:opacity-100 p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all duration-150"
                                title="Hapus ToDo">
                            <i class="fa-regular fa-trash-can text-sm"></i>
                        </button>
                    </form>

                </div>
            @empty
                <!-- Empty State -->
                <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-slate-300 p-8">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-500 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <i class="fa-solid fa-clipboard-list text-xl"></i>
                    </div>
                    <h3 class="text-slate-800 font-semibold mb-1">Belum ada tugas</h3>
                    <p class="text-slate-400 text-sm mb-4">Buat tugas pertama Anda untuk mulai mencatat progres.</p>
                    <a href="{{ route('todos.create') }}" class="inline-flex items-center gap-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 font-medium px-4 py-2 rounded-lg text-sm transition-colors">
                        <i class="fa-solid fa-plus text-xs"></i>
                        Tambah ToDo Baru
                    </a>
                </div>
            @endforelse
        </div>

    </div>
</body>
</html>