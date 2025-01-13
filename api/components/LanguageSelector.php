<?php

namespace api\components;

use api\enums\Language;
use Yii;
use yii\base\Component;

class LanguageSelector extends Component
{
    public function init(): void
    {
        parent::init();
        $language = Yii::$app->request->headers->get('Language', Language::RU->value);
        if (in_array($language, Language::getList())) {
            Yii::$app->language = $language;
        } else {
            Yii::$app->language = Language::RU->value;
        }
    }
}