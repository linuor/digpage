<?php
/* @var $this yii\web\View */
/* @var $sections array */
/* @var $rootId integer */
?>
<li><?= $sections[$rootId]['title']?>
<?php
if (isset($sections[$rootId]['firstChild'])) {
    echo '<ul>';
    $cur = $sections[$rootId]['firstChild'];
    while ($cur !== null) {
        echo $this->render('_sort', [
            'sections' => $sections,
            'rootId' => $cur,
        ]);
        $cur = $sections[$cur]['next'];
    }
    echo '</ul>';
}
?>
</li>