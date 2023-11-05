<?php

namespace backend\controllers;

use ArrayObject;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;

class LogController extends \yii\web\Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['getLog', 'save'],
                'rules' => [
                    [
                        'actions' => ['getLog', 'save'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],

                ],
            ],
        ];
    }


    public string $message;
    public string $file;
    public int $line;
    public int $code;

    const FILENAME =  __DIR__ . "/../log.txt";


    public function actionIndex(): string
    {
        $errors = self::getLog();
        return $this->render('index', compact('errors'));
    }

    public static function add(?string $text, ?string $file, ?int $line , ?int $code): bool
    {

        $struct = array('time' => date('Y-M-d H:i:s'),
            'file' => $file,
            'line' => $line,
            'code' => $code,
            'ip' => Yii::$app->request->userIP,
            'user_id' => Yii::$app->user->id,
            'text' => $text,); //объявление структуры
        try{
            $ao = LogController::getLog();
            $ao->append($struct);//Добавили в конец запись

            return LogController::save($ao);

        }catch(\Exception $e){
            return false;
        }
    }

    public static function save(ArrayObject $arr): bool{
        try{
            $text = $arr->serialize(); //Преобразовали в строку
            file_put_contents(self::FILENAME, $text); //Записали в файл
            return true;
        }catch(\Exception $e){
            var_dump($e->getMessage());
            return false;
        }

    }


    public static function getLog(): ArrayObject{
        $ao = new ArrayObject();
        if(file_exists(self::FILENAME)){
            $text = file_get_contents(self::FILENAME); //Получили данные из файла
            if(!empty($text)) {//Если файл не пустой
                $ao->unserialize($text); //Преобразовали в массив
            }
        }
        return $ao;
    }

    public function actionClearLog(): \yii\web\Response
    {
       unlink(self::FILENAME);
        return $this->redirect(Url::to("/admin/log/index"));
    }




}