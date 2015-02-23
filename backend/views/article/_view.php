<?php

/* @var $this yii\web\View */
/* @var $sections array */
/* @var $rootId integer */
/* @var $section common\libs\PlainSection */
$section = $sections[$rootId];
?>
<div class="section" id="<?= $rootId ?>">
    <?= $section->title ?>
    <?= $section->content ?>
<?php
$cur = $section->firstChild;
while ($cur !== null) {
    echo $this->render('_view', [
        'sections' => $sections,
        'rootId' => $cur,
    ]);
    $cur = $sections[$cur]->next;
}
?>
</div>
