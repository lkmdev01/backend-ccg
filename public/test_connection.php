<?php
$host = getenv('DB_HOST');
$db = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');

try {
  $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo json_encode(["status" => "success", "message" => "Conexão bem-sucedida!"]);
} catch (PDOException $e) {
  echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
