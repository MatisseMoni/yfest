<?php

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;

?>
<ul class="categories-module">
    <?php require ModuleHelper::getLayoutPath('mod_articles_categories', $params->get('layout', 'default') . '_items') ?>
</ul>
