<?php
namespace common\libs;

use yii\base\Object;
use common\models\Section;
/**
 * Map of section, for quickly access the section with its id.
 *
 * @author linuor <linuor@gmail.com>
 */
class SectionTree extends Object implements \RecursiveIterator{
    private $_entryId;
    private $_sections;
    private $_firstChildren;
    private $_lastChildren;
    private $_cur;
    private $_valid;
    
    public function __construct($config = []) {
        $this->_entryId = null;
        $this->_sections = [];
        $this->_firstChildren = [];
        $this->_lastChildren = [];
        $this->_valid = false;
        if (isset($config['parent'])) {
            /* @var $tmp SectionTree */
            $tmp = $config['parent'];
            unset($config['parent']);
            $this->_sections = $tmp->getSections();
            $this->_firstChildren = $tmp->getFirstChildren();
            $this->_lastChildren = $tmp->getLastChildren();
            $this->_entryId = $this->_sections[$tmp->key()]->id;
            $this->rewind();
        } else if (isset($config['sections'])) {
            $this->setSections($config['sections']);
            unset($config['sections']);
        }
        parent::__construct($config);
    }
    
    public function getEntryId() {
        return $this->_entryId;
    }
    
    public function getEntrySection() {
        return is_null($this->_entryId) ? null:$this->getSection($this->_entryId);
    }
    
    /**
     * Get the section according the given id.
     * @param integer $id
     * @return Section
     */
    public function getSection($id) {
        return isset($this->_sections[$id]) ? $this->_sections[$id]:null;
    }
    
    public function getSections() {
        return $this->_sections;
    }
    
    /**
     * Set sections models for current article.
     * @param Section[]|Section $sections Array or instance of Section
     */
    public function setSections(&$sections) {
        $this->_valid = true;
        if ($sections instanceof Section) {
            $this->_sections[$sections->id] = $sections;
            $this->_firstChildren[$sections->id] = null;
            $this->_lastChildren[$sections->id] = null;
        }
        foreach ($sections as $section) {
            $this->_sections[$section->id] = $section;
            if ($section->parent !== null) continue;
            if ($section->prev == null) {
                $this->_firstChildren[$section->parent] = $section->id;
            }
            if ($section->next == null) {
                $this->_lastChildren[$section->parent] = $section->id;
            }
        }
        foreach ($this->_sections as $section) {
            if (isset($this->_sections[$section->parent]) ||
                    isset($this->_sections[$section->prev])) {
                        continue;
            }
            $this->_entryId = $section->id;
            break;
        }
    }
    
    public function getFirstChildren() {
        return $this->_firstChildren;
    }
    
    public function getLastChildren() {
        return $this->_lastChildren;
    }
    
    /**
     * 
     * @return Section
     */
    public function current() {
        return $this->_sections[$this->_cur];
    }
    
    public function key() {
        return $this->_cur;
    }
    
    public function next() {
        $next = $this->_sections[$this->_cur]->next;
        if ($next !== null && isset($this->_sections[$next])) {
            $this->_valid = true;
            $this->_cur = $next;
        }
        $this->_valid = false;
    }
    
    public function rewind() {
        $this->_cur = $this->_entryId;
        $this->_valid = isset($this->_sections[$this->_cur]);
    }
    
    public function valid() {
        return $this->_valid;
    }
    
    public function hasChildren() {
        return (isset($this->_firstChildren[$this->_cur]) && 
                $this->_firstChildren[$this->_cur] !==null);
    }
    
    public function getChildren() {
        return (new SectionTree([
            'parent' => $this,
        ]));
    }
}
