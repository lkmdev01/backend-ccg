<?php

namespace Src\Database;

use PDO;
use PDOException;
use Dotenv\Dotenv;

class Database
{
  private $conn;

  public function __construct()
  {
    // Carrega as variáveis do .env
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../../'); // Ajuste o caminho se necessário
    $dotenv->load();
  }

  public function getConnection()
  {
    $this->conn = null;

    try {
      // Usa as variáveis de ambiente do .env
      $host = getenv('DB_HOST');
      $db_name = getenv('DB_NAME');
      $username = getenv('DB_USER');
      $password = getenv('DB_PASS');

      $this->conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $exception) {
      echo "Connection error: " . $exception->getMessage();
    }

    return $this->conn;
  }
}
