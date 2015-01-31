<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%section}}".
 *
 * @property integer $id
 * @property string $title
 * @property integer $parent
 * @property integer $next
 * @property integer $prev
 * @property integer $child_num
 * @property integer $toc_mode
 * @property integer $status
 * @property integer $comment_mode
 * @property integer $comment_num
 * @property string $content
 * @property integer $ver
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class Section extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%section}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent', 'next', 'prev', 'child_num', 'toc_mode', 'status', 'comment_mode', 'comment_num', 'ver', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'title' => Yii::t('common', 'Title'),
            'parent' => Yii::t('common', 'Parent'),
            'next' => Yii::t('common', 'Next'),
            'prev' => Yii::t('common', 'Prev'),
            'child_num' => Yii::t('common', 'Child Num'),
            'toc_mode' => Yii::t('common', 'Toc Mode'),
            'status' => Yii::t('common', 'Status'),
            'comment_mode' => Yii::t('common', 'Comment Mode'),
            'comment_num' => Yii::t('common', 'Comment Num'),
            'content' => Yii::t('common', 'Content'),
            'ver' => Yii::t('common', 'Ver'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'created_by' => Yii::t('common', 'Created By'),
            'updated_by' => Yii::t('common', 'Updated By'),
        ];
    }
}
