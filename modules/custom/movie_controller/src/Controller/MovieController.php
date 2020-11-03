<?php

  namespace Drupal\movie_controller\Controller;

  use Drupal\Core\Controller\ControllerBase;
  use Symfony\Component\DependencyInjection\ContainerInterface;
  use \Drupal\Core\Entity\EntityTypeManagerInterface;
  use Drupal\Core\Form\FormBuilderInterface;
  use Drupal\taxonomy\Entity\Term;
  use Doctrine\ORM\Tools\Pagination\Paginator;

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

public function getType()
{
  $tids =$this->entityQuery->get('taxonomy_term')->execute();
$terms = $this->entityTypeManager()->getStorage('taxonomy_term')->loadMultiple($tids);
  foreach($terms as $term) {
  $movietypes[] = array(
    'zanr'  => $term->label()
      );  
  }
  return $movietypes;
}

public function search()
{
  $zanr = \Drupal::request()->request->get('selected');
  $find = \Drupal::request()->request->get('search_movie');

   $nids1=$this->entityQuery->get('node')->condition('type', 'movie')->condition('field_movie_title', $find, 'CONTAINS')->execute();
   $node_storage1 = $this->entityTypeManager()->getStorage('node');  
   $nodes1 = $node_storage1->loadMultiple($nids1);
   if($find!==null&&$find!==""){
   foreach($nodes1 as $node1) {
      $items1[] = array(
        'search'  => $node1->field_movie_title->value,
    );
  }}
if($zanr!==null){
    foreach($nodes1 as $node1) {
      $tids = $node1->field_movie_type->target_id;
      $terms = $this->entityTypeManager()->getStorage('taxonomy_term')->load($tids);
      if($zanr==$terms->name->value){
      $items1[] = array(
        'search'  => $node1->field_movie_title->value,
    );
  }
}
}
if($zanr!==null&&$find!==null&&$find!==""){
  foreach($nodes1 as $node1) {
    $tids = $node1->field_movie_type->target_id;
    $terms = $this->entityTypeManager()->getStorage('taxonomy_term')->load($tids);
    if($zanr==$terms->name->value){
    $items1[] = array(
      'search'  => $node1->field_movie_title->value,
  );
}
}
}
return $items1;
}
  
 public function movie(){
 // $form = \Drupal::formBuilder()->getForm('Drupal\movie_controller\Form\MovieSettingsForm');
 // $text = \Drupal::request()->get('film_text');
 // $broj = \Drupal::request()->get('film_broj');

$items = $this->displayMovies($this->items);
$movietypes = $this->getType($this->movietypes);
$items1 = $this->search($this->items1);


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


