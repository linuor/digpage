<?php

namespace common\models;
use common\models\Section;
use common\libs\SectionNode;
use yii\db\Query;
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
    public $content;
    public $toc_mode;
    public $status;
    public $comment_mode;
    
    private $_id;
    private $_title;
    private $_created_by;
    private $_updated_at;
    private $_sectionNode;
    private $_sectionTree;
    
    public function __construct($config = []) {
        $this->content = '';
        $this->toc_mode = Section::TOC_MODE_NORMAL;
        $this->status = Section::STATUS_DRAFT;
        $this->comment_mode = Section::COMMENT_MODE_NORMAL;
        $this->_id = null;
        $this->_title = '';
        $this->_created_by = null;
        $this->_updated_at = null;
        $this->_sectionNode = null;
        $this->_sectionTree = [];
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
                Section::STATUS_PUBLISH,
                Section::STATUS_DELETE,
            ]],
            [['comment_mode'], 'in', 'range' =>[
                Section::COMMENT_MODE_NORMAL,
                Section::COMMENT_MODE_FORBIDDEN,
                Section::COMMENT_MODE_HIDDEN,
            ]],
        ];
    }
    
    public function scenarios() {
        return [
            'create' => ['content', 'toc_mode', 'comment_mode'],
        ];
    }
    
    public function create($validate=true) {
        if ($validate && !$this->validate()) return false;
        if ($this->_sectionNode == null) {
            $this->_sectionNode = new SectionNode();
            $this->_sectionNode->loadHtml($this->content);
        }
        $saveTransaction = Yii::$app->db->beginTransaction();
        try {
            $this->insert();
            $saveTransaction->commit();
        } catch (Exception $e) {
            $saveTransaction->rollBack();
            throw $e;
        }
        return true;
    }
    
    /**
     * Load all sections with the same ancestor.
     * @param integer $id Article ID, also the ancestor fields.
     * @param type $status Constraint on the fields of status. False while no 
     * constraint.
     * @return boolean
     */
    public function loadArticle($id, $status = [Section::STATUS_DRAFT, Section::STATUS_PUBLISH]) {
        $condition = [];
        $condition['ancestor'] = $id;
        if ($status !== false) {
            $condition['status'] = $status;
        }
        $this->_sectionTree = Section::find()->where($condition)->indexBy('id')->all();
        if (empty($this->_sectionTree)) return false;
        $this->populate($this->_sectionTree[$id]);
        return true;
    }
    
    /**
     * Populate all fields from Section.
     * @param common\models\Section $sectionModel
     */
    protected function populate($sectionModel) {
        $this->_id = $sectionModel->id;
        $this->_title = $sectionModel->title;
    }

    protected function insert() {
        $db = Yii::$app->db;
        $tblName = Section::tableName();
        $lastRow = (new Query())
                ->select(['id'])
                ->from($tblName)
                ->where(['parent' => null, 'next' => null])
                ->andWhere(['not', ['status' => Section::STATUS_DELETE]])
                ->scalar();
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
        
        $this->updateInsert($db, $tblName, $totalRows, $lastRow);
    }
    
    protected function updateInsert($db, $tblName, $totalRows, $lastRow) {
        $lastId = $db->getLastInsertID();
        $this->_id = $beginId = $lastId - $totalRows + 1;
        $stack = [];
        array_push($stack, $this->_sectionNode);
        do {
            $node = array_pop($stack);
            $db->createCommand()->update($tblName, [
                'parent' => ($node->id == 1 ? null : $node->getParent()->id + $beginId - 1),
                'ancestor' => $beginId,
                'prev' => ($node->getPrev()==false ? null : $node->getPrev()->id + $beginId - 1),
                'next' => ($node->getNext()==false ? null : $node->getNext()->id + $beginId - 1),
                    ], 'id=:id', [':id' => $node->id + $beginId - 1])
                ->execute();
            foreach ($node->getChild() as $sub) {
                array_push($stack, $sub);
            }
        } while (!empty($stack));
        if ($lastRow !== false) {
            $db->createCommand()
                    ->update($tblName, ['prev' => $lastRow], ['id' => $beginId])
                    ->execute();
            $db->createCommand()
                    ->update($tblName, ['next' => $beginId], ['id' => $lastRow])
                    ->execute();
        }
    }
    
    /**
     * Getter for all plain sections.
     * @return Section[] An array of Section objects.
     */
    public function getSections() {
        return $this->_sectionTree;
    }
    
    public function getCreatedBy() {
        if ($this->_created_by == null) {
            $tmp = $this->_sectionTree->getEntrySection()->getCreatedBy();
            if ($tmp != null) {
                $this->_created_by = $tmp->username;
            } else {
                $this->_created_by = '';
            }
        }
        return $this->_created_by;
    }
    
    public function getUpdatedAt() {
        if ($this->_updated_at == null) {
            $this->_updated_at = 0;
            foreach ($this->_sectionTree as $section) {
                if ($section->updated_at > $this->_updated_at) {
                    $this->_updated_at = $section->updated_at;
                }
            }
        }
        return $this->_updated_at;
    }
    
    public function getId() {
        return $this->_id;
    }

    /**
     * Get the title in plain text, also the title of the root section.
     * @return string
     */
    public function getTitle() {
        if(!empty($this->_title)) return $this->_title;
        return $this->_sectionTree->models[$this->_id]->title;
    }
    
    public function attributeLabels()
    {
        $section = new Section();
        return $section->attributeLabels();
    }
    
    /**
     * Get the toc of current article
     * @param array $status
     * @return array
     */
    public function getAncestorToc($status = [Section::STATUS_DRAFT, Section::STATUS_PUBLISH]) {
        $query = new Query();
        $query->select(['id', 'title', 'ancestor', 'parent', 'next', 'prev', 'ver'])
                ->from(Section::tableName())
                ->where([
                    'ancestor' => $this->_id,
                    'toc_mode' => Section::TOC_MODE_NORMAL,
                    'status' => $status
                ])->indexBy('id');
        return $query->all();
    }
    
    public static function getArticleToc($id = null, $status = [Section::STATUS_DRAFT, Section::STATUS_PUBLISH]) {
        $query = new Query();
        $query->select(['id', 'title', 'ancestor', 'parent', 'next', 'prev', 'ver'])
                ->from(Section::tableName())
                ->where([
                    'parent' => $id,
                    'toc_mode' => Section::TOC_MODE_NORMAL,
                    'status' => $status
                ])->indexBy('id');
        return $query->all();
    }
    
    public static function generateFirstChild(&$array) {
        $headingId = null;
        foreach ($array as $v) {
            if ($v['prev'] !== null && isset($array[$v['prev']])) 
                continue;
            
            if ($v['parent'] === null || !isset($array[$v['parent']]))
                $headingId = $v['id'];
            else
                $array[$v['parent']]['firstChild'] = $v['id'];
        }
        return $headingId;
    }
    
    public static function getOrderedArticleToc($id=null) {
        $res = static::getArticleToc($id);
        Yii::error($res);
        if (empty($res)) {
            return [];
        }
        $heading = static::generateFirstChild($res);
        $r = [];
        while($heading !== null) {
            $r[] = [
                'id' => $res[$heading]['id'],
                'parent' => $res[$heading]['parent']===null?'#':$res[$heading]['parent'],
                'text' => $res[$heading]['title'],
                'children' => true,
            ];
            $heading = $res[$heading]['next'];
        }
        return $r;
    }
}
