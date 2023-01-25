<?php

defined('_JEXEC') or die;

?>

<?php if (!empty($list)) : ?>
<ul class="archive-module">
    <?php foreach ($list as $item) : ?>
    <li><a href="<?= $item->link ?>"><?= $item->text ?></a></li>
    <?php endforeach ?>
</ul>
<?php endif ?>
