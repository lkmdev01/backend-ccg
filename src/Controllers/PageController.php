<?php

namespace Src\Controllers;

use Src\Database\Database;

class PageController
{
  private $db;

  public function __construct()
  {
    $database = new Database();
    $this->db = $database->getConnection();
  }

  public function getPages()
  {
    $query = $this->db->query("SELECT * FROM pages");
    return $query->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function getPage($slug)
  {
    // Dados principais da página
    $pageQuery = $this->db->prepare("SELECT * FROM pages WHERE slug = :slug");
    $pageQuery->execute(['slug' => $slug]);
    $page = $pageQuery->fetch(\PDO::FETCH_ASSOC);

    if (!$page) {
      http_response_code(404);
      return ["error" => "Página não encontrada"];
    }

    // Banners da página
    $bannerQuery = $this->db->prepare("SELECT * FROM banners WHERE page_id = :page_id");
    $bannerQuery->execute(['page_id' => $page['id']]);
    $banners = $bannerQuery->fetchAll(\PDO::FETCH_ASSOC);

    // Seções da página
    $sectionQuery = $this->db->prepare("SELECT * FROM sections WHERE page_id = :page_id");
    $sectionQuery->execute(['page_id' => $page['id']]);
    $sections = $sectionQuery->fetchAll(\PDO::FETCH_ASSOC);

    // Eventos relacionados à página
    $eventQuery = $this->db->prepare("SELECT * FROM events WHERE page_id = :page_id");
    $eventQuery->execute(['page_id' => $page['id']]);
    $events = $eventQuery->fetchAll(\PDO::FETCH_ASSOC);

    // Campos de formulário da página
    $formQuery = $this->db->prepare("SELECT * FROM forms WHERE page_id = :page_id");
    $formQuery->execute(['page_id' => $page['id']]);
    $formFields = $formQuery->fetchAll(\PDO::FETCH_ASSOC);

    // Estrutura dos dados da página
    return [
      "page" => $page,
      "banners" => $banners,
      "sections" => $sections,
      "events" => $events,
      "formFields" => $formFields
    ];
  }

  public function createPage($slug, $title, $content, $image_url)
  {
    // Validação simples dos dados
    if (empty($slug) || empty($title) || empty($content)) {
      http_response_code(400);
      return ["error" => "Slug, título e conteúdo são obrigatórios."];
    }

    $query = $this->db->prepare("INSERT INTO pages (slug, title, content, image_url, created_at, updated_at) VALUES (:slug, :title, :content, :image_url, NOW(), NOW())");
    $query->execute(['slug' => $slug, 'title' => $title, 'content' => $content, 'image_url' => $image_url]);
    return ["id" => $this->db->lastInsertId(), "message" => "Página criada com sucesso."];
  }

  public function updatePage($slug, $title, $content, $image_url)
  {
    // Validação simples dos dados
    if (empty($title) || empty($content)) {
      http_response_code(400);
      return ["error" => "Título e conteúdo são obrigatórios para atualização."];
    }

    $query = $this->db->prepare("UPDATE pages SET title = :title, content = :content, image_url = :image_url, updated_at = NOW() WHERE slug = :slug");
    $query->execute(['title' => $title, 'content' => $content, 'image_url' => $image_url, 'slug' => $slug]);

    if ($query->rowCount() === 0) {
      http_response_code(404);
      return ["error" => "Página não encontrada para atualização."];
    }

    return ["message" => "Página atualizada com sucesso."];
  }

  public function deletePage($slug)
  {
    $query = $this->db->prepare("DELETE FROM pages WHERE slug = :slug");
    $query->execute(['slug' => $slug]);

    if ($query->rowCount() === 0) {
      http_response_code(404);
      return ["error" => "Página não encontrada para exclusão."];
    }

    return ["message" => "Página deletada com sucesso."];
  }
}
