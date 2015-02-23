<?php
namespace common\libs;

use yii\base\Object;
use common\models\Section;
/**
 * Represent a section and its relationships between other sections.
 *
 * @author linuor <linuor@gmail.com>
 */
class PlainSection extends Object{
    public $id;
    public $title;
    public $content;
    public $parent;
    public $next;
    public $prev;
    public $firstChild;
    
    private $_section;
    
    /**
     * Constructor
     * @param Section $section Section model to init the object.
     * @param boolean $withChild Search for first child (default) or not.
     * @param array $config
     */
    public function __construct($section, $withChild = false, $config = []) {
        $this->_section = $section;
        $this->id = $section->id;
        $this->title = $section->title;
        $this->content = $section->content;
        $this->parent = $section->parent;
        $this->next = $section->next;
        $this->prev = $section->prev;
        $this->firstChild = null;
        if ($withChild) {
            $this->getFirstChild();
        }
        parent::__construct($config);
    }
}
