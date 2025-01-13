<?php

namespace api\interface\services;

use common\models\LoginForm;

interface AuthServiceInterface
{
    public function auth(LoginForm $form);

}