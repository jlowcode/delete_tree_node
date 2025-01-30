define(['jquery', 'fab/list-plugin'], function (jQuery, FbListPlugin) {
    var FbListDelete_tree_node = new Class({
        Extends   : FbListPlugin,
        initialize: function (options) {
            this.parent(options);
            jQuery('#list_' + this.options.ref + ' .delete').remove();
        },
        //Get the selected ids
        getSelectedIds: function () {
            var ids_selected = [];
            document.getElements('input[name^=ids]').each(function (c) {
                if (c.checked) {
                    ids_selected.push(c.value);
                }
            });
            return ids_selected;
        },
        //Do the ajax function
        doAjax: function (selectedIds) {
            jQuery.ajax ({
                url: '',
                method: "POST",
                data: {
                    'option': 'com_fabrik',
                    'format': 'raw',
                    'task': 'plugin.pluginAjax',
                    'plugin': 'delete_tree_node',
                    'method': 'delete',
                    'g': 'list',
                    'selectedIds': selectedIds,
                    'table_name': this.options.table_name,
                    'db_join_column': this.options.db_join_column,
                    'listid': this.options.listid,
                    'option_delete': this.options.option_delete
                }
            }).done (function (data) {
                location.reload();
            }.bind(this));
        },
        //Do the action when click on the delete button
        buttonAction: function () {
            var selectedIds = this.getSelectedIds();

            var confirmation = confirm ("Tem certeza que deseja apagar " + selectedIds.length + " registro(s)?");
            if (confirmation) {
                this.doAjax (selectedIds);
            }
        }
    });

    return FbListDelete_tree_node;
});