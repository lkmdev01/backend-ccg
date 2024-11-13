<?php

namespace Src\Controllers;

use Src\Database\Database;

class SiteInfoController
{
  private $db;

  public function __construct()
  {
    $database = new Database();
    $this->db = $database->getConnection();
  }

  public function getSiteInfo()
  {
    $query = $this->db->query("SELECT * FROM site_info");
    return $query->fetchAll(\PDO::FETCH_ASSOC);
  }
}
