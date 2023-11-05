<?php

namespace common\models;

use Yii;
use yii\base\Model;

class ValidateForm
{
    public static function emailRules(): array{
        return [
            ['email', 'trim' ],// обрезает пробелы вокруг
            ['email', 'email', 'message' => Yii::t('app', 'Не верный формат email')],
            ['email', 'string', 'max' => 255, 'tooLong' => Yii::t('app', 'max 255')],
        ];
    }

    public static function passwordRules(): array{
        return [
                ['password', 'trim' ],// обрезает пробелы вокруг
                [['password'], 'required', 'message' => "{attribute} не может быть пустым!"] ,
                ['password', 'string', 'length' => [6, 100], 'tooShort' => Yii::t('app', "Мин. длинна 6 сим."), 'tooLong' => Yii::t('app', "Макс. длинна 100 сим.")],
                ['password', 'match', 'pattern' =>  '/^(?=.*[a-z])/','message' => Yii::t('app', "Пароль должен содержать латинские буквы")],
                ['password', 'match', 'pattern' =>  '/^(?=.*[0-9])/','message' => Yii::t('app', "Пароль должен содержать цыфры")],
            ];
    }
    public static function passwordRepeatRules(): array{
        return [
                ['password', 'trim' ],// обрезает пробелы вокруг
                ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('app', "Пароли не совпадают")],
                [['password_repeat'], 'required', 'message' => Yii::t('app', "{attribute} не может быть пустым!")] ,
        ];
    }
    public static function loginRules(): array{
        return [
                ['login', 'trim' ],// обрезает пробелы вокруг
                [['login'], 'string', 'length' => [6, 100], 'tooShort' => Yii::t('app', 'Мин. длинна 6 сим.'), 'tooLong' =>Yii::t('app', 'Макс. длинна 100 сим.') ],
                ['login', 'match', 'pattern' =>  '/^[a-z0-9-_@]+$/i','message' => Yii::t('app', "Только латинские буквы и цыфры, а также символы(@_$)")],
                ['login', 'match', 'pattern' =>  '/^(?=.*[a-z])/','message' => Yii::t('app', "Login должен содержать буквы")],
                ['login', 'match', 'pattern' =>  '/^(?=.*[0-9])/','message' => Yii::t('app', "Login должен содержать цыфры")],
            ];
    }
 public static function phoneRules(): array{
        return [
                ['phone', 'trim' ],// обрезает пробелы вокруг
                ['phone', 'filter', 'filter' => function ($value) { return preg_replace('/[^0-9]/', '', $value); }],
            ];
    }

}