<?php

namespace app\models\comments;

use yii\base\Model;

class CommForm extends Model
{
    public $comment;

    public function attributeLabels()
    {
        return [
            'comment' => 'Комментарий'
        ];
    }
}