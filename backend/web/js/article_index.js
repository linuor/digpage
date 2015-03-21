(function(){
    var onSelectChange = function(e) {
        $obj = $(e.target);
        $parent = $obj.closest('tr');
        id = $parent.data('key');
        ver = $parent.data('sectionver');
        value = $obj.val();
        field = $obj.data('sectionfield');
        data={};
        data[field] = value;
        data['ver'] = ver;
        $.ajax({
            url: window.digpage.apiUrl + 'sections/' + id,
            type: 'PUT',
            data: data,
            success: function(data, text, xhr){
                $parent.data('sectionver', parseInt(ver) + 1);
            }
        });
    };
    
    var onDelSection = function(e) {
        if (!confirm('确定要删除么？')) {
            return;
        }
        $obj = $(e.target);
        $parent = $obj.closest('tr');
        id = $parent.data('key');
        ver = $parent.data('sectionver');
        $.ajax({
            url: window.digpage.apiUrl + 'sections/' + id,
            type: 'DELETE',
            data: {
                ver: ver
            },
            success: function(data, text, xhr){
                $parent.data('sectionver', parseInt(ver) + 1);
                $parent.find('.stauts-dropdown').val(90);
            }
        });
    };
    $(document).on('change', '.stauts-dropdown', onSelectChange);
    $(document).on('change', '.toc_mode-dropdown', onSelectChange);
    $(document).on('change', '.comment_mode-dropdown', onSelectChange);
    $(document).on('click', '.lnk-del-section', onDelSection);
})();