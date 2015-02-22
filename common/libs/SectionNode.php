<?php
/**
 * Represent a Section node in the DOM
 *
 * @author linuor <linuor@gmail.com>
 */

namespace common\libs;
use DOMDocument;

class SectionNode extends \yii\base\Object {

    public $id;
    private $_title;
    private $_content;
    private $_root;
    private $_child;
    private $_parent;
    private $_next;
    private $_prev;
    
    /**
     * Constructor
     * @param \common\helper\DOMNode $node
     * @param array $config config array
     */
    public function __construct($node = null, $config = []) {
        $this->reset();
        $this->_root = $node;
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
        // empty array or null.
        if (empty($this->_child)) return false;
        $this->getChild();
        return empty($this->_child);
    }

    /**
     * Get all the child section, in an array of SectionNode
     * @return array SectionNode
     */
    public function getChild() {
        if ($this->_child === null)
        {
            $this->_child = [];
            if ($this->_root->hasChildNodes()) {
                foreach ($this->_root->childNodes as $node) {
                    if ($node->nodeType == XML_ELEMENT_NODE && self::isSectionNode($node)) {
                        $this->appendChild(new SectionNode($node));
                    }
                }
            }
        }
        return $this->_child;
    }

    /**
     * Add a SectionNode as the last child.
     * @param SectionNode $section
     */
    public function appendChild($section) {
        if ($this->_child === null)
            $this->_child = [];
        
        $count = count($this->_child);
        if ($count>0) {
            $tmp = $this->_child[$count -1];
            $tmp->next = $section;
            $section->prev = $tmp;
        } else {
            $section->prev = false;
        }
        $section->next = false;
        $section->parent = $this;
        array_push($this->_child, $section);
    }
    
    /**
     * Get parent section. Return false while no parent.
     * @return \common\helper\SectionNode The parent section.
     */
    public function getParent() {
        if ($this->_parent === null) {
            $node = $this->_root->parentNode;
            while ($node && !self::isSectionNode($node)) {
                $node = $node->parentNode;
            }
            if ($node === null) {
                $this->_parent = false;
            } else {
                $tmp = new SectionNode($node);
                $tmp->addChild($this);
                $this->_parent = $tmp;
            }
        }
        return $this->_parent;
    }
    
    public function setParent($section) {
        $this->_parent = $section;
    }

    /**
     * Get next section of the same parent. Return false while no next section.
     * @return \common\helper\SectionNode
     */
    public function getNext() {
        if ($this->_next === null) {
            $node = $this->_root->nextSibling;
            while ($node && !self::isSectionNode($node)) {
                $node = $node->nextSibling;
            }
            if ($node === null) {
                $this->_next = false;
            } else {
                $this->_next = new SectionNode($node);
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
        if ($this->_prev === null) {
            $node = $this->_root->previousSibling;
            while ($node && !self::isSectionNode($node)) {
                $node = $node->previousSibling;
            }
            if ($node === null) {
                $this->_prev = false;
            } else {
                $this->_prev = new SectionNode($node);
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
        if ($this->_title === null) {
            if ($this->_root->hasChildNodes()) {
                foreach ($this->_root->childNodes as $node) {
                    if ($node->nodeType == XML_ELEMENT_NODE && self::isTitle($node)) {
                        $this->_title = self::getHtml($node);
                        break;
                    }
                }
            } else {
                $this->_title = '';
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
        if ($this->_content === null) {
            $content = '';
            if ($this->_root->hasChildNodes()) {
                foreach ($this->_root->childNodes as $node) {
                    if (!self::isTitle($node) && !self::isSectionNode($node)) {
                        $content .= self::getHtml($node);
                    }
                }
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
        $this->id = null;
        $this->_root = null;
        $this->_parent = null;
        $this->_child = null;
        $this->_next = null;
        $this->_prev = null;
        $this->_title = null;
        $this->_content = null;
    }
}
