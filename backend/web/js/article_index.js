(function(){
    var onSelectChange = function(e) {
        $obj = $(e.target);
        $parent = $obj.parents('tr');
        id = $parent.data('key');
        ver = $parent.data('sectionver');
        value = $obj.val();
        field = $obj.data('sectionfield');
        data={};
        data[field] = value;
        data['ver'] = ver;
        $.ajax({
            url: 'http://api.dev.com/sections/' + id,
            type: 'put',
            data: data,
            success: function(data, text, xhr){
                $parent.data('sectionver', parseInt(ver) + 1);
            }
        });
    };
    $(document).on('change', '.stauts-dropdown', onSelectChange);
    $(document).on('change', '.toc_mode-dropdown', onSelectChange);
    $(document).on('change', '.comment_mode-dropdown', onSelectChange);
})();