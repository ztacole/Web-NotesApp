<?php

require_once __DIR__ . '/../data/controller/NotesController.php';
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

header('Content-Type: application/json');

$notesController = new NotesController($_SESSION['idUser']);

try {
    $data = json_decode(file_get_contents("php://input"), true);

    $note = new Notes();
    $note->title = $data['title'];
    $note->content = $data['content'];

    if ($notesController->addNote($note)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menambahkan catatan.']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}