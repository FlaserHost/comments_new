<?php
namespace app\controllers;
require_once '../functions/functions.php';

use app\models\comments\Comments;
use app\models\comments\CommForm;
use app\models\comments\LoginForm;
use app\models\comments\Users;
use yii\base\ErrorException;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;

class CommentsController extends Controller
{
    public function actionIndex()
    {
        $this->layout = '/commentsLayout';
        $this->view->title = 'Комментарии';
        $this->view->registerMetaTag(["name" => "description", "content" => "comments"], "description");

        $comments = '';
        $pages = '';
        $emptyField = false;

        try
        {
            $items = Comments::find();
            $users = Users::find()->all();
            $commentSend = new CommForm();

            if($items->count() > 0)
            {
                $pages = new Pagination(['totalCount' => $items->count(), 'pageSize' => 3]);
                $comments = $items->offset($pages->offset)->limit($pages->limit)->all();
            }
            else
            {
                $emptyField = true;
            }

            return $this->render("/comments/mainView", compact("comments", "pages", "users", "commentSend", "emptyField"));
        }
        catch(\ErrorException $err)
        {
            \Yii::warning($err);
        }

        return 0;
    }

    public function actionShowModal()
    {
        $request = \Yii::$app->request;

        try
        {
            $login = new LoginForm();
            $action = '';

            if($request->isAjax)
            {
                if($request->post("modalAction"))
                {
                    $action = htmlspecialchars($request->post("modalAction"));
                }

                if($action === '')
                {
                    return $this->renderAjax("/comments/modalView", compact("login", "action"));
                }
                else if($action !== '')
                {
                    return $this->renderAjax("/comments/enterView", compact("login", "action"));
                }
            }
        }
        catch(\ErrorException $err)
        {
            \Yii::warning($err);
        }

        return 0;
    }

    public function actionAuth()
    {
        $session = \Yii::$app->session;
        $request = \Yii::$app->request;
        $model = new LoginForm();

        try
        {
            if($request->isAjax && $request->post("loginData"))
            {
                $model->load($request->post());

                $transform = array(
                    0 => htmlspecialchars($request->post("loginData")[1]["value"]),
                    1 => htmlspecialchars($request->post("loginData")[2]["value"])
                );

                if(emptyCheck($transform) === true && ActiveForm::validate($model))
                {
                    $email = htmlspecialchars($request->post("loginData")[1]["value"]);
                    $password = htmlspecialchars($request->post("loginData")[2]["value"]);
                    $password = md5($password);

                    $users = Users::find()->where("email = :email AND password = :password", [":email" => $email, ":password" => $password])->one();

                    if($users)
                    {
                        $userIdent = array(
                            'status' => true,
                            'user_id' => $users->user_id,
                            'userName' => $users->full_name
                        );

                        $session->set("currentUser", $userIdent);
                        echo json_encode($userIdent);
                    }
                    die;
                }
            }
        }
        catch(\ErrorException $err)
        {
            \Yii::warning($err);
        }
    }

    public function actionLogout()
    {
        \Yii::$app->session->remove("currentUser");
    }

    public function actionSort()
    {
        $request = \Yii::$app->request;

        try
        {
            if($request->isAjax && $request->post("sortData"))
            {
                $orderBy = htmlspecialchars($request->post("sortData")["parametr"]);
                $sortType = constant('SORT_' . htmlspecialchars($request->post("sortData")["sortType"]));

                $items = Comments::find();
                $users = Users::find()->all();

                $pages = new Pagination(['totalCount' => $items->count(), 'pageSize' => 3]);
                $comments = $items->offset($pages->offset)->limit($pages->limit)->orderBy([$orderBy => $sortType])->all();

                return $this->renderAjax("/comments/sortedView", compact("comments", "pages", "users"));
            }
        }
        catch(\ErrorException $err)
        {
            \Yii::warning($err);
        }

        return 0;
    }

    public function actionCommentAdd()
    {
        $request = \Yii::$app->request;

        try
        {
            if($request->isAjax && $request->post("postData"))
            {
                $user_id = \Yii::$app->session->get("currentUser")["user_id"];
                $commentBody = htmlspecialchars($request->post("postData")[1]["value"]);
                $commentDate = date("Y-m-d H:i:s");

                $comments = new Comments();
                $comments->user_id = $user_id;
                $comments->comment_body = $commentBody;
                $comments->creation_date = $commentDate;
                $comments->save();

                $newMaxID = Comments::find()->max('comment_id');
                $fullCommentsCount = Comments::find()->count();
                $userName = Users::find()->where("user_id = :user_id", [":user_id" => $user_id])->one();

                $newComment = array(
                    'user_id' => $user_id,
                    'userName' => $userName->full_name,
                    'commentID' => $newMaxID,
                    'commentBody' => $commentBody,
                    'commentDate' => $commentDate,
                    'commentsCount' => $fullCommentsCount
                );

                echo json_encode($newComment);
                die;
            }
        }
        catch(\ErrorException $err)
        {
            \Yii::warning($err);
        }
    }

    public function actionDelEdit()
    {
        $request = \Yii::$app->request;

        try
        {
            if($request->isAjax && $request->post("actionData"))
            {
                $commentBody = '';
                $action = htmlspecialchars($request->post("actionData")["commentAction"]);
                $commentID = htmlspecialchars($request->post("actionData")["commentNamespace"]);

                if(isset($_POST["actionData"]["commentBody"]))
                {
                    $commentBody = htmlspecialchars($request->post("actionData")["commentBody"]);
                }

                if($action === 'Редактирование')
                {
                    $editDate = date("Y-m-d H:i:s");
                    $comments = Comments::find()->where("comment_id = :comment_id", [":comment_id" => $commentID])->one();
                    $comments->comment_body = $commentBody;
                    $comments->creation_date = $editDate;
                    $comments->update();

                    $actionResult = array(
                        'commentID' => $commentID,
                        'commentBody' => $commentBody,
                        'editDate' => $editDate
                    );

                    echo json_encode($actionResult);
                }
                else if($action === 'Удаление')
                {
                    $comments = Comments::find()->where("comment_id = :comment_id", [":comment_id" => $commentID])->one();
                    $comments->delete();

                    echo json_encode($commentID);
                }

                die;
            }
        }
        catch(\ErrorException $err)
        {
            \Yii::warning($err);
        }
    }

    public function actionUserRegistration()
    {
        $request = \Yii::$app->request;

        $model = new LoginForm();
        $model->load($request->post());

        try
        {
            $notEmptyFlag = false;
            if($request->isAjax && $request->post("registrationData"))
            {
                $transform = array(
                    0 => htmlspecialchars($request->post("registrationData")[1]["value"]),
                    1 => htmlspecialchars($request->post("registrationData")[2]["value"]),
                    2 => htmlspecialchars($request->post("registrationData")[3]["value"]),
                    3 => htmlspecialchars($request->post("registrationData")[4]["value"])
                );

                if(emptyCheck($transform) === true && ActiveForm::validate($model))
                {
                    $email = htmlspecialchars($request->post("registrationData")[1]["value"]);

                    if(!$model->validate($email))
                    {
                        return false;
                    }

                    if(userExist($email) === false)
                    {
                        $password = htmlspecialchars($request->post("registrationData")[2]["value"]);
                        $password = md5($password);
                        $firstName = htmlspecialchars($request->post("registrationData")[3]["value"]);
                        $secondName = htmlspecialchars($request->post("registrationData")[4]["value"]);

                        $users = new Users();
                        $users->full_name = $firstName . ' ' . $secondName;
                        $users->email = $email;
                        $users->password = $password;
                        $users->save();

                        echo 'Регистрация прошла успешно. Выполните вход.';
                    }
                    else
                    {
                        echo 'Пользователь с таким email адресом уже зарегистрирован.';
                    }
                }
                else
                {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    if(!$model->validate())
                    {
                        $validation = array(
                            'serverValidation' => ActiveForm::validate($model),
                            'message' => 'Заполните форму регистрации.'
                        );

                        return $validation;
                    }
                }
                die;
            }
        }
        catch(\ErrorException $err)
        {
            \Yii::warning($err);
        }

        return 0;
    }
}