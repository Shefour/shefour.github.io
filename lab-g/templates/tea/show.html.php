<?php

/** @var \App\Model\Tea $tea */
/** @var \App\Service\Router $router */

$title = "{$tea->getName()} ({$tea->getId()})";
$bodyClass = 'show';

ob_start(); ?>
    <h1><?= $tea->getName() ?></h1>
    <p><strong>Type:</strong> <?= $tea->getType() ?></p>
    <article>
        <?= $tea->getDescription();?>
    </article>

    <ul class="action-list">
        <li> <a href="<?= $router->generatePath('tea-index') ?>">Back to list</a></li>
        <li><a href="<?= $router->generatePath('tea-edit', ['id'=> $tea->getId()]) ?>">Edit</a></li>
    </ul>
<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';