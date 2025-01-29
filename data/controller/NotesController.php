<?php

include __DIR__ . '/../model/Notes.php';
include __DIR__ . '/../remote/Connection.php';

class NotesController
{
    private int $idUser;
    private ?PDO $connection;
    public function __construct(int $idUser) {
        $this->idUser = $idUser;
        $this->connection = Connection::connect();
    }

    public function getNotes() {
        $statement = $this->connection->prepare("SELECT * FROM notes WHERE idUser = :idUser");
        $statement->execute(['idUser' => $this->idUser]);
        $notes = $statement->fetchAll(PDO::FETCH_CLASS, Notes::class);
        return $notes;
    }

    public function addNote(Notes $note) {
        $statement = $this->connection->prepare("INSERT INTO notes (title, content, idUser) VALUES (:title, :content, :idUser)");
        return $statement->execute(['title' => $note->title, 'content' => $note->content, 'idUser' => $this->idUser]);
    }

    public function deleteNote(int $idNote) {
        $statement = $this->connection->prepare("DELETE FROM notes WHERE id = :idNote");
        return $statement->execute(['idNote' => $idNote]);
    }
}