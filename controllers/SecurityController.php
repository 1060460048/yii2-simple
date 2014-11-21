<?php

namespace app\controllers;

use Yii;
use dektrium\user\controllers\SecurityController as BaseSecurityController;
use azraf\simple\extendible\SimpleController as Simple;

class SecurityController extends BaseSecurityController
{
    public function actionCreate()
    {
        // do your magic
    }
    
    /**
     * Displays the login page.
     *
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
//        echo '<h1>Test Here</h1>';
        $model = $this->module->manager->createLoginForm();

        if ($model->load(\Yii::$app->getRequest()->post()) && $model->login()) {
//            $session = Yii::$app->session;
//            $session->set('language', 'bn-BD');
            Yii::$app->view->params['user'] = [
                                                'loggedIn'=>true,
                                                Yii::$app->wtsecure->sessionUserInfoTag => [
                                                    'id'=>Yii::$app->user->identity->id,
                                                    'username'=>Yii::$app->user->identity->username,
                                                ],
                                                Yii::$app->wtsecure->sessionRoleTag => Yii::$app->wtsecure->encryptRoles($roles=[],$userInfo=[])
                                            ];
            return $this->goBack();
        }

        return $this->render('login', [
            'model' => $model
        ]);
    }
    
    /**
     * Logs the user out and then redirects to the homepage.
     *
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Simple::setNull();
        \Yii::$app->getUser()->logout();

        return $this->goHome();
    }
}