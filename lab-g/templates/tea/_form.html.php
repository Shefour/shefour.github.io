<?php
    /** @var $tea ?\App\Model\Tea */
?>

<div class="form-group">
    <label for="name">Name</label>
    <input type="text" id="name" name="post[name]" value="<?= $tea ? $tea->getName() : '' ?>">
</div>

<div class="form-group">
    <label for="type">Type</label>
    <input type="text" id="type" name="tea[type]" value="<?= $tea ? $tea->getType() : '' ?>">
</div>

<div class="form-group">
    <label for="description">Description</label>
    <input type="text" id="description" name="tea[description]" value="<?= $tea ? $tea->getDescription() : '' ?>">
</div>

<div class="form-group">
    <label></label>
    <input type="submit" value="Submit">
</div>