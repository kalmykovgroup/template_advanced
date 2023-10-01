<?php

use common\models\User;
use yii\db\Migration;

/**
 * Class m230929_092144_AddUser
 */
class m230929_092144_AddUser extends Migration
{
    /**
     * {@inheritdoc}
     * @throws Exception
     */
    public function safeUp()
    {
        $connection = Yii::$app->db;

        $query = new \yii\db\Query();

        foreach([
                    'admin'=> '333333',
                    'manager'=> '222222',
                    'tester'=> '111111',
                ] as $login => $password){

            $transaction = $connection->beginTransaction();
            $password_hash = Yii::$app->security->generatePasswordHash($password);
            $auth_key = Yii::$app->security->generateRandomString(32);
            $access_token = Yii::$app->security->generateRandomString(32);

            try {
                $connection->createCommand()->insert('user',
                    ['login'=>$login,
                        'password_hash'=>$password_hash,
                        'auth_key'=>$auth_key,
                        'access_token'=>$access_token,
                    ])->execute();
                $connection->createCommand()->insert('full_info', ['user_id'=>($query->select('id')->from('user')->where(['login'=>$login]))])->execute();
                $transaction->commit();
            } catch (\Exception|\Throwable $e) {
                $transaction->rollBack();
            }
        }

        $auth = Yii::$app->authManager;
        $auth->assign($auth->getRole('admin'), User::findOne(['login' => 'admin'])->id);
        $auth->assign($auth->getRole('manager'), User::findOne(['login' => 'manager'])->id);
        $auth->assign($auth->getRole('user'), User::findOne(['login' => 'tester'])->id);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230929_092144_AddUser cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230929_092144_AddUser cannot be reverted.\n";

        return false;
    }
    */
}
