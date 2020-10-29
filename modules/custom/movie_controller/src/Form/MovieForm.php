<?php

namespace Drupal\movie_controller\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;


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
          '#value' =>'Pronadji',
          '#submit' => array('film_dugme_submit'),
        );

        $form['film_broj'] = array(
          '#type' => 'textfield',
          '#title' =>$this->t('Ogranici broj prikaza po stranici'),
          '#default_value' => \Drupal::request()->get('film_broj')
        );
        $form['film_range'] = array(
          '#type' => 'submit',
          '#value' =>'Ogranici',
          '#submit' => array('film_range_submit'),
        );
        
        return $form;
      }
         
      public function submitForm(array &$form, FormStateInterface $form_state) {   
  }
}
  

