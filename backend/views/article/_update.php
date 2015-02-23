<?php

/* @var $this yii\web\View */
/* @var $sections array */
/* @var $rootId integer */
/* @var $section common\libs\PlainSection */
$section = $sections[$rootId];
?>
<div class="section" id="<?= $rootId ?>">
    <div contenteditable="true">
        <?= $section->title ?>
        <?= $section->content ?>
    </div>
<?php
$cur = $section->firstChild;
while ($cur !== null) {
    echo $this->render('_update', [
        'sections' => $sections,
        'rootId' => $cur,
    ]);
    $cur = $sections[$cur]->next;
}
?>
</div>
