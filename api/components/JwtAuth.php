<?php

namespace api\components;

use Yii;
use yii\base\ActionFilter;
use yii\web\UnauthorizedHttpException;

class JwtAuth extends ActionFilter
{
    public function beforeAction($action): bool
    {
        $authHeader = Yii::$app->request->headers->get('Authorization');

        if (!$authHeader || !preg_match('/^jwt\s+(.*)$/i', $authHeader, $matches)) {
            throw new UnauthorizedHttpException('Missing or invalid Authorization header.');
        }

        $token = $matches[1];

        try {
            $decoded = Yii::$app->jwt->validateToken($token);
            Yii::$app->user->loginByAccessToken($token);
        } catch (\Exception $e) {
            throw new UnauthorizedHttpException('Invalid token: ' . $e->getMessage());
        }

        return parent::beforeAction($action);
    }
}