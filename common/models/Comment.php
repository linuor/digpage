<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%comment}}".
 *
 * @property integer $id
 * @property integer $section_id
 * @property integer $parent
 * @property integer $status
 * @property integer $thumbsup
 * @property integer $thumbsdown
 * @property string $content
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property User $updatedBy
 * @property User $createdBy
 * @property Comment $parent0
 * @property Comment[] $comments
 * @property Section $section
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%comment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['section_id'], 'required'],
            [['section_id', 'parent', 'status', 'thumbsup', 'thumbsdown', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['content'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common/comment', 'ID'),
            'section_id' => Yii::t('common/comment', 'Section ID'),
            'parent' => Yii::t('common/comment', 'Parent'),
            'status' => Yii::t('common/comment', 'Status'),
            'thumbsup' => Yii::t('common/comment', 'Thumbsup'),
            'thumbsdown' => Yii::t('common/comment', 'Thumbsdown'),
            'content' => Yii::t('common/comment', 'Content'),
            'created_at' => Yii::t('common/comment', 'Created At'),
            'updated_at' => Yii::t('common/comment', 'Updated At'),
            'created_by' => Yii::t('common/comment', 'Created By'),
            'updated_by' => Yii::t('common/comment', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentComment()
    {
        return $this->hasOne(Comment::className(), ['id' => 'parent']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildComments()
    {
        return $this->hasMany(Comment::className(), ['parent' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSection()
    {
        return $this->hasOne(Section::className(), ['id' => 'section_id']);
    }
}
