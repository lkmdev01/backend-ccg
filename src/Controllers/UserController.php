<?php

namespace Src\Controllers;

use Src\Database\Database;

class UserController
{
  private $db;

  public function __construct()
  {
    $database = new Database();
    $this->db = $database->getConnection();
  }

  public function getUsers()
  {
    $query = "SELECT * FROM users"; // Query para selecionar todos os usuários
    $stmt = $this->db->prepare($query); // Prepara a consulta
    $stmt->execute(); // Executa a consulta

    $users = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Busca todos os resultados

    return $users; // Retorna os usuários em formato de array
  }
}
