<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TodoController extends Controller
{
    // Halaman Home: Menampilkan daftar ToDo
    public function index()
    {
        $todos = Todo::latest()->get();
        return view('todos.index', compact('todos'));
    }

    // Halaman Formulir Buat ToDo
    public function create()
    {
        return view('todos.create');
    }

    // Menyimpan ToDo baru ke Database
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Todo::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('todos.index')->with('success', 'ToDo berhasil ditambahkan!');
    }

    // Mengubah status Selesai / Belum Selesai (Checklist)
    public function toggleComplete(Todo $todo)
    {
        $todo->is_completed = !$todo->is_completed;
        
        // Jika ditandai selesai, simpan tanggal sekarang. Jika batal, set null.
        $todo->completed_at = $todo->is_completed ? Carbon::now() : null;
        $todo->save();

        return redirect()->back();
    }
    
    // Opsional: Hapus ToDo
    public function destroy(Todo $todo)
    {
        $todo->delete();
        return redirect()->route('todos.index')->with('success', 'ToDo berhasil dihapus!');
    }
}