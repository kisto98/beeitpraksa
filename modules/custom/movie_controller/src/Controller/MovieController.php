<?php

namespace Drupal\movie_controller\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;

class MovieController {


  
public function movie() {
      
  
  $db = Database::getConnection();
    $query = $db->select('node__field_movie_title', 'nrft');
    $query->join('node__field_description', 'nrfd', 'nrfd.entity_id = nrft.entity_id');
    $query->join('node__field_imagename', 'nrfp', 'nrfd.entity_id = nrfp.entity_id');
    $query->fields('nrft', ['field_movie_title_value']);
    $query->fields('nrfd', ['field_description_value']);
    $query->fields('nrfp', ['field_imagename_alt']);
    $result = $query->execute()->fetchAll();
  
    return [
      '#theme' => 'movie',
      '#datas' => $result,
      '#title' => 'movie list'
    ];
   
 }
}

