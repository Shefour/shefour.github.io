<?php

/** @var \App\Model\Tea $tea */
/** @var \App\Service\Router $router */

$title = "Edit Tea {$tea->getName()} ({$tea->getId()})";
$bodyClass = "edit";

ob_start(); ?>
    <h1><?= $title ?></h1>
    <form action="<?= $router->generatePath('tea-edit') ?>" method="post" class="edit-form">
        <?php require __DIR__ . DIRECTORY_SEPARATOR . '_form.html.php'; ?>
        <input type="hidden" name="action" value="tea-edit">
        <input type="hidden" name="id" value="<?= $tea->getId() ?>">
    </form>

    <ul class="action-list">
        <li>
            <a href="<?= $router->generatePath('tea-index') ?>">Back to list</a></li>
        <li>
            <form action="<?= $router->generatePath('tea-delete') ?>" method="post">
                <input type="submit" value="Delete" onclick="return confirm('Are you sure?')">
                <input type="hidden" name="action" value="tea-delete">
                <input type="hidden" name="id" value="<?= $tea->getId() ?>">
            </form>
        </li>
    </ul>

<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';