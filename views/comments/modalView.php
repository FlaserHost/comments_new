<?php
/** @var $login */
/** @var $action */
?>
<div class="modal-area">
    <div class="modal">
        <i id="xmark" class="fa-solid fa-xmark"></i>
        <div class="modal-content">
            <div>
                <a href="#" data-modal-action="Авторизация" class="tab active-tab">Войти</a>
                <a href="#" data-modal-action="Регистрация" class="tab">Зарегистрироваться</a>
            </div>
            <div class="form">
                <?= $this->render("enterView", compact("login", "action")) ?>
            </div>
        </div>
    </div>
</div>