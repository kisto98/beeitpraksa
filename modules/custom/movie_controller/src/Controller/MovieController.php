<?php

namespace Drupal\movie_controller\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use \Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Drupal\Core\Render\Markup;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Form\FormState;
use Drupal\movie_controller\Form\MovieForm;

class MovieController extends ControllerBase {

  protected $formBuilder;
public $entityQuery;
public $entityTypeManager;

   public function __construct($entityQuery, EntityTypeManagerInterface $entityTypeManager, FormBuilderInterface $form_builder)
{
  $this->entityQuery=$entityQuery;
 $this->entityTypeManager=$entityTypeManager;
 $this->formBuilder = $form_builder;
}
public static function create(ContainerInterface $container){
return new static (
  $container-> get('entity.query'),
  $container-> get('entity_type.manager'),
  $container->get('form_builder')
);
}
  
 public function movie(){
  $form = \Drupal::formBuilder()->getForm('Drupal\movie_controller\Form\MovieForm');
  $text = \Drupal::request()->get('film_text');

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

     if($text!==null){
    
   $nids1=$this->entityQuery->get('node')->condition('type', 'movie')->condition('title', $text, 'CONTAINS')->execute();
   $node_storage1 = $this->entityTypeManager()->getStorage('node');  
   $nodes1 = $node_storage1->loadMultiple($nids1);
     
      foreach($nodes1 as $node1) {
      $items1[] = array(
        'nesto'  => $node1->field_movie_title->value,
    );
   }
   $text==null;
  }
  
    return array(
      '#datas'=> $items, 
      '#title'=> 'Lista filmova',
      '#theme'=>'movie',
      '#form' => $form,
      '#trazis'=> $items1,
       );
  }
 
}

