(function () {
    var allItems;
    CKEDITOR.plugins.add('dropdown', {
        requires: ['richcombo'],
        init: function (editor) {
            items = allItems = editor.config.dropdowns.items;
            for (var item in items) {
                addDropDown(editor, items[item], item,
                        editor.config.dropdowns.onClick);
            }
        }
    });

    function addDropDown(editor, dropdown, index, onClick) {
        var getLabel = function (combo, index, value) {
            var groups = allItems[index]['groups'];
            var k = groups.length,
                    j, tags;
            for (j = 0; j < k; ++j) {
                tags = groups[j].tags;
                var n = tags.length,
                        m, tag;
                for (m = 0; m < n; ++m) {
                    tag = tags[m];
                    if (value == tag.value) {
                        return tag.label;
                    }
                }
            }
        };
        editor.ui.addRichCombo(index, {
            label: dropdown.label,
            title: dropdown.title,
            multiSelect: false,
            toolbar: 'digpage',
            panel: {
                css: [
                    editor.config.contentsCss,
                    CKEDITOR.getUrl(CKEDITOR.skin.getPath('editor') + 'editor.css')
                ],
                multiSelect: false
            },
            init: function () {
                var n = dropdown.groups.length,
                        i, group;
                for (i = 0; i < n; i++) {
                    group = dropdown.groups[i];
                    this.startGroup(group.label);
                    var k = group.tags.length,
                            j, tag;
                    for (j = 0; j < k; j++) {
                        tag = group.tags[j];
                        this.add(tag.value, tag.label, tag.label);
                    }
                }
            },
            onRender: function() {
                editor.on('selectionChange', function(ev){
                    var value = editor.element.$.dataset[index];
                    this.setValue(value, getLabel(this, index, value));
                }, this);
            },
            onClick: function (value) {
                this.setValue(value, getLabel(this, index, value));
                onClick(editor, index, value);
            }
        });
    };
})();
