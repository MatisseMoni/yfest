<?php

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;

HTMLHelper::_('stylesheet', 'mod_languages/template.css', ['version' => 'auto', 'relative' => true]);

$baseUrl = Uri::getInstance();

?>

<div class="uk-panel mod-languages">

    <?php if ($headerText) : ?>
    <p><?= $headerText ?></p>
    <?php endif ?>

    <?php if ($params->get('dropdown', 0)) : ?>

        <div class="uk-inline">

            <?php foreach ($list as $language) : ?>
                <?php if ($language->active) : ?>
                <a tabindex="0">
                    <?php if ($params->get('dropdownimage', 1) && $language->image) : ?>
                        <?= HTMLHelper::_('image', 'mod_languages/' . $language->image . '.gif', '', null, true) ?>
                    <?php endif ?>
                    <?= $language->title_native ?>
                </a>
                <?php endif ?>
            <?php endforeach ?>

            <div uk-dropdown="mode: click">
                <ul class="uk-nav uk-dropdown-nav">
                    <?php foreach ($list as $language) : ?>
                        <?php if (!$language->active || $params->get('show_active', 1)) : ?>
                        <li <?= $language->active ? 'class="uk-active"' : ''?>>
                            <a href="<?= htmlspecialchars_decode(htmlspecialchars(!$language->active ? $language->link : $baseUrl, ENT_QUOTES, 'UTF-8'), ENT_NOQUOTES) ?>">
                                <?php if ($params->get('dropdownimage', 1) && $language->image) : ?>
                                    <?= HTMLHelper::_('image', 'mod_languages/' . $language->image . '.gif', '', null, true) ?>
                                <?php endif ?>
                                <?= $language->title_native ?>
                            </a>
                        </li>
                        <?php endif ?>
                    <?php endforeach ?>
                </ul>
            </div>

        </div>

    <?php else : ?>

        <ul class="<?= $params->get('inline', 1) ? 'uk-subnav' : 'uk-nav uk-nav-default' ?>">
            <?php foreach ($list as $language) : ?>
                <?php if (!$language->active || $params->get('show_active', 1)) : ?>
                <li <?= $language->active ? 'class="uk-active"' : ''?>>
                    <a style="display: flex !important;" href="<?= htmlspecialchars_decode(htmlspecialchars(!$language->active ? $language->link : $baseUrl, ENT_QUOTES, 'UTF-8'), ENT_NOQUOTES) ?>">
                        <?php if ($params->get('image', 1) && $language->image) : ?>
                            <?= HTMLHelper::_('image', 'mod_languages/' . $language->image . '.gif', $language->title_native, ['title' => $language->title_native], true) ?>
                        <?php else : ?>
                            <?= $params->get('full_name', 1) ? $language->title_native : strtoupper($language->sef) ?>
                        <?php endif ?>
                    </a>
                </li>
                <?php endif ?>
            <?php endforeach ?>
        </ul>

    <?php endif ?>

    <?php if ($footerText) : ?>
    <p><?= $footerText ?></p>
    <?php endif ?>

</div>
