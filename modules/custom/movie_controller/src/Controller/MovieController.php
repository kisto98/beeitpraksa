<?php

  namespace Drupal\movie_controller\Controller;

  use Drupal\Core\Controller\ControllerBase;
  use Symfony\Component\DependencyInjection\ContainerInterface;
  use \Drupal\Core\Entity\EntityTypeManagerInterface;
  use Drupal\Core\Form\FormBuilderInterface;
  use Drupal\taxonomy\Entity\Term;
  use Doctrine\ORM\Tools\Pagination\Paginator;
  use Drupal\taxonomy;

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
  $container->get('form_builder'),
);
}
  
public function displayMovies($items){
  $config = \Drupal::config('movie.settings');
  $broj =$config->get('film_broj');
  
  $nids=$this->entityQuery->get('node')
  ->condition('type', 'movie')
  ->sort('created', 'DESC')
  ->pager($broj)
  ->execute();
  $node_storage = $this->entityTypeManager()->getStorage('node');  
  $nodes = $node_storage->loadMultiple($nids);

foreach($nodes as $node) {
    $tids = $node->field_movie_type->target_id;
$terms = $this->entityTypeManager()->getStorage('taxonomy_term')->load($tids);
    $items[] = array(
    'filmime'  => $node->field_movie_title->value,
      'opis' => $node->field_description->value,
     'slika'=> $node->field_imagename->entity->getFileUri(),
     'type'=>$node=$terms->name->value,
      );  
}
return $items;
}


public function getIds(){
 
  $find = \Drupal::request()->request->get('search_movie');
  $zanrid = \Drupal::request()->request->get('selected');
  
  if(!empty($find) && empty($zanrid)){
    $nids1=$this->entityQuery->get('node')->condition('type', 'movie')->condition('field_movie_title', $find, 'CONTAINS')->execute();
  }
  else if(empty($find) && !empty($zanrid)){
    $nids1=$this->entityQuery->get('node')->condition('type', 'movie')->condition('field_movie_type', $zanrid)->execute();
  }
  else if(!empty($find) && !empty($zanrid)){
    $nids1=$this->entityQuery->get('node')->condition('type', 'movie')->condition('field_movie_title', $find, 'CONTAINS')->condition('field_movie_type', $zanrid)->execute();
  }
else {
      $nids1=array(); #mora imate vrednost ako nema vraca 0 i onda nodes ucitava sve na prvom loadu
  }
return $nids1; 
} 

public function loadIds(){
  $nids1 = $this->getIds($this->nids1);
  $nodes1= $this->entityTypeManager()->getStorage('node')->loadMultiple($nids1);
 
  return $nodes1; 
 }

public function search(){
  $nodes1 = $this->loadIds($this->nodes1);
  
  foreach($nodes1 as $node1) {
  $items1[] = array(
    'search'  => $node1->field_movie_title->value,
    );
  }
return $items1;
}

public function getType()
{
  $tids =$this->entityQuery->get('taxonomy_term')->execute();
  $terms = $this->entityTypeManager()->getStorage('taxonomy_term')->loadMultiple($tids);

  foreach($terms as $term) {
  $movietypes[] = array(
    'zanr'  => $term->name->value, //label()
    'id'=>$term->tid->value
      );  
  }
  return $movietypes;
}

public function movie(){

 $this->getIds($this->nids1);
 $this->loadIds($this->nids1);
 $items1 = $this->search($this->items1);
 $items = $this->displayMovies($this->items);
$movietypes = $this->getType($this->movietypes);

 $form['pager'] = array(
    '#type' => 'pager',
  );
  $path = \Drupal::service('path.current')->getPath();

   return array(
    '#datas'=> $items,
    '#title'=> 'Lista filmova',
    '#theme'=>'movie',
    '#finds'=> $items1,
    '#types'=>$movietypes,
    '#path'  => $path,
    '#form'=>$form
     );
  

 }  
}


