<?php

namespace common\models;
use common\models\Section;
use common\libs\SectionNode;
use Yii;
use yii\db\Exception;
/**
 * @property string $content
 * @property integer $toc_mode
 * @property integer $status
 * @property integer $comment_mode
 */
class Article extends \yii\base\Model
{
    public $content;
    public $toc_mode;
    public $status;
    public $comment_mode;
    private $_sectionNode;
    
    public function __construct($config = array()) {
        $this->content = '';
        $this->toc_mode = Section::TOC_MODE_NORMAL;
        $this->status = Section::STATUS_DRAFT;
        $this->comment_mode = Section::COMMENT_MODE_NORMAL;
        $this->_sectionNode = null;
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
                Section::COMMENT_MODE_FORBIDDE,
                Section::COMMENT_MODE_HIDE,
            ]],
        ];
    }
    
    public function create() {
        if (!$this->validate()) return false;
        if (is_null($this->_sectionNode)) {
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
        return false;
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
        return [
            'totalRows' => $totalRows,
            'lastId' => $db->getLastInsertID(),
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
}
