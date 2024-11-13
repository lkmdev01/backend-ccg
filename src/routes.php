<?php

use Src\Controllers\UserController;
use Src\Controllers\SiteInfoController;
use Src\Controllers\PageController;

// Define rotas
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Rota para obter todos os usuários
if ($requestUri === '/api/users' && $requestMethod === 'GET') {
  $controller = new UserController();
  echo json_encode($controller->getUsers());
}
// Rota para obter as informações do site
elseif ($requestUri === '/api/site-info' && $requestMethod === 'GET') {
  $controller = new SiteInfoController();
  echo json_encode($controller->getSiteInfo());
}
// Rota para obter todas as páginas
elseif (preg_match('/^\/api\/pages\/?$/', $requestUri) && $requestMethod === 'GET') {
  $controller = new PageController();
  echo json_encode($controller->getPages());
}
// Rota para obter uma página específica pelo slug
elseif (preg_match('/^\/api\/pages\/([a-zA-Z0-9-]+)$/', $requestUri, $matches) && $requestMethod === 'GET') {
  $slug = $matches[1];
  $controller = new PageController();
  echo json_encode($controller->getPage($slug));
}
// Rota para criar uma nova página
elseif ($requestMethod === 'POST' && $requestUri === '/api/pages') {
  $controller = new PageController();
  $data = json_decode(file_get_contents('php://input'), true);

  // Certifique-se de que os dados necessários estão presentes
  if (isset($data['slug'], $data['title'], $data['content'], $data['image_url'])) {
    $response = $controller->createPage($data['slug'], $data['title'], $data['content'], $data['image_url']);
    http_response_code(isset($response['error']) ? 400 : 201);
    echo json_encode($response);
  } else {
    http_response_code(400);
    echo json_encode(["error" => "Dados inválidos fornecidos para a criação da página."]);
  }
}
// Rota para atualizar uma página específica pelo slug
elseif ($requestMethod === 'PUT' && preg_match('/^\/api\/pages\/([a-zA-Z0-9-]+)$/', $requestUri, $matches)) {
  $slug = $matches[1];
  $controller = new PageController();
  $data = json_decode(file_get_contents('php://input'), true);

  // Certifique-se de que os dados necessários estão presentes
  if (isset($data['title'], $data['content'], $data['image_url'])) {
    $response = $controller->updatePage($slug, $data['title'], $data['content'], $data['image_url']);
    http_response_code(isset($response['error']) ? 400 : 200);
    echo json_encode($response);
  } else {
    http_response_code(400);
    echo json_encode(["error" => "Dados inválidos fornecidos para a atualização da página."]);
  }
}
// Rota para deletar uma página específica pelo slug
elseif ($requestMethod === 'DELETE' && preg_match('/^\/api\/pages\/([a-zA-Z0-9-]+)$/', $requestUri, $matches)) {
  $slug = $matches[1];
  $controller = new PageController();
  $response = $controller->deletePage($slug);
  http_response_code(isset($response['error']) ? 404 : 200);
  echo json_encode($response);
}
// Rota não encontrada
else {
  http_response_code(404);
  echo json_encode(["error" => "Rota não encontrada"]);
}
