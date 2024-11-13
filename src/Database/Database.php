<?php

namespace Src\Database;

use PDO;
use PDOException;

class Database
{
  private $conn;

  public function __construct() {}

  public function getConnection()
  {
    $this->conn = null;

    try {
      // Usa as variáveis de ambiente do sistema
      $host = getenv('DB_HOST');
      $db_name = getenv('DB_NAME');
      $username = getenv('DB_USER');
      $password = getenv('DB_PASS');

      if (!$host || !$db_name || !$username || !$password) {
        throw new \Exception("Erro: As variáveis de ambiente não foram configuradas corretamente.");
      }

      // Estabelece a conexão com o banco de dados
      $this->conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $exception) {
      echo "Connection error: " . $exception->getMessage();
    } catch (\Exception $e) {
      echo $e->getMessage();
    }

    return $this->conn;
  }
}
