<?php

namespace Drupal\movie_controller\Form;


use Symfony\Component\DependencyInjection\ContainerInterface;
use \Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Messenger;
use Drupal\Core\Messenger\Messenger as MessengerMessenger;
use MessengerTrait;

class MovieForm extends FormBase {
 
    public function getFormId() {
        return 'movie';
      }
      public function buildForm(array $form, FormStateInterface $form_state){
        
        $form['film_text'] = array(
          '#type' => 'textfield',
          '#title' =>$this->t('Trazi film'),
          '#default_value' => \Drupal::request()->get('film_text')
        );
       
        $form['film_dugme'] = array(
          '#type' => 'submit',
          '#value' =>'perform_finding',
        );        
        return $form;
      }
         
      public function submitForm(array &$form, FormStateInterface $form_state) {   
  }
}
  

