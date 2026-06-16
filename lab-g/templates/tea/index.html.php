<?php

/** @var \App\Model\Tea[] $teas */
/** @var \App\Service\Router $router */

$title = 'Tea List';
$bodyClass = 'index';

ob_start(); ?>
    <h1>Tea List</h1>

    <a href="<?= $router->generatePath('tea-create') ?>">Create new</a>

    <ul class="index-list">
        <?php foreach ($teas as $tea): ?>
            <li><h3><?= $tea->getName() ?></h3>
                <ul class="action-list">
                    <li><a href="<?= $router->generatePath('tea-show', ['id' => $tea->getId()]) ?>">Details</a></li>
                    <li><a href="<?= $router->generatePath('tea-edit', ['id' => $tea->getId()]) ?>">Edit</a></li>
                </ul>
            </li>
        <?php endforeach; ?>
    </ul>

<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';