<?php

namespace common\models;

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
 * @property-read Comment[] $comments Comments related to the section.
 * @property-read Section[] $descendentSections Sections belongs to the same ancestor.
 * @property-read User $updatedBy User updated the section at last.
 * @property-read User $createdBy Author of the section.
 * @property-read Section $nextSection The next section at the same level.
 * @property-read Section[] $childSections All child sections.
 * @property-read Section $parentSection The parent section.
 * @property-read Section $prevSection The prev section at the same level.
 */
class Section extends \yii\db\ActiveRecord {

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
    public static function tableName() {
        return '{{%section}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['toc_mode', 'status', 'comment_mode', 'ver',], 'integer'],
            [['toc_mode'], 'in', 'range' => [
                    Section::TOC_MODE_NORMAL,
                    Section::TOC_MODE_HIDDEN,
                ]],
            [['status'], 'in', 'range' => [
                    Section::STATUS_DRAFT,
                    Section::STATUS_PUBLISH,
                    Section::STATUS_DELETE,
                ]],
            [['comment_mode'], 'in', 'range' => [
                    Section::COMMENT_MODE_NORMAL,
                    Section::COMMENT_MODE_FORBIDDEN,
                    Section::COMMENT_MODE_HIDDEN,
                ]],
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
    public function attributeLabels() {
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

    /**
     * Get all sections belongs to the same ancestor.
     * @return yii\db\ActiveQueryInterface
     */
    public function getDescendentSections() {
        return $this->hasMany(Sections::className(), ['ancestor' => 'id']);
    }

    /**
     * Get all comments related to the section
     * @return yii\db\ActiveQueryInterface
     */
    public function getComments() {
        return $this->hasMany(Comment::className(), ['section_id' => 'id']);
    }

    /**
     * Get the user who updated the section at last.
     * @return yii\db\ActiveQueryInterface
     */
    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * Get the author.
     * @return yii\db\ActiveQueryInterface
     */
    public function getCreatedBy() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * Get the next section at the same level.
     * @return yii\db\ActiveQueryInterface
     */
    public function getNextSection() {
        return $this->hasOne(Section::className(), ['id' => 'next']);
    }

    /**
     * Get all child sections.
     * @return yii\db\ActiveQueryInterface
     */
    public function getChildSections() {
        return $this->hasMany(Section::className(), ['parent' => 'id']);
    }

    /**
     * Get the first child section.
     * @return yii\db\ActiveQueryInterface
     */
    public function getFirstChildSection() {
        $children = $this->getChildSections();
        $children->andWhere(['prev' => null])->limit(1);
        return $children;
    }

    /**
     * Get the last child section.
     * @return yii\db\ActiveQueryInterface
     */
    public function getLastChildSection() {
        $children = $this->getChildSections();
        $children->andWhere(['next' => null])->limit(1);
        return $children;
    }

    /**
     * Get the parent section.
     * @return Section
     */
    public function getParentSection() {
        return $this->hasOne(Section::className(), ['id' => 'parent']);
    }

    /**
     * Get the prev section at the same level.
     * @return Section
     */
    public function getPrevSection() {
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
            self::STATUS_PUBLISH => Yii::t('common/section', 'Publish'),
            self::STATUS_DELETE => Yii::t('common/section', 'Delete'),
        ];
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

    /**
     * Mark Section on status DELETE.
     * Related section will auto update, cause by the beforeSave() method
     */
    public function markDeleted() {
        $this->status = self::STATUS_DELETE;
        return $this->save(false);
    }

    /**
     * @inheritDoc
     */
    public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        $status = $this->getDirtyAttributes(['status']);
        if (!empty($status) && $status['status'] == self::STATUS_DELETE) {
            if (!$this->updateRelatedDelete()) {
                return false;
            }
        }
        return true;
    }

    /**
     * Make insert, update and delete operations transactional.
     */
    public function transactions() {
        return [
            self::OP_ALL,
        ];
    }

    public function fields() {
        $fields = parent::fields();
        unset($fields['content']);
        return $fields;
    }

    public function reorder($data) {
        $prev = $this->getPrevSection()->one();
        $next = $this->getNextSection()->one();
        if ($prev !== null) {
            $prev->next = $this->next;
            $prev->save(false);
        }
        if ($next !== null) {
            $next->prev = $this->prev;
            $next->save(false);
        }
        if (isset($data['parent'])) {
            $this->parent = empty($data['parent']) ? null : $data['parent'];
        }
        $this->prev = empty($data['prev']) ? null : $data['prev'];
        $newPrev = null;
        if ($this->prev == null) {
            $newPrev = static::findOne(['parent' => $this->parent, 'prev' => null]);
            if ($newPrev !== null) {
                $newPrev->prev = $this->id;
                $newPrev->save(false);
                $this->next = $newPrev->id;
            } else {
                $this->next = null;
            }
        } else {
            $newPrev = static::find()->with('nextSection')
                            ->where(['id' => $this->prev])->one();
            $this->next = $newPrev->next;
            $newNext = $newPrev->getNextSection()->one();
            if ($newNext !== null) {
                $newNext->prev = $this->id;
                $newNext->save(false);
            }
            $newPrev->next = $this->id;
            $newPrev->save(false);
        }
        $this->save(false);
    }
}
