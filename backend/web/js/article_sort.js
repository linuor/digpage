(function () {
    $('#jstree').on('move_node.jstree', function (e, data) {
        var jstree = $('#jstree').jstree(true);
        var node = data.node;
        var pkg = {
            id: node.id,
            prev: null
        };
        if (data.parent !== data.old_parent) {
            pkg.parent = data.parent==='#'?null:data.parent;
        }
        if (data.position !== 0) {
            var children = jstree.get_node(data.parent).children;
            var i = children.length;
            for(j=0;j<i;++j) {
                if (j === data.position) {
                    break;
                }
                pkg.prev = children[j];
            }
        }
        $.ajax({
            url: '/article/reorder/' + pkg.id,
            type: 'POST',
            data: pkg
        });
    }).jstree({
        'core': {
            'check_callback': function (operation, node, node_parent, node_position, more) {
                return operation === 'move_node' ? true : false;
            },
            'multiple': false,
            'data': {
                'url': '/article/toc/',
                'data': function (node) {
                    return node.id === '#' ? '' : {'id': node.id};
                }
            }
        },
        'plugins': ['dnd']
    });
})();
