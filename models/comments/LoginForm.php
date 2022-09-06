<?php

namespace app\models\comments;

use yii\base\Model;

class LoginForm extends Model
{
    public $firstName;
    public $secondName;
    public $email;
    public $password;

    public function rules()
    {
        return [
            [['firstName', 'secondName', 'email', 'password'], 'required', 'skipOnEmpty' => false],
            [['firstName', 'secondName', 'email'], 'trim'],
            ['password', 'string', 'length' => [6, 20]],
            ['email', 'email']
        ];
    }

    public function attributeLabels()
    {
        return [
            'firstName' => 'Имя',
            'secondName' => 'Фамилия',
            'email' => 'Электронная почта',
            'password' => 'Пароль'
        ];
    }
}