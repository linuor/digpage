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
 *
 * @property Comment[] $comments
 * @property User $updatedBy
 * @property User $createdBy
 * @property Section $next0
 * @property Section[] $sections
 * @property Section $parent0
 * @property Section $prev0
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
            [['parent', 'next', 'prev', 'toc_mode', 'status', 'comment_mode', 'comment_num', 'ver', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
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
            'id' => Yii::t('common/section', 'ID'),
            'title' => Yii::t('common/section', 'Title'),
            'parent' => Yii::t('common/section', 'Parent'),
            'next' => Yii::t('common/section', 'Next'),
            'prev' => Yii::t('common/section', 'Prev'),
            'toc_mode' => Yii::t('common/section', 'Toc Mode'),
            'status' => Yii::t('common/section', 'Status'),
            'comment_mode' => Yii::t('common/section', 'Comment Mode'),
            'comment_num' => Yii::t('common/section', 'Comment Num'),
            'content' => Yii::t('common/section', 'Content'),
            'ver' => Yii::t('common/section', 'Ver'),
            'created_at' => Yii::t('common/section', 'Created At'),
            'updated_at' => Yii::t('common/section', 'Updated At'),
            'created_by' => Yii::t('common/section', 'Created By'),
            'updated_by' => Yii::t('common/section', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['section_id' => 'id']);
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
    public function getNextSection()
    {
        return $this->hasOne(Section::className(), ['id' => 'next']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildSections()
    {
        return $this->hasMany(Section::className(), ['parent' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentSection()
    {
        return $this->hasOne(Section::className(), ['id' => 'parent']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrevSection()
    {
        return $this->hasOne(Section::className(), ['id' => 'prev']);
    }
}
