<?php

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test-todo', function () {
    return Task::all();
});

Route::post('/test-todo', function (Request $request) {
    // Validasi sederhana
    $validated = $request->validate([
        'title' => 'required|string',
        'notes' => 'nullable|string',
    ]);

    // Simpan ke database (sesuaikan dengan model kamu, misal 'Task')
    // Kita hardcode user_id: 1 dulu untuk testing
    $task = \App\Models\Task::create([
        'user_id' => 1, 
        'title' => $validated['title'],
        'notes' => $validated['notes'] ?? null,
        'status' => 'pending',
        'is_important' => false,
    ]);

    return response()->json([
        'message' => 'Data berhasil disimpan!',
        'data' => $task
    ], 201); // 201 artinya 'Created'
});

// update dan delete lewat via postman : masih belajar
Route::patch('/test-todo/{id}', function (Request $request, $id) {
    $task = \App\Models\Task::findOrFail($id);
    $task->update($request->only('status'));

    return response()->json([
        'message' => 'Status berhasil diperbarui!',
        'data' => $task
    ]);
});

Route::delete('/test-todo/{id}', function ($id) {
    $task = \App\Models\Task::findOrFail($id);
    $task->delete();

    return response()->json(['message' => 'Tugas berhasil dihapus']);
});