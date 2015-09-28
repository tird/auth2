<?php
namespace app\controllers;

use yii\rest\ActiveController;

class RolesController extends ActiveController
{
    public $modelClass = 'app\models\Roles';

    public function actionTest(){
        //return 0;
        var_dump(new $this->modelClass);
    }
}