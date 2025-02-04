<?php

include __DIR__ . '/../model/Notes.php';
include __DIR__ . '/../remote/Connection.php';

class NotesController
{
    private int $idUser;
    private $connection = null;
    public function __construct(int $idUser) {
        //Simpan ID User
        $this->idUser = $idUser;
        //Koneksi ke database
        $this->connection = Connection::connect();
    }

    // Mendapatkan daftar catatan
    public function getNotes() {
        $statement = $this->connection->prepare("SELECT * FROM notes WHERE idUser = :idUser");
        $statement->execute(['idUser' => $this->idUser]);
        $notes = $statement->fetchAll(PDO::FETCH_CLASS, Notes::class);
        return $notes;
    }

    // Menambahkan catatan
    public function addNote(Notes $note) {
        $statement = $this->connection->prepare("INSERT INTO notes (title, content, idUser) VALUES (:title, :content, :idUser)");
        return $statement->execute(['title' => $note->title, 'content' => $note->content, 'idUser' => $this->idUser]);
    }

    // Menghapus catatan
    public function deleteNote(int $idNote) {
        $statement = $this->connection->prepare("DELETE FROM notes WHERE id = :idNote");
        return $statement->execute(['idNote' => $idNote]);
    }

    // Memperbarui catatan
    public function updateNote(Notes $note) {
        $statement = $this->connection->prepare("UPDATE notes SET title = :title, content = :content WHERE id = :idNote");
        return $statement->execute(['title' => $note->title, 'content' => $note->content, 'idNote' => $note->id]);
    }
}