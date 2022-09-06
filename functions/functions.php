<?php
use app\models\comments\Users;
use yii\base\ErrorException;

function emptyCheck($data)
{
    $notEmptyFlag = false;
    try
    {
        foreach($data as $key => $validate)
        {
            if(!empty($validate[$key]))
            {
                $notEmptyFlag = true;
            }
            else
            {
                $notEmptyFlag = false;
                break;
            }
        }

        return $notEmptyFlag;
    }
    catch(\ErrorException $err)
    {
        \Yii::warning($err);
    }

    return 0;
}

function userExist($userEmail)
{
    $checkResult = false;

    try
    {
        $check = Users::find()->where("email = :email", [":email" => $userEmail])->one();

        if($check)
        {
            $checkResult = true;
        }

        return $checkResult;
    }
    catch(\ErrorException $err)
    {
        \Yii::warning($err);
    }

    return 0;
}
?>