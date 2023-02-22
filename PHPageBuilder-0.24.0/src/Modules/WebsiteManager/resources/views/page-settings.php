<?php
$pageUrlParam = '';
if (isset($page)) {
    $pageUrlParam = '&page=' . phpb_e($page->getId());
}

$pageTranslations = $page ? $page->getTranslations() : [];
?>

<div class="py-5 text-center">
    <h2><?= phpb_trans('website-manager.title') ?></h2>
</div>

<div class="row">
    <div class="col-12">

        <div class="manager-panel">
            <form method="post" action="<?= phpb_url('website_manager', ['route' => 'page_settings', 'action' => $action]) ?><?= $pageUrlParam ?>">
                <h4>
                    <?php
                    if ($action === 'create'):
                        echo phpb_trans('website-manager.add-new-page');
                    else:
                        echo phpb_trans('website-manager.edit-page');
                    endif;
                    ?>
                </h4>

                <div class="main-spacing">
                    <div class="form-group required">
                        <label for="name">
                            <?= phpb_trans('website-manager.name') ?>
                            <span class="text-muted">(<?= phpb_trans('website-manager.visible-in-page-overview') ?>)</span>
                        </label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= phpb_field_value('name', $page) ?>" required>
                    </div>

                    <div class="form-group required">
                        <label for="layout"><?= phpb_trans('website-manager.layout') ?></label>
                        <select class="form-control" id="layout" name="layout" required>
                            <?php
                            $value = phpb_field_value('layout', $page);
                            foreach ($theme->getThemeLayouts() as $layout):
                                if ($layout->getSlug() === $value):
                                    echo '<option value="' . phpb_e($layout->getSlug()) . '" selected>' . phpb_e($layout->getTitle()) . '</option>';
                                else:
                                    echo '<option value="' . phpb_e($layout->getSlug()) . '">' . phpb_e($layout->getTitle()) . '</option>';
                                endif;
                            endforeach;
                            ?>
                        </select>
                    </div>

                    <?php
                    foreach (phpb_active_languages() as $languageCode => $languageTranslation):
                    ?>
                    <h5 class="pt-2"><?= phpb_trans('languages.' . $languageCode) ?></h5>
                    <div class="pt-2 pl-3 pr-3">
                        <div class="form-group required">
                            <label for="page-title"><?= phpb_trans('website-manager.page-title') ?></label>
                            <input type="text" class="form-control" id="page-title" name="title[<?= phpb_e($languageCode) ?>]" value="<?= phpb_e($pageTranslations[$languageCode]['title'] ?? '') ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="page-meta-title"><?= phpb_trans('website-manager.page-meta-title') ?></label>
                            <input type="text" class="form-control" id="page-meta-title" name="meta_title[<?= phpb_e($languageCode) ?>]" value="<?= phpb_e($pageTranslations[$languageCode]['meta_title'] ?? '') ?>">
                        </div>

                        <div class="form-group">
                            <label for="page-meta-description"><?= phpb_trans('website-manager.page-meta-description') ?></label>
                            <input type="text" class="form-control" id="page-meta-description" name="meta_description[<?= phpb_e($languageCode) ?>]" value="<?= phpb_e($pageTranslations[$languageCode]['meta_description'] ?? '') ?>">
                        </div>

                        <div class="form-group required">
                            <label for="route"><?= phpb_trans('website-manager.route') ?></label>
                            <input type="text" class="form-control" id="route" name="route[<?= phpb_e($languageCode) ?>]" value="<?= phpb_e($pageTranslations[$languageCode]['route'] ?? '') ?>" required>
                        </div>
                    </div>
                    <?php
                    endforeach;
                    ?>
                </div>

                <hr class="mb-3">

                <a href="<?= phpb_url('website_manager') ?>" class="btn btn-light btn-sm mr-1">
                    <?= phpb_trans('website-manager.back') ?>
                </a>
                <button class="btn btn-primary btn-sm">
                    <?php
                    if ($action === 'create'):
                        echo phpb_trans('website-manager.add-new-page');
                    else:
                        echo phpb_trans('website-manager.save-changes');
                    endif;
                    ?>
                </button>
            </form>

        </div>
    </div>

</div>
