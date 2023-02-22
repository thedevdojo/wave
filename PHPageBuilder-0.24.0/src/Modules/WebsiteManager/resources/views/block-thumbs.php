<?php
$pageUrlParam = '';
if (isset($page)) {
    $pageUrlParam = '&page=' . phpb_e($page->getId());
}
?>

<div class="py-5 text-center">
    <h2><?= phpb_trans('website-manager.title') ?></h2>
</div>

<div class="row">
    <div class="col-12">

        <div class="manager-panel">
            <h4><?= phpb_trans('website-manager.render-thumbs') ?></h4>

            <div class="main-spacing">
                <iframe class="thumb-renderer" src="<?= phpb_url('pagebuilder', ['route' => 'thumb_generator', 'action' => 'renderNextBlockThumb']) ?>"></iframe>
            </div>

            <hr class="mb-3">

            <a href="<?= phpb_url('website_manager') ?>" class="btn btn-light btn-sm mr-1">
                <?= phpb_trans('website-manager.back') ?>
            </a>
        </div>
    </div>

</div>
