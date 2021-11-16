<?php

require_once 'Form.class.php';

$form = Form::Begin([
    'action' => 'submit.php',
    'method' => 'post'
]);

echo $form->input([
    'type' => 'text',
    'name' => 'aaa'
]);

echo $form->password([
    'name' => 'aaaa'
]);

echo $form->submit([
    'type' => 'submit',
    'value' => 'Отправить форму'
]);

Form::end();
