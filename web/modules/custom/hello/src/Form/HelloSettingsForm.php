<?php

namespace Drupal\hello\Form;

use Drupal\Component\Utility\Html;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class HelloSettingsForm extends ConfigFormBase {

  public function getFormId() {
    return 'hello_settings';
  }

  public function getEditableConfigNames() {
    return ['hello.settings'];
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['login_message'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Login message'),
      '#description' => $this->t('You can use the @username token.'),
      '#required' => TRUE,
      '#default_value' => $this->config('hello.settings')->get('login_message'),
    ];
    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $safe_value = Html::escape($form_state->getValue('login_message'));
    $this->config('hello.settings')
      ->set('login_message', $safe_value)
      ->save();
  }

}
