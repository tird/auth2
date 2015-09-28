<?php

namespace app\controllers;

use app\models\User;
use app\models\Roles;
use app\models\LoginForm;
use yii\rest\Controller;
use yii\rest\ActiveController;
/**
 * Class UserController
 * @package rest\versions\v1\controllers
 */
class UserController extends ActiveController
{
    public $modelClass = 'app\models\User';
    /**
     * This method implemented to demonstrate the receipt of the token.
     * Do not use it on production systems.
     * @return string AuthKey or model with errors
     */

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
        $model = new LoginForm();

        if ($model->load(\Yii::$app->getRequest()->getBodyParams(), '') && $model->login()) {
            return [
                \Yii::$app->user->identity->getAuthKey(),
                \Yii::$app->user->identity->getId(),
                \Yii::$app->user->identity->getRole()
            ];
        } else {
            return $model;
        }
    }
    public function actionTest()
    {
        $model = new Roles();
        //return $model->findOne('1');
        //return $model->find()->where(['id', 2]);
        //return $model->find()->all();

        /*return $model->find()
            ->where(['=','role_name', 'admin']
            )->all();*/

        $test = $model->find()
               ->where(['=','role_name', 'admin']
               )->all();
        return $test[0]->role_name;
        if($model->role_id){
            return $model;
        } else {
            var_dump($model);
        }

    }
    public function actionTest2()
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
                \Yii::$app->user->identity->getRole(),
                $result[0]->role_name
            ];
        } else {
            return $modelLoginFrom;
        }

    }
    public function actionAdd()
    {
        if ($post = \Yii::$app->getRequest()->getBodyParams()){
            $modelRoles = new Roles();
            $modelRoles->role_name = $post['role_name'];

            $modelRoles->save();  // equivalent to $model->insert();
            return $modelRoles;
        } else {
            throw new \yii\web\HttpException(400, 'No data was posted');
        }
    }
    public function actionAdduser()
    {
        if ($post = \Yii::$app->getRequest()->getBodyParams()){
            //$password = $post['password'];
            $modelUser = new User();
            if ($modelUser->findByUsername($post['username'])){
                return 'found';
            } else{
                return 'not found';
            }
            $modelUser->username = $post['username'];
            //$modelUser->password_hash = $modelUser->setPassword('123');
            $modelUser->setPassword($post['password']);
            var_dump($modelUser->password_hash);
            //return 0;
            $modelUser->email = $post['email'];
            //$modelRoles->role_name = $post['role_name'];
            $modelUser->role_id = 3;
            $modelUser->generateAuthKey();
            $modelUser->created_at = date('Y-m-d H:i:s');

            $modelUser->save();  // equivalent to $model->insert();
            return $modelUser;
        } else {
            throw new \yii\web\HttpException(400, 'No data was posted');
        }
    }
    /*    hasAttribute('role_name'){
            return 0;
        } else {
        return $model;*/






        /*if ($model->load(\Yii::$app->getRequest()->getBodyParams(), '') && $model->login()) {
            return \Yii::$app->user->identity->getAuthKey() . \Yii::$app->user->identity->getId();
        } else {
            return $model;
        }*/

}
