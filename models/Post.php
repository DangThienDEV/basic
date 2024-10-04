<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Post extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%post}}'; // Tên bảng
    }

    public function rules()
    {
        return [
            [['title', 'created_at'], 'required'],
            [['content'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }
}
