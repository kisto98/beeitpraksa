<?php

namespace Drupal\movie_controller\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use \Drupal\Core\Entity\EntityTypeManagerInterface;

class MovieController extends ControllerBase {


public $entityQuery;
public $entityTypeManager;

   public function __construct($entityQuery, EntityTypeManagerInterface $entityTypeManager)
{
  $this->entityQuery=$entityQuery;
 $this->entityTypeManager=$entityTypeManager;

}
public static function create(ContainerInterface $container){
return new static (
  $container-> get('entity.query'),
  $container-> get('entity_type.manager')

);
}
 
 
 public function movie(){
 
   $nids=$this->entityQuery->get('node')->condition('type', 'movie')->execute();
   $node_storage = $this->entityTypeManager()->getStorage('node');  
   $nodes = $node_storage->loadMultiple($nids);
     
      foreach($nodes as $node) {
      $items[] = array(
      'filmime'  => $node->field_movie_title->value,
      'opis' => $node->field_description->value,
      'slika'=> $node->field_imagename->entity->getFileUri()    
    
    );
        
  }
  
    return array(
      '#datas'=> $items, 
      '#title'=> 'Lista filmova',
      '#theme'=>'movie'
    );
  }
}
