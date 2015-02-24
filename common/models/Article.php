<?php

namespace common\models;
use common\models\Section;
use common\libs\SectionNode;
use Yii;
use yii\db\Exception;
/**
 * @property integer $id
 * @property string $content
 * @property integer $toc_mode
 * @property integer $status
 * @property integer $comment_mode
 * @property string $created_by
 * @property integer $updated_at
 */
class Article extends \yii\base\Model
{
    public $id;
    public $content;
    public $toc_mode;
    public $status;
    public $comment_mode;
    public $created_by;
    public $updated_at;
    
    private $_sectionNode;
    private $_plainSections;
    private $_title;
    
    public function __construct($config = array()) {
        $this->content = '';
        $this->toc_mode = Section::TOC_MODE_NORMAL;
        $this->status = Section::STATUS_DRAFT;
        $this->comment_mode = Section::COMMENT_MODE_NORMAL;
        $this->created_by = null;
        $this->updated_at = 0;
        $this->_sectionNode = null;
        $this->_plainSections = [];
        $this->id = null;
        $this->_title = '';
        parent::__construct($config);
    }
    
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['toc_mode', 'status', 'comment_mode'], 'integer'],
            [['toc_mode'], 'in', 'range' => [
                Section::TOC_MODE_NORMAL,
                Section::TOC_MODE_HIDDEN,
            ]],
            [['status'], 'in', 'range' => [
                Section::STATUS_DRAFT,
                Section::STATUS_WAIT,
                Section::STATUS_REVIEW,
                Section::STATUS_DENY,
                Section::STATUS_PUBLISH,
                Section::STATUS_TIMING,
                Section::STATUS_ARCHIVE,
                Section::STATUS_UNPUBLISH,
                Section::STATUS_DELETE,
            ]],
            [['comment_mode'], 'in', 'range' =>[
                Section::COMMENT_MODE_NORMAL,
                Section::COMMENT_MODE_FORBIDDEN,
                Section::COMMENT_MODE_HIDDEN,
            ]],
        ];
    }
    
    public function create() {
        if (!$this->validate()) return false;
        if ($this->_sectionNode === null) {
            $this->_sectionNode = new SectionNode();
            $this->_sectionNode->loadHtml($this->content);
        }
        $saveTransaction = Yii::$app->db->beginTransaction();
        try {
            $insertTransaction = Yii::$app->db->beginTransaction();
            try {
                $res = $this->insert();
                $insertTransaction->commit();
            } catch (Exception $e) {
                $insertTransaction->rollBack();
                throw $e;
            }
            $this->updateInsert($res);
            $saveTransaction->commit();
        } catch (Exception $e) {
            $saveTransaction->rollBack();
            throw $e;
        }
        return true;
    }
    
    protected function insert() {
        $db = Yii::$app->db;
        $tblName = Section::tableName();
        $time = time();
        $userId = Yii::$app->user->getId();
        $totalRows = 0;
        $stack = [];
        array_push($stack, $this->_sectionNode);
        do {
            $node = array_pop($stack);
            $cmd = $db->createCommand()->insert($tblName, [
                'title' => $node->getTitle(),
                'content' => $node->getContent(),
                'toc_mode' => $this->toc_mode,
                'status' => $this->status,
                'comment_mode' => $this->comment_mode,
                'created_at' => $time,
                'updated_at' => $time,
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);
            $totalRows += $cmd->execute();
            $node->id = $totalRows;
            foreach ($node->getChild() as $sub) {
                array_push($stack, $sub);
            }
        } while (!empty($stack));
        $lastId = $db->getLastInsertID();
        $this->id = $lastId - $totalRows + 1;
        return [
            'totalRows' => $totalRows,
            'lastId' => $lastId,
        ];
    }
    
    protected function updateInsert($param) {
        $db = Yii::$app->db;
        $tblName = Section::tableName();
        $beginId = $param['lastId'] - $param['totalRows'] + 1;
        $stack = [];
        array_push($stack, $this->_sectionNode);
        
        do {
            $node = array_pop($stack);
            $cmd = $db->createCommand()->update($tblName, [
                'parent' => ($node->id == 1 ? null : $node->getParent()->id + $beginId - 1),
                'ancestor' => $beginId,
                'prev' => ($node->getPrev()==false ? null : $node->getPrev()->id + $beginId - 1),
                'next' => ($node->getNext()==false ? null : $node->getNext()->id + $beginId - 1),
                    ], 'id=:id', [':id' => $node->id + $beginId - 1]);
            $cmd->execute();
            foreach ($node->getChild() as $sub) {
                array_push($stack, $sub);
            }
        } while (!empty($stack));
    }
    
    /**
     * Get all available comment mode in key-value pairs.
     * @return array
     */
    public static function getAllCommentMode() {
        return Section::getAllTocMode();
    }

    /**
     * Get all available TOC mode in key-value pairs.
     * @return array
     */
    public static function getAllTocMode() {
        return Section::getAllCommentMode();
    }
    
    /**
     * Set sections models for current article.
     * @param Section|array $sections array or instance of Section
     * @return boolean Return true if success, while false on failure.
     */
    public function setSections($sections) {
        if (is_array($sections)) {
            foreach ($sections as $section) {
                $plain = $section->toPlainSection();
                foreach ($sections as $sec) {
                    if ($sec->parent == $plain->id && $sec->prev === null) {
                        $plain->firstChild = $sec->id;
                        break;
                    }
                }
                $this->_plainSections[$section->id] = $plain;
                if ($this->id == null && $section->parent == null) {
                    $this->_title = $section->getTitleText();
                    $this->id = $section->id;
                }
                if ($this->created_by == null) {
                    $this->created_by = $section->getCreatedBy()->one()->username;
                }
                if ($this->updated_at < $section->updated_at) {
                    $this->updated_at = $section->updated_at;
                }
            }
            return true;
        } else if ($sections instanceof Section) {
            $this->id = $sections->id;
            $this->_plainSections[$sections->id] = $sections;
            return true;
        }
        return false;
    }
    
    /**
     * Getter for all plain sections.
     * @return array An array of PlainSection objects.
     */
    public function getSections() {
        return $this->_plainSections;
    }
    
    /**
     * Get an Article model rooted with the given id.
     * @param integer $id Root Section ID.
     * @return Article. null while failure.
     */
    public static function findOne($id) {
        $model = new self();
        $sections = Section::findAll(['ancestor' => $id]);
        if (empty($sections)) return null;
        $model->setSections($sections);
        return $model;
    }
    
    /**
     * Get the title in plain text, also the title of the root section.
     * @return string
     */
    public function getTitle() {
        return $this->_title;
    }
    
    public function attributeLabels()
    {
        return reset($this->_plainSections)->attributeLabels();
    }
}
