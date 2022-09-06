<?php
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\widgets\LinkPager;

/** @var $comments */
/** @var $users */
/** @var $commentSend */
/** @var $pages */
/** @var $emptyField */

$session = \Yii::$app->session;
$count = 0;
?>
<div class="main-container">
    <header class="header">
        <?php if($session->has("currentUser")): ?>
            <div class="user-name-block">
                <span class="current-user-Auth">Вы вошли как: &nbsp;<?= $session->get("currentUser")["userName"] ?></span>
            </div>
        <?php endif ?>
        <div class="lk adaptive">
            <div class="middle">
                <?php if(!$session->has("currentUser")): ?>
                    <i class="lk-sign fa-solid fa-user"></i>
                    <span class="enter current-action-span">личный кабинет</span>
                <?php else: ?>
                    <i class="lk-sign fa-solid fa-door-open"></i>
                    <span class="exit current-action-span">выйти</span>
                    <input type="hidden" id="exit">
                <?php endif ?>
            </div>
        </div>
    </header>
    <main class="content">
        <div class="comments">
            <?php if(!$emptyField): ?>
                <div class="sort">
                    <div>
                        <span>Сортировка:</span>
                        <a href="#" class="sort-link" data-parametr="creation_date" data-sort-type="DESC">по дате <i class="fa-solid fa-arrow-up-long"></i></a>&nbsp;
                        <a href="#" class="sort-link" data-parametr="user_id" data-sort-type="DESC">по пользователям <i class="fa-solid fa-arrow-up-long"></i></a>
                    </div>
                </div>
                <?php foreach($comments as $comment): ?>
                    <?php $count++ ?>
                    <div id="comment_<?= $comment->comment_id ?>" class="comment comment-number-<?= $count ?>" data-comment-namespace="<?= $comment->comment_id ?>" data-comment-unamespace="<?= $comment->user_id ?>">
                        <div class="comment-info">
                            <div class="avatar">
                                <figure>
                                    <i class="fa-solid fa-user"></i>
                                </figure>
                            </div>
                            <div class="full-name">
                                <?php
                                foreach($users as $user)
                                {
                                    if($comment->user_id === $user->user_id)
                                    {
                                        $getFullName = $user->full_name;
                                    }
                                }
                                ?>
                                <span title="Имя пользователя"><?= $getFullName ?></span>
                            </div>
                            <div class="comment-date">
                                <time title="Дата и время добавления комментария"><?= $comment->creation_date ?></time>
                            </div>
                        </div>
                        <div class="comment-body">
                            <p><?= $comment->comment_body ?></p>
                        </div>
                        <?php if($session->has("currentUser") && $session->get("currentUser")["status"] === true): ?>
                            <?php if($comment->user_id === $session->get("currentUser")["user_id"]): ?>
                                <div class="control-panel">
                                    <div class="edit-comment">
                                        <i data-comment-namespace="<?= $comment->comment_id ?>" data-comment-action="Редактирование" class="fa-solid fa-pencil"></i>
                                    </div>
                                    <div class="delete-comment">
                                        <i data-comment-namespace="<?= $comment->comment_id ?>" data-comment-action="Удаление" class="fa-solid fa-trash"></i>
                                    </div>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                <?php endforeach ?>
                <?= LinkPager::widget(['pagination' => $pages]); ?>
            <?php elseif($emptyField): ?>
                <div class='empty-field'>
                    <p>На данный момент, нет ни одного комментария.<br>
                        Оставьте его первым
                    </p>
                </div>
            <?php endif ?>
            <div class="comment-input">
                <?php $form = ActiveForm::begin([
                    'id' => 'comment-form'
                ]) ?>
                <?php
                if($session->has("currentUser") && $session->get("currentUser")["status"] === true)
                {
                    $placeholder = '';
                    $disabled = 'value';
                }
                else
                {
                    $placeholder = 'Авторизируйтесь, чтобы оставить комментарий';
                    $disabled = 'disabled';
                }
                ?>
                <?= $form->field($commentSend, 'comment')->textarea([
                    'class' => 'comment-box',
                    'id' => 'comment-box',
                    'name' => 'comment',
                    'placeholder' => $placeholder,
                    $disabled => '']) ?>
                <?php if($session->has("currentUser") && $session->get("currentUser")["status"] === true): ?>
                <?php endif ?>
                <div class="btn-block">
                    <?= Html::submitButton("Отправить", ['class' => 'send-btn', 'id' => 'send-btn', $disabled => '']) ?>
                </div>
                <?php ActiveForm::end() ?>
            </div>
        </div>
    </main>
</div>
<?php
// Отключение стандартного тулбара от Yii2
if(class_exists('yii\debug\Module'))
{
    $this->off(\yii\web\View::EVENT_END_BODY, [\yii\debug\Module::getInstance(), 'renderToolbar']);
}
?>