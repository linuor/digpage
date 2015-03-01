<?php
/* @var $this yii\web\View */
/* @var $sections array */
/* @var $rootId integer */
/* @var $level integer */
/* @var $section common\libs\SectionRel */
$section = $sections[$rootId];
$tag = 'h' . ($level>6?6:$level);
$title = "<$tag>" . $section->title . "</$tag>";
?>
<div class="section" id="section<?= $rootId?>">
    <div contenteditable="true"
         data-sectionid="<?=$section->id?>"
         data-sectionver="<?=$section->ver?>"
         data-sectionstatus="<?=$section->status?>"
         data-sectioncomment_mode="<?=$section->comment_mode?>"
         data-sectiontoc_mode="<?=$section->toc_mode?>"
         >
        <?= $title ?>
        <?= $section->content ?>
    </div>
    <?php
    $cur = $section->firstChild;
    while ($cur !== null) {
        echo $this->render('_update', [
            'sections' => $sections,
            'rootId' => $cur,
            'level' => $level + 1,
        ]);
        $cur = $sections[$cur]->next;
    }
    ?>
</div>
