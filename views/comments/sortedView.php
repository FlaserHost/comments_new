<?php
use yii\widgets\LinkPager;

/** @var $comments */
/** @var $users */
/** @var $pages */

$session = \Yii::$app->session;
$count = 0;
?>
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