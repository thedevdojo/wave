$(document).ready(function() {

    window.CKEDITOR.on('dialogDefinition', function(event) {
        let dialogName = event.data.name;
        let dialogDefinition = event.data.definition;
        if (dialogName === 'link') {
            let infoTab = dialogDefinition.getContents('info');

            dialogDefinition.onLoad = function() {
                let dialog = CKEDITOR.dialog.getCurrent();
                dialog.getContentElement('info', 'linkType').getElement().hide();
                dialog.getContentElement('info', 'protocol').getElement().hide();
                dialog.getContentElement('info', 'url').getElement().hide();
            };

            infoTab.add({
                type: 'select',
                id: 'linktype-selector',
                label: 'Linktype',
                'default': '',
                items: [
                    [window.translations['page'], "page"],
                    ["URL", "url"]
                ],
                onChange: function(obj) {
                    let dialog = CKEDITOR.dialog.getCurrent();
                    if (obj.data.value === 'page') {
                        dialog.getContentElement('info', 'page-selector').getElement().show();
                        dialog.getContentElement('info', 'url-field').getElement().hide();
                    } else {
                        dialog.getContentElement('info', 'page-selector').getElement().hide();
                        dialog.getContentElement('info', 'url-field').getElement().show();
                        dialog.getContentElement('info', 'url-field').setValue('');
                    }
                },
                setup: function(data) {
                    if (data.type === undefined) {
                        this.setValue('page');
                    } else if (data.type === 'url' && data.url.url.startsWith('[page id=')) {
                        this.setValue('page');
                    } else {
                        this.setValue(data.type);
                    }
                }
            });

            infoTab.add({
                type: 'select',
                id: 'page-selector',
                label: window.translations['page'],
                'default': '',
                items: window.pages,
                onChange: function() {
                    let dialog = CKEDITOR.dialog.getCurrent();
                    let page = '[page id=' + this.getValue() + ']';
                    dialog.setValueOf('info', 'url', page);
                    dialog.setValueOf('info', 'protocol', '');
                },
                setup: function(dialog) {
                    this.allowOnChange = false;
                    let pageId = '';
                    if (dialog.url) {
                        pageId = dialog.url.url.substr(9, dialog.url.url.length - 10);
                    }
                    this.setValue(pageId);
                    this.allowOnChange = true;
                }
            });

            infoTab.add({
                type: 'text',
                id: 'url-field',
                label: 'URL',
                'default': '',
                onChange: function() {
                    let dialog = CKEDITOR.dialog.getCurrent();
                    let url = this.getValue();
                    dialog.setValueOf('info', 'url', url);
                },
                setup: function(dialog) {
                    this.allowOnChange = false;
                    let url = '';
                    if (dialog.url) {
                        url = dialog.url.url;
                    }
                    this.setValue(url);
                    this.allowOnChange = true;
                }
            });
        }
    });

});
