<script type="text/javascript">

let styleManager = editor.StyleManager;

styleManager.addSector('advanced',{
    name: '<?= phpb_trans('pagebuilder.style-manager.sectors.advanced') ?>',
    open: false,
    properties: []
}, { at: 10 });

<?php
foreach (phpb_trans('pagebuilder.style-manager.properties') as $sector => $sectorProperties) {
    foreach ($sectorProperties as $property => $data) {
        if (is_array($data)) {
            for ($i = 0; $i < sizeof($data['properties'] ?? []); $i++) {
                $translation = $data['properties'][array_keys($data['properties'])[$i]];
?>
window.editor.StyleManager.getProperty('<?= phpb_e($sector) ?>', '<?= phpb_e($property) ?>').attributes.properties.models[<?= $i ?>].attributes.name = '<?= phpb_e($translation) ?>';
<?php
            }
?>
window.editor.StyleManager.getProperty('<?= phpb_e($sector) ?>', '<?= phpb_e($property) ?>').set({ name: '<?= phpb_e($data['name']) ?>' });
<?php
        } else {
?>
window.editor.StyleManager.getProperty('<?= phpb_e($sector) ?>', '<?= phpb_e($property) ?>').set({ name: '<?= phpb_e($data) ?>' });
<?php
        }
    }
}
?>
</script>
