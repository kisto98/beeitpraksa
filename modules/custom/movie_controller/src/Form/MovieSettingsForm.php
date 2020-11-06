<?php

namespace Drupal\movie_controller\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\ConfigFormBase;


class MovieSettingsForm extends ConfigFormBase {
  const SETTINGS = 'movie.settings';
    public function getFormId() {
        return 'movie_admin_settings';
      
      }
      protected function getEditableConfigNames() {
        return [
          static::SETTINGS,
        ];
      }
      public function buildForm(array $form, FormStateInterface $form_state){
        $config = $this->config(static::SETTINGS);
        $form['film_broj'] = array(
          '#type' => 'textfield',
          '#title' =>$this->t('Ogranici broj prikaza po stranici'),
          '#default_value' => $config->get('film_broj'),
        );

        return parent::buildForm($form, $form_state);
      }
         
      public function submitForm(array &$form, FormStateInterface $form_state) {  
        $this->configFactory->getEditable(static::SETTINGS)
      ->set('film_broj', $form_state->getValue('film_broj'))
      ->save();

    parent::submitForm($form, $form_state); 

  }
}
  

