<?php

require_once __DIR__ . '/../data/controller/NotesController.php';
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$notesController = new NotesController($_SESSION['idUser']);

try {
    $notes = $notesController->getNotes();

    echo json_encode(['status' => 'success', 'data' => $notes]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

?>