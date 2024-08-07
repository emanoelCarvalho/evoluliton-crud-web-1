<?php
require_once "../config/database.php";

class Autor
{
    private $conn;
    private $table_name = "autores";

    public $id;
    public $nome;
    public $editora;
    public $timestamp_criacao;
    public $timestamp_update;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function create()
    {
        $query = "INSERT INTO " . $this->table_name . " SET nome = :nome, editora = :editora";
        $stmt = $this->conn->prepare($query);
        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->editora = htmlspecialchars(strip_tags($this->editora));
        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":editora", $this->editora);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    function index()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function read()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->nome = $row['nome'];
            $this->editora = $row['editora'];
            $this->timestamp_criacao = $row['timestamp_criacao'];
            $this->timestamp_update = $row['timestamp_update'];
            return true;
        }

        return false;
    }

    function update()
    {
        $query = "UPDATE " . $this->table_name . " SET nome = :nome, editora = :editora WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->editora = htmlspecialchars(strip_tags($this->editora));
        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":editora", $this->editora);
        $stmt->bindParam(":id", $this->id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    function livrosNaoAssociados()
    {
        $query = "SELECT * FROM livros WHERE id NOT IN (SELECT livro_id FROM autores_livros WHERE autor_id = ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt;
    }
}
