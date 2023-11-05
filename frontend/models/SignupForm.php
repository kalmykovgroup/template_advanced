<?php

namespace frontend\models;

use common\models\FullInfo;
use common\models\ValidateForm;
use Yii;
use yii\base\Model;
use common\models\User;
use yii\db\Exception;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public string $email = "";
    public string $phone = "";
    public string $password = "";
    public bool $consent_to_data_processing = true;
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {

        return array_merge(
            ValidateForm::emailRules(),
            ValidateForm::phoneRules(),
            ValidateForm::passwordRules(),
            [
                ['consent_to_data_processing', 'boolean'],
                ['consent_to_data_processing',  function(){
                    if(!$this->consent_to_data_processing){
                        $this->addError('consent_to_data_processing', Yii::t('app', Yii::t('app',"Нужно дать согласие, иначе продолжить не возможно")));
                    }
                }],

                [['email', 'phone'], 'required' , 'message' => Yii::t('app', "{attribute} не может быть пустым!"), 'when' => function(){
                    return empty($this->phone) && empty($this->email);

                }, 'whenClient' => "function (attribute, value) {
                    return $('#loginform-phone').val() == '' && $('#loginform-email').val() == '' ;
                }"],

                ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => Yii::t('app', 'Этот адрес электронной почты уже занят.')],
                ['phone', 'unique', 'targetClass' => '\common\models\User', 'message' => Yii::t('app', 'Телефон уже занят.')],

            ]);

    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     * @throws Exception
     * @throws \yii\base\Exception
     */
    public function signup(): bool
    {

       if (!$this->validate()) return false;

        $user = new User();
        $user->email = $this->email;
        $user->phone = $this->phone;
        $user->setPassword($this->password);

        $transaction = $user->getDb()->beginTransaction();
        try {
            if ($user->save()) {
                $fullInfo = new FullInfo(['user_id' => $user->id,]);
                if($fullInfo->save()) {
                    $transaction->commit();
                     Yii::$app->user->login($user);
                    return true;
                }
            }

        } catch(\Exception $e) {
            Yii::error($e);
            $transaction->rollBack();
            throw $e;
        } catch(\Throwable $e) {
            Yii::error($e);
            $transaction->rollBack();
        }

        return false;
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
