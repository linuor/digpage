CKEDITOR.replace('article-content', {
    language : 'zh-cn',
    allowedContent : true,
    filebrowserBrowseUrl : '/browser/browse.php?opener=ckeditor&type=files',
    filebrowserUploadUrl : '/browser/upload.php?opener=ckeditor&type=files',
    filebrowserImageBrowseUrl : '/browser/browse.php?opener=ckeditor&type=images',
    filebrowserImageUploadUrl : '/browser/upload.php?opener=ckeditor&type=images'
});

(function(){
    $(document).on('click', '#btn-publish', function(e){
        $form = $(e.target).closest('form');
        $form.find('#hdn-ispublish').val(1);
        $form.submit();
    });
})();