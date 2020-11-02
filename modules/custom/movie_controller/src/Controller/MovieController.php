<?php

  namespace Drupal\movie_controller\Controller;

  use Drupal\Core\Controller\ControllerBase;
  use Symfony\Component\DependencyInjection\ContainerInterface;
  use \Drupal\Core\Entity\EntityTypeManagerInterface;
  use Drupal\Core\Form\FormBuilderInterface;
  use Drupal\taxonomy\Entity\Term;

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
  
 public function movie(){
 // $form = \Drupal::formBuilder()->getForm('Drupal\movie_controller\Form\MovieSettingsForm');
 // $text = \Drupal::request()->get('film_text');
 // $broj = \Drupal::request()->get('film_broj');
  $config = \Drupal::config('movie.settings');
  $broj =$config->get('film_broj');

  $nids=$this->entityQuery->get('node')
  ->condition('type', 'movie')
  ->sort('created', 'DESC')
  ->range(0,$broj)
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


$tid=$this->entityQuery->get('taxonomy_term')->execute();
$trmes = $this->entityTypeManager()->getStorage('taxonomy_term')->loadMultiple($tid);


foreach($trmes as $trm) {

 $niz[] = array(
  'tip'  => $trm->label()
    );  
}
# pronalazi filmove sa nazivom
  $tipovi = \Drupal::request()->request->get('selected');
  $tekst = \Drupal::request()->request->get('search_movie');

   $nids1=$this->entityQuery->get('node')->condition('type', 'movie')->condition('field_movie_title', $tekst, 'CONTAINS')->execute();
   $node_storage1 = $this->entityTypeManager()->getStorage('node');  
   $nodes1 = $node_storage1->loadMultiple($nids1);
   if($tekst!==null&&$tekst!==""){
   foreach($nodes1 as $node1) {
      $items1[] = array(
        'nesto'  => $node1->field_movie_title->value,
    );
  }}
if($tipovi!==null){
    foreach($nodes1 as $node1) {
      $tids = $node1->field_movie_type->target_id;
      $terms = $this->entityTypeManager()->getStorage('taxonomy_term')->load($tids);
      if($tipovi==$terms->name->value){
      $items1[] = array(
        'nesto'  => $node1->field_movie_title->value,
    );
  }}}


   return array(
    '#datas'=> $items, 
    '#title'=> 'Lista filmova',
    '#theme'=>'movie',
    '#trazis'=> $items1,
    '#drugi'=>$niz,
     );
  
 // $path = \Drupal::service('path.current')->getPath();
 }  
}


