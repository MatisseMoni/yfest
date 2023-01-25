<?php

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use YOOtheme\Path;
use YOOtheme\Url;

if (version_compare(JVERSION, '4.0', '<')) {
    HTMLHelper::_('behavior.core');
    $this->document->addStylesheet(
        Url::to(Path::get('~theme/html/com_finder/assets/com_finder/css/finder.min.css')),
        ['version' => 'auto']
    );
    $this->document->addScript(
        Url::to(Path::get('~theme/html/com_finder/assets/com_finder/js/finder.min.js')),
        ['version' => 'auto'],
        ['defer' => true]
    );
} else {
    $this->document->getWebAssetManager()
        ->useStyle('com_finder.finder')
        ->useScript('com_finder.finder');
}

?>
<div class="finder <?= $this->pageclass_sfx ?>">

    <?php if ($this->params->get('show_page_heading')) : ?>
        <h1>
            <?php if ($this->escape($this->params->get('page_heading'))) : ?>
                <?= $this->escape($this->params->get('page_heading')) ?>
            <?php else : ?>
                <?= $this->escape($this->params->get('page_title')) ?>
            <?php endif ?>
        </h1>
    <?php endif ?>

    <?php if ($this->params->get('show_search_form', 1)) : ?>
        <?= $this->loadTemplate('form') ?>
    <?php endif ?>

    <?php // Load the search results layout if we are performing a search. ?>
    <?php if ($this->query->search === true) : ?>
        <?= $this->loadTemplate('results') ?>
    <?php endif ?>

</div>
