<?php
namespace common\libs;

use common\models\Section;
/**
 * Represent a section and its relationships between other sections.
 *
 * @author linuor <linuor@gmail.com>
 */

/**
 * Extended Section, with the support of get the first child.
 * @property integer $firstChild ID of the first child section
 */
class SectionRel extends Section{
    public $firstChild;
    
    public function __construct($config = []) {
        $this->firstChild = null;
        parent::__construct($config);
    }
}
