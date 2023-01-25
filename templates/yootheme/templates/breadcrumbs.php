<?php if ($items) : ?>
<div class="uk-margin-medium-bottom">
    <ul class="uk-breadcrumb">
        <?php foreach ($items as $key => $item) : ?>

            <?php if (!empty($item->link)) : ?>
                <li><a href="<?= $item->link ?>"><?= $item->name ?></a></li>
            <?php elseif ($key !== array_key_last($items)) : ?>
                <li class="uk-disabled"><a><?= $item->name ?></a></li>
            <?php else : ?>
                <li><span><?= $item->name ?></span></li>
            <?php endif ?>

        <?php endforeach ?>
    </ul>
</div>
<?php endif ?>
