<?php
namespace app\controllers;

use yii\rest\ActiveController;
use app\models\User;
use app\models\LoginForm;
use app\models\UserRoleView;
use app\models\Role;

class UserController extends ActiveController
{
    public $modelClass = 'app\models\User';

    public function actionLogin()
    {
        $modelLoginFrom = new LoginForm();
        if ($modelLoginFrom->load(\Yii::$app->getRequest()->getBodyParams(), '') && $modelLoginFrom->login()) {

            $modelRoleView = new UserRoleView();

            $result = $modelRoleView->find()
                ->where(['=','user_id', \Yii::$app->user->identity->getId()]
                )->all();

            $roles_table = new Role();
            foreach($result as $key){
                $role_name = $roles_table->find()
                    ->where(['=','role_id', $key->role_id]
                    )->one();
                $user_role[] = $role_name->name;
            }
            return [
                \Yii::$app->user->identity->getAuthKey(),
                $user_role
            ];
        } else {
            return $modelLoginFrom;
        }
    }
    public function actionRestorepass(){
        if (!$post = \Yii::$app->getRequest()->getBodyParams()) {
            throw new \yii\web\HttpException(400, 'No data was posted');
        }
        $model = User::findByUsername($post['username']);
        if (!$model->username){
            throw new \yii\web\HttpException(400, 'Username is incorrect');
        }
        $model->generatePasswordResetToken();
        $model->save();
        $url = 'http://web/rest.php/users/changepass?u=' . $model->username . '&p=' . $model->password_reset_token;
        \Yii::$app->mailer->compose()
            ->setFrom('localhost@domain.com')
            ->setTo('ZhenyaSt@yandex.ru')
            ->setSubject('Restore Password')
            ->setTextBody('')
            ->setHtmlBody("<b>$url</b>")
            ->send();
    }
    public function actionChangepass(){
        if (!$get = \Yii::$app->getRequest()->queryParams) {
            throw new \yii\web\HttpException(400, 'No data was posted');
        }
        $model = User::findByUsername($get['u']);
        $result = $model->find()
            ->where(['=','password_reset_token', $get['p']]
            )->one();
        if (!$result){
            throw new \yii\web\HttpException(400, 'Password reset token is not valid');
        }
        //create new password
    }
}
