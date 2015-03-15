<?php

/* @var $this yii\web\View */
/* @var $sections array */
/* @var $rootId integer */
/* @var $level integer */
/* @var $section common\libs\SectionRel */
$section = $sections->getSection($rootId);
$tag = 'h' . ($level>6?6:$level);
$title = "<$tag>" . $section->title . "</$tag>";
?>
<div class="section" id="<?= $rootId ?>">
    <?= $title ?>
    <?= $section->content ?>
<?php
$cur = $section->firstChild;
while ($cur !== null) {
    echo $this->render('_view', [
        'sections' => $sections,
        'rootId' => $cur,
        'level' => $level + 1,
    ]);
    $cur = $sections[$cur]->next;
}
?>
</div>
