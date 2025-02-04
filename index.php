<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Notes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 70px;
        }

        .add-note-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            line-height: 0px;
            font-size: 42px;
            padding-top: 0px;
            z-index: 1030;
        }

        .card {
            cursor: pointer;
        }

        .nav-link {
            color: aliceblue;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">Catatan Saya</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="process/logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Konten Utama -->
    <div class="container mt-4">
        <div id="notesContainer" class="row"></div>
    </div>

    <!-- Tombol Tambah Catatan -->
    <button class="btn btn-primary add-note-btn" data-bs-toggle="modal" data-bs-target="#noteModal">+</button>

    <!-- Modal Tambah Catatan -->
    <div class="modal fade" id="noteModal" tabindex="-1" aria-labelledby="noteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="noteModalLabel">Tambah Catatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="noteForm">
                        <div class="mb-3">
                            <label for="noteTitle" class="form-label">Judul</label>
                            <input type="text" class="form-control" id="noteTitle">
                        </div>
                        <div class="mb-3">
                            <label for="noteContent" class="form-label">Isi Catatan</label>
                            <textarea class="form-control" id="noteContent" rows="3"></textarea>
                        </div>
                        <button type="submit" data-bs-dismiss="modal" class="btn btn-primary w-100">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal View Note -->
    <div class="modal fade" id="viewNoteModal" tabindex="-1" aria-labelledby="viewNoteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewNoteModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="viewNoteContent"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Catatan -->
    <div class="modal fade" id="editNoteModal" tabindex="-1" aria-labelledby="editNoteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editNoteModalLabel">Edit Catatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editNoteForm">
                        <input type="hidden" id="editNoteId">
                        <div class="mb-3">
                            <label for="editNoteTitle" class="form-label">Judul</label>
                            <input type="text" class="form-control" id="editNoteTitle">
                        </div>
                        <div class="mb-3">
                            <label for="editNoteContent" class="form-label">Isi Catatan</label>
                            <textarea class="form-control" id="editNoteContent" rows="3"></textarea>
                        </div>
                        <button type="submit" data-bs-dismiss="modal" class="btn btn-primary w-100">Simpan
                            Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            loadNotes();

            // Event listener untuk tombol "Tambah Catatan"
            document.getElementById("noteForm").addEventListener("submit", function (event) {
                event.preventDefault();

                let title = document.getElementById("noteTitle").value || 'Tak Berjudul';
                let content = document.getElementById("noteContent").value;

                if (!content) {
                    alert("Catatan harus diisi.");
                    return;
                }

                if (!validateNotes(title, content)) {
                    alert("Judul dan isi catatan tidak boleh mengandung karakter spesial.");
                    return;
                }

                fetch('process/addNotes.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ title: title, content: content })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert("Catatan berhasil ditambahkan.");
                        } else {
                            alert("Catatan gagal ditambahkan.");
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });

                document.getElementById("noteForm").reset();
                let modal = new bootstrap.Modal(document.getElementById("noteModal"));
                modal.hide();

                loadNotes();
            });
        });

        // Function to load notes
        function loadNotes() {
            let notesContainer = document.getElementById("notesContainer");
            notesContainer.innerHTML = "";

            fetch('process/loadNotes.php')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        let notes = data.data;
                        if (notes.length === 0) {
                            notesContainer.innerHTML = "Belum ada catatan.";
                        }
                        notes.forEach(note => {
                            let noteCard = `
                                <div class="col-md-4 mb-3">
                                    <div class="card shadow-sm" onclick="viewNote('${note.title}', '${note.content}')">
                                        <div class="card-body">
                                            <h5 class="card-title">${note.title.substring(0, 20)}${note.title.length > 20 ? '...' : ''}</h5>
                                            <p class="card-text">${note.content.substring(0, 40)}${note.content.length > 40 ? '...' : ''}</p>
                                            <button class="btn btn-danger btn-sm" onclick="deleteNote(${note.id}); event.stopPropagation();">Hapus</button>
                                            <button class="btn btn-warning btn-sm" onclick="updateNote(${note.id}, '${note.title}', '${note.content}'); event.stopPropagation();">Ubah</button>
                                        </div>
                                    </div>
                                </div>
                            `;
                            notesContainer.innerHTML += noteCard;
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        //View Note
        function viewNote(title, content) {
            document.getElementById('viewNoteModalLabel').textContent = title;
            document.getElementById('viewNoteContent').textContent = content;
            new bootstrap.Modal(document.getElementById('viewNoteModal')).show();
        }

        //Delete Note
        function deleteNote(id) {
            fetch('process/deleteNotes.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ idNote: id })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert("Catatan berhasil dihapus.");
                    } else {
                        alert("Catatan gagal dihapus.");
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            loadNotes();
        }

        //Update Note
        function updateNote(id, title, content) {
            document.getElementById('editNoteId').value = id;
            document.getElementById('editNoteTitle').value = title;
            document.getElementById('editNoteContent').value = content;

            new bootstrap.Modal(document.getElementById('editNoteModal')).show();
        }

        //Submit Edit Note
        document.getElementById("editNoteForm").addEventListener("submit", function (event) {
            event.preventDefault();

            let id = document.getElementById("editNoteId").value;
            let title = document.getElementById("editNoteTitle").value || 'Tak Berjudul';
            let content = document.getElementById("editNoteContent").value;

            if (!content) {
                alert("Catatan harus diisi.");
                return;
            }

            if (!validateNotes(title, content)) {
                alert("Judul dan isi catatan tidak boleh mengandung karakter spesial.");
                return;
            }

            fetch('process/updateNotes.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ idNote: id, title: title, content: content })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert("Catatan berhasil diperbarui.");
                    } else {
                        alert("Gagal memperbarui catatan.");
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });

            let modal = new bootstrap.Modal(document.getElementById("editNoteModal"));
            modal.hide();
            loadNotes();
        });

        // Function to validate notes input
        function validateNotes(title, content) {
            var regex = /[-_#$%^&*|<>]/;

            if (regex.test(title) || regex.test(content)) {
                return false;
            } else {
                return true;
            }
        }

    </script>
</body>

</html>