<?php

use Joomla\CMS\Language\Text;

// Config
$blog = '~theme.blog';

?>

<?php if ($pagination->pagesTotal > 1) : ?>

    <?php if ($config("$blog.navigation") == 'pagination') : ?>
        <?= $pagination->getPagesLinks() ?>
    <?php elseif ($config("$blog.navigation") == 'previous/next') : ?>
        <ul class="uk-pagination uk-margin-large">

            <?php if ($prevlink = $pagination->getData()->previous->link) : ?>
                <li><a href="<?= $prevlink ?>"><span uk-pagination-previous></span> <?= Text::_('JPREV') ?></a></li>
            <?php endif ?>

            <?php if ($nextlink = $pagination->getData()->next->link) : ?>
                <li class="uk-margin-auto-left"><a href="<?= $nextlink ?>"><?= Text::_('JNEXT') ?> <span uk-pagination-next></span></a></li>
            <?php endif ?>

        </ul>
    <?php endif ?>

<?php endif ?>
