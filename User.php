<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\filters\RateLimitInterface;

class User extends ActiveRecord implements IdentityInterface , RateLimitInterface
{
    public static function tableName()
    {
        return '{{%user}}';
    }
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            [['role_id'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['email'], 'email']
        ];
    }
    public function fields(){
        return [
            'id',
            'username',
            'email'
        ];
    }
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }
    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token]);
    }
    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }
    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }
        return static::findOne([
            'password_reset_token' => $token,
        ]);
    }
    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }
    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }
    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }
    public function getRole()
    {
        return $this->role_id;
    }
    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }
    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    /**
     * @inheritdoc
     */
    public function getRateLimit($request, $action)
    {
        if (($request->isPut || $request->isDelete || $request->isPost)) {
            return [Yii::$app->params['maxRateLimit'], Yii::$app->params['perRateLimit']];
        }

        return [Yii::$app->params['maxGetRateLimit'], Yii::$app->params['perGetRateLimit']];
    }
    /**
     * @inheritdoc
     */
    public function loadAllowance($request, $action)
    {
        return [
            \Yii::$app->cache->get($request->getPathInfo() . $request->getMethod() . '_remaining'),
            \Yii::$app->cache->get($request->getPathInfo() . $request->getMethod() . '_ts')
        ];
    }
    /**
     * @inheritdoc
     */
    public function saveAllowance($request, $action, $allowance, $timestamp)
    {
        \Yii::$app->cache->set($request->getPathInfo() . $request->getMethod() . '_remaining', $allowance);
        \Yii::$app->cache->set($request->getPathInfo() . $request->getMethod() . '_ts', $timestamp);
    }
}