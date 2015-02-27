<?php

namespace common\models;

use common\libs\PlainSection;
use Yii;

/**
 * This is the model class for table "{{%section}}".
 *
 * @property integer $id
 * @property string $title
 * @property integer $parent
 * @property integer $ancestor
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
    const STATUS_DRAFT = 0;
    const STATUS_PENDING = 20;
    const STATUS_REVIEW = 30;
    const STATUS_DENY = 40;
    const STATUS_PUBLISH = 50;
    const STATUS_TIMING = 60;
    const STATUS_ARCHIVE = 70;
    const STATUS_PRIVATE = 80;
    const STATUS_DELETE = 90;
    const COMMENT_MODE_NORMAL = 0;
    const COMMENT_MODE_FORBIDDEN = 20;
    const COMMENT_MODE_HIDDEN = 30;
    const TOC_MODE_NORMAL = 0;
    const TOC_MODE_HIDDEN = 20;

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
            [['parent', 'ancestor', 'next', 'prev', 'toc_mode', 'status', 'comment_mode', 'comment_num', 'ver', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 255]
        ];
    }
    
    /**
     * Make Section support optimistic lock, with the field of ver.
     */
    public function optimisticLock() {
        return 'ver';
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
            'ancestor' => Yii::t('common/section', 'Ancestor'),
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

    public function getDescendentSections()
    {
        return $this->hasMany(Sections::className(), ['ancestor' => 'id']);
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
    
    /**
     * Get all available comment mode in key-value pairs.
     * @return array
     */
    public static function getAllCommentMode() {
        return [
            self::COMMENT_MODE_NORMAL => Yii::t('common/section', 'Normal'),
            self::COMMENT_MODE_FORBIDDEN => Yii::t('common/section', 'Forbidden'),
            self::COMMENT_MODE_HIDDEN => Yii::t('common/section', 'Hidden'),
        ];
    }

    /**
     * Get all available TOC mode in key-value pairs.
     * @return array
     */
    public static function getAllTocMode() {
        return [
            self::TOC_MODE_NORMAL => Yii::t('common/section', 'Normal'),
            self::TOC_MODE_HIDDEN => Yii::t('common/section', 'Hidden'),
        ];
    }
    /**
     * Get all available status in key-value pairs.
     * @return array
     */
    public static function getAllStatus() {
        return [
            self::STATUS_DRAFT => Yii::t('common/section', 'Draft'),
            self::STATUS_PENDING => Yii::t('common/section', 'Pending'),
            self::STATUS_REVIEW => Yii::t('common/section', 'Review'),
            self::STATUS_DENY => Yii::t('common/section', 'Deny'),
            self::STATUS_PUBLISH => Yii::t('common/section', 'Publish'),
            self::STATUS_TIMING => Yii::t('common/section', 'Timing'),
            self::STATUS_ARCHIVE => Yii::t('common/section', 'Archive'),
            self::STATUS_PRIVATE => Yii::t('common/section', 'Private'),
            self::STATUS_DELETE => Yii::t('common/section', 'Delete'),
        ];
    }
    
    public function getTitleText() {
        return preg_replace('/<[^>]*>/', '', $this->title);
    }
    
    public function getTocModeText() {
        return static::getAllCommentMode()[$this->toc_mode];
    }
    
    public function getCommentModeText() {
        return static::getAllCommentMode()[$this->comment_mode];
    }
    
    public function getStatusText() {
        return static::getAllStatus()[$this->status];
    }
    
    public function toPlainSection() {
        return new PlainSection($this);
    }
    
    /**
     * Mark Section on status DELETE.
     * Related section will auto update, cause by the beforeSave() method
     */
    public function markDeleted() {
        $this->status = self::STATUS_DELETE;
        $this->save(false);
    }
    
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $fields = $this->getDirtyAttributes();
            if (!empty($fields) && !empty($fields['status'])
                    &&$fields['status'] == self::STATUS_DELETE) {
                $next = $this->getNextSection()->one();
                if ($next !== null) {
                    $next->prev = $this->prev;
                    $next->save(false);
                }
                $prev = $this->getPrevSection()->one();
                if ($prev !== null) {
                    $prev->next = $this->next;
                    $prev->save(false);
                };
            }
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Make insert, update and delete operations transactional.
     */
    public function transactions() {
        return [
            self::OP_ALL,
        ];
    }
}
