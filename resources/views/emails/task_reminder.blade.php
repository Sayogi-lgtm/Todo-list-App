<!DOCTYPE html>
<html>
<head>
    <title>Pengingat Tugas</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
        <h2 style="color: #eab308;">Halo, {{ $task->user->name }}! 👋</h2>
        
        <p>Ini adalah pengingat otomatis dari <strong>To-Do List App</strong> kamu.</p>
        
        <p>Tugas kamu yang berjudul: <br>
           <strong style="font-size: 18px; color: #dc2626;">"{{ $task->title }}"</strong>
        </p>
        
        <p>Memiliki tenggat waktu pada: <strong>{{ \Carbon\Carbon::parse($task->due_date)->format('d F Y') }}</strong>.</p>
        
        <p>Jangan lupa untuk segera diselesaikan dan tandai selesai di Dashboard ya!</p>
        
        <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
        <small style="color: #999;">Email ini dikirim secara otomatis. Tetap semangat dan produktif!</small>
    </div>
</body>
</html>