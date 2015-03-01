<?php
/* @var $this yii\web\View */
/* @var $sections array */
/* @var $rootId integer */
/* @var $section common\libs\SectionRel */
$section = $sections[$rootId];
?>
<div class="section" id="<?= $rootId?>">
    <div contenteditable="true"
         data-sectionid="<?=$section->id?>"
         data-sectionver="<?=$section->ver?>"
         data-sectionstatus="<?=$section->status?>"
         data-sectioncomment_mode="<?=$section->comment_mode?>"
         data-sectiontoc_mode="<?=$section->toc_mode?>"
         >
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
