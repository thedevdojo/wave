<script type="text/javascript">

let linkType = editor.DomComponents.getType('link');
editor.DomComponents.addType('link', {
    model: linkType.model.extend({
        defaults: Object.assign({}, linkType.model.prototype.defaults, {
            traits: [
                {
                    type: 'text',
                    label: '<?= phpb_trans('pagebuilder.trait-manager.link.text') ?>',
                    name: 'content',
                    changeProp: 1,
                },
                {
                    type: 'text',
                    label: 'URL',
                    name: 'href',
                },
                {
                    type: 'text',
                    label: '<?= phpb_trans('pagebuilder.trait-manager.link.tooltip') ?>',
                    name: 'title',
                },
                {
                    type: 'select',
                    label: '<?= phpb_trans('pagebuilder.trait-manager.link.target') ?>',
                    name: 'target',
                    options: [
                        {value: '_blank', name: '<?= phpb_trans('pagebuilder.yes') ?>'},
                        {value: false, name: '<?= phpb_trans('pagebuilder.no') ?>'},
                    ],
                }
            ],
        }),
        init() {
            this.getTrait('content').setTargetValue(this.attributes.content.trim());
            if (! this.attributes.attributes.target) {
                this.getTrait('target').setTargetValue(false);
            }
        },
    }),
    view: linkType.view
});

const textType = editor.DomComponents.getType('text');
editor.DomComponents.addType('text', {
    model: {
        defaults: {
            traits: [],
            attributes: {},
        },
    },
    view: textType.view.extend({
        events: {
            click: 'onActive',
            touchend: 'onActive'
        },
    }),
});

/**
 * The raw-content type does not transform child elements into GrapesJS components.
 * This is important to prevent the RTE editor from dealing with html elements modified by GrapesJS (for instance removed inline styling).
 * Source: https://github.com/artf/grapesjs/issues/774#issuecomment-358963099
 */
editor.DomComponents.addType('raw-content', {
    model: textType.model.extend({
        },{
            isComponent: function(el) {
                if (el.hasAttribute && (el.hasAttribute('data-raw-content') || el.hasAttribute('phpb-editable'))) {
                    return {
                        type: 'raw-content',
                        content: el.innerHTML,
                        components: [] // avoid parsing children
                    };
                }
            }
        }
    ),
    view: textType.view
});

editor.DomComponents.addType('row', {
    model: {
        defaults: {
            traits: [],
            attributes: {},
        },
    },
});

editor.DomComponents.addType('cell', {
    model: {
        defaults: {
            traits: [],
            attributes: {},
        },
    },
});

editor.DomComponents.addType('default', {
    model: {
        defaults: {
            traits: [],
            attributes: {},
        },
    },
});

</script>
