<?php
/**
 * Represent a Section node in the DOM.
 * A sectoin node is html element directly contains a header at least.
 * In most case, it also has some content and some children sections.
 *
 * @author linuor <linuor@gmail.com>
 */

namespace common\libs;
use DOMDocument;
use yii\base\Object;

class SectionNode extends Object {

    public $id;
    private $_title;
    private $_content;
    private $_root;
    private $_children;
    private $_parent;
    private $_next;
    private $_prev;
    
    /**
     * Constructor
     * @param \common\helper\DOMNode $node
     * @param array $config config array
     */
    public function __construct($config = []) {
        $this->reset();
        if (isset($config['DomNode'])) {
            $this->_root = $config['DomNode'];
            unset($config['DomNode']);
        }
        parent::__construct($config);
    }
    
    /**
     * Load the root node from a html string.
     * @param string $html
     */
    public function loadHtml($html) {
        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $doc->preserveWhiteSpace = true;
        $doc->loadHTML(
                mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'),
                LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        $this->reset();
        $this->_root = $doc->documentElement;
    }

    /**
     * Weather contains any sub-section.
     * @return boolean
     */
    public function hasChild() {
        if (empty($this->_children)) return false;
        $this->getChild();
        return empty($this->_children);
    }

    /**
     * Get all the child section, in an array of SectionNode
     * @return array SectionNode
     */
    public function getChild() {
        if ($this->_children === false)
        {
            $this->_children = [];
            if ($this->_root->hasChildNodes()) {
                foreach ($this->_root->childNodes as $node) {
                    if ($node->nodeType == XML_ELEMENT_NODE && self::isSectionNode($node)) {
                        $this->appendChild(new SectionNode(['DomNode' => $node]));
                    }
                }
            }
        }
        return $this->_children;
    }

    /**
     * Add a SectionNode as the last child.
     * @param SectionNode $section
     */
    public function appendChild($section) {
        if ($this->_children === false) $this->_children = [];
        
        $count = count($this->_children);
        if ($count>0) {
            $tmp = $this->_children[$count -1];
            $tmp->next = $section;
            $section->prev = $tmp;
        } else {
            $section->prev = null;
        }
        $section->next = null;
        $section->parent = $this;
        array_push($this->_children, $section);
    }
    
    /**
     * Get parent section. Return false while no parent.
     * @return \common\helper\SectionNode The parent section.
     */
    public function getParent() {
        if ($this->_parent === false) {
            $node = $this->_root->parentNode;
            while ($node && !self::isSectionNode($node)) {
                $node = $node->parentNode;
            }
            if ($node === null) {
                $this->_parent = null;
            } else {
                $tmp = new SectionNode(['DomNode' => $node]);
                $this->_parent = $tmp;
            }
        }
        return $this->_parent;
    }
    
    /**
     * @param SectionNode $sectionNode
     */
    public function setParent($sectionNode) {
        $this->_parent = $sectionNode;
    }

    /**
     * Get next section of the same parent. Return false while no next section.
     * @return \common\helper\SectionNode
     */
    public function getNext() {
        if ($this->_next === false) {
            $node = $this->_root->nextSibling;
            while ($node && !self::isSectionNode($node)) {
                $node = $node->nextSibling;
            }
            if ($node === null) {
                $this->_next = null;
            } else {
                $this->_next = new SectionNode(['DomNode' => $node]);
            }
        }
        return $this->_next;
    }
    
    public function setNext($section) {
        $this->_next = $section;
    }

    /**
     * Get prev section of the same parent. Return false while no prev section.
     * @return \common\helper\SectionNode
     */
    public function getPrev() {
        if ($this->_prev === false) {
            $node = $this->_root->previousSibling;
            while ($node && !self::isSectionNode($node)) {
                $node = $node->previousSibling;
            }
            if ($node === null) {
                $this->_prev = null;
            } else {
                $this->_prev = new SectionNode(['DomNode' => $node]);
            }
        }
        return $this->_prev;
    }
    
    public function setPrev($section) {
        $this->_prev = $section;
    }
    
    /**
     * Get the title of the current section.
     * @return string
     */
    public function getTitle() {
        if ($this->_title === false) {
            if (!$this->_root->hasChildNodes()) return $this->_title = '';
            foreach ($this->_root->childNodes as $node) {
                if ($node->nodeType == XML_ELEMENT_NODE && self::isTitle($node)) {
                    $this->_title = trim($node->nodeValue);
                    break;
                }
            }
        }
        return $this->_title;
    }
    
    /**
     * Get the content of the current section.
     * That is child nodes except headers and sub sections.
     * @return string
     */
    public function getContent() {
        if ($this->_content === false) {
            $content = '';
            if (!$this->_root->hasChildNodes()) return $this->_content = '';
            foreach ($this->_root->childNodes as $node) {
                if (self::isTitle($node) || self::isSectionNode($node)) continue;
                $content .= self::getHtml($node);
            }
            $this->_content = $content;
        }
        return $this->_content;
    }
    
    /**
     * HTML tag name of node.
     * @return string Tag name of current node.
     */
    public function getTagName() {
        return $this->_root->nodeName;
    }
    
    /**
     * Wether a node is a header(h1-h6)
     * @param DomNode $node
     * @return boolean
     */
    protected static function isTitle($node) {
        return ($node->nodeType == XML_ELEMENT_NODE &&
                preg_match('/^h[1-6]$/i', $node->nodeName) == 1);
    }

    /**
     * Detection for nodes with header(h1-h6) as child.
     * @param DomNode $node
     * @return boolean
     */
    protected static function isSectionNode($node) {
        if ($node->nodeType == XML_ELEMENT_NODE && $node->hasChildNodes()) {
            foreach ($node->childNodes as $sub) {
                if (self::isTitle($sub)) {
                    return true;
                }
            }
        }
        return false;
    }
    
    /**
     * Get the raw html of the given Node.
     * @param DomNode $node The given node
     * @return string The raw html, return false when failure.
     */
    protected static function getHtml($node) {
        $doc = new DOMDocument();
        $doc->appendChild($doc->importNode($node, true));
        $doc->encoding = 'UTF-8';
        return trim($doc->saveHtml($doc->documentElement));
    }
    
    protected function reset() {
        $this->id = false;
        $this->_root = false;
        $this->_parent = false;
        $this->_children = false;
        $this->_next = null;
        $this->_prev = null;
        $this->_title = false;
        $this->_content = false;
    }
}
