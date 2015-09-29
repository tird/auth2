<?php

namespace app\controllers;
use app\models\User;
use app\models\Roles;
use app\models\LoginForm;
use yii\rest\ActiveController;

class UserController extends ActiveController
{
    public $modelClass = 'app\models\User';
    public function actions()
    {
        return [
            'index' => [
                'class' => 'yii\rest\IndexAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'view' => [
                'class' => 'yii\rest\ViewAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
        ];
    }
    public function actionLogin()
    {
        $modelLoginFrom = new LoginForm();
        if ($modelLoginFrom->load(\Yii::$app->getRequest()->getBodyParams(), '') && $modelLoginFrom->login()) {
            $modelRoles = new Roles();
            $result = $modelRoles->find()
                ->where(['=','role_id', \Yii::$app->user->identity->getRole()]
                )->all();
            return [
                \Yii::$app->user->identity->getAuthKey(),
                \Yii::$app->user->identity->getId(),
                $result[0]->role_name
            ];
        } else {
            return $modelLoginFrom;
        }
    }
    public function actionAdduser()
    {
        if (!$post = \Yii::$app->getRequest()->getBodyParams()) {
            throw new \yii\web\HttpException(400, 'No data was posted');
        } elseif (\Yii::$app->getRequest()->getReferrer() != 'http://localhost/basic-yii22/web/'){
                throw new \yii\web\HttpException(400, '');
            }
            $model = new User();
            if ($model->findByUsername($post['username'])){
                throw new \yii\web\HttpException(400, 'Username is already exist');
            }
            $model->username = $post['username'];
            $model->setPassword($post['password']);
            $model->email = $post['email'];
            $model->generateAuthKey();
            $model->save();
            return $model;
    }
}
