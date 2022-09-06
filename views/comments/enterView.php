<?php
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var $login */
/** @var $action */

?>
<?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'class' => 'login-form'
]) ?>
<?php $template = ['template' => '<div>{input}</div>{error}'] ?>
<?= $form->field($login, 'email',  $template)->input('email', ['class' => 'entry-field email-field validation', 'id' => 'email-field', 'name' => 'emailField', 'placeholder' => 'Электронная почта*'])->label(false) ?>
<?= $form->field($login, 'password', [
    'template' => '<div class="pass">{input}<i id="eye" class="fa-solid fa-eye"></i></div>{error}'
])->passwordInput(['class' => 'entry-field password-field validation', 'id' => 'password-field', 'name' => 'passwordField', 'placeholder' => 'Пароль*'])->label(false) ?>
<?php if($action === 'Авторизация' || $action === ''): ?>
    <?= Html::submitButton("Войти", ['class' => 'entry-btn', 'id' => 'entry-btn']) ?>
<?php elseif($action === 'Регистрация'): ?>
    <div class="reg-fields">
        <?= $form->field($login, 'firstName', $template)->textInput([
            'class' => 'reg-field validation',
            'id' => 'first-name-field',
            'name' => 'firstName',
            'placeholder' => 'Имя'
        ])->label(false) ?>
        <?= $form->field($login, 'secondName', $template)->textInput([
            'class' => 'reg-field validation',
            'id' => 'second-name-field',
            'name' => 'secondName',
            'placeholder' => 'Фамилия'
        ])->label(false) ?>
    </div>
    <?= Html::submitButton("Зарегистрироваться", ['class' => 'reg-btn', 'id' => 'reg-btn']) ?>
<?php endif ?>
<?php ActiveForm::end() ?>