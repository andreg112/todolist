<?php
session_start();

// Inisialisasi tugas
if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [
        ["id" => 1, "title" => "Belajar PHP", "status" => "belum"],
        ["id" => 2, "title" => "Kerjakan tugas UX", "status" => "selesai"]
    ];
}

// Tambah tugas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah'])) {
    $judul = trim($_POST['tugas']);
    if ($judul !== '') {
        $id = count($_SESSION['tasks']) + 1;
        $_SESSION['tasks'][] = ["id" => $id, "title" => $judul, "status" => "belum"];
    }
}

// Ubah status
if (isset($_POST['ubah_status'])) {
    $id = $_POST['ubah_status'];
    foreach ($_SESSION['tasks'] as &$task) {
        if ($task['id'] == $id) {
            $task['status'] = ($task['status'] === 'selesai') ? 'belum' : 'selesai';
            break;
        }
    }
}

// Hapus tugas
if (isset($_POST['hapus'])) {
    $id = $_POST['hapus'];
    $_SESSION['tasks'] = array_filter($_SESSION['tasks'], fn($task) => $task['id'] != $id);
}

/**
 * Tampilkan daftar tugas (dengan checkbox dan hapus)
 */
function tampilkanDaftar($tasks)
{
    foreach ($tasks as $task) {
        $statusClass = ($task['status'] == 'selesai') ? 'text-success' : 'text-danger';
        $checked = ($task['status'] == 'selesai') ? 'checked' : '';
        echo "<div class='d-flex justify-content-between align-items-center bg-light rounded px-3 py-2 mb-2'>";
        echo "<form method='post' class='d-flex align-items-center m-0 p-0 gap-2'>";
        echo "<input type='hidden' name='ubah_status' value='{$task['id']}'>";
        echo "<input type='checkbox' onChange='this.form.submit()' $checked>";
        echo "<span class='fw-semibold'>" . htmlspecialchars($task['title']) . "</span>";
        echo "</form>";
        echo "<div class='d-flex align-items-center gap-3'>";
        echo "<span class='fw-bold $statusClass'>" . ucfirst($task['status']) . "</span>";
        echo "<form method='post' class='m-0'>";
        echo "<input type='hidden' name='hapus' value='{$task['id']}'>";
        echo "<button class='btn btn-sm btn-outline-danger'>✕</button>";
        echo "</form>";
        echo "</div>";
        echo "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>To-Do List | Rapi & Responsif</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f2f2f2;
        }
        .todo-wrapper {
            max-width: 500px;
            margin: 60px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            font-weight: bold;
            margin-bottom: 25px;
        }
        input[type="text"] {
            border-radius: 8px;
        }
        button {
            border-radius: 8px;
        }
    </style>
</head>
<body>

<div class="todo-wrapper">
    <h2>📝 <span class="text-dark">To-Do List</span> 🥶</h2>

    <!-- Form Tambah -->
    <form method="post" class="d-flex gap-2 mb-3">
        <input type="text" name="tugas" class="form-control" placeholder="Tambahkan tugas baru..." required>
        <button type="submit" name="tambah" class="btn btn-primary">Tambah</button>
    </form>

    <!-- Daftar Tugas -->
    <h5 class="fw-bold">Daftar Tugas:</h5>
    <?php tampilkanDaftar($_SESSION['tasks']); ?>
</div>

</body>
</html>
