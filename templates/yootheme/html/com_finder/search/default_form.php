<?php

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use YOOtheme\Path;
use YOOtheme\Url;

// This segment of code sets up the autocompleter.
if ($this->params->get('show_autosuggest', 1))
{
    if (version_compare(JVERSION, '4.0', '<')) {
        $this->document->addStylesheet(
            Url::to(Path::get('~theme/html/com_finder/assets/awesomplete/css/awesomplete.css')),
            ['version' => 'auto']
        );
        $this->document->addScript(
            Url::to(Path::get('~theme/html/com_finder/assets/awesomplete/js/awesomplete.min.js')),
            ['version' => 'auto']
        );
    } else {
        $this->document->getWebAssetManager()->usePreset('awesomplete');
    }

    $this->document->addScriptOptions('finder-search', ['url' => Route::_('index.php?option=com_finder&task=suggestions.suggest&format=json&tmpl=component')]);
}

?>
<form action="<?= Route::_($this->query->toUri()) ?>" method="get" class="form-inline js-finder-searchform">

    <?= $this->getFields() ?>

    <fieldset class="word">
        <div class="uk-grid-small" uk-grid>
            <div class="uk-width-expand@s">

                <div class="uk-search uk-search-default uk-width-1-1">
                    <input id="q" class="uk-search-input<?= $this->params->get('show_autosuggest', 1) ? ' js-finder-search-query' : ''?>" type="text" name="q" placeholder="<?= Text::_('TPL_YOOTHEME_SEARCH') ?>" size="30" value="<?= $this->escape($this->query->input) ?>">
                </div>

            </div>
            <div class="uk-width-auto@s">

                <div class="uk-grid-small" uk-grid>
                    <div class="uk-width-auto@s">
                        <button name="Search" type="submit" class="uk-button uk-button-primary uk-width-1-1"><?= Text::_('JSEARCH_FILTER_SUBMIT') ?></button>
                    </div>
                    <?php if ($this->params->get('show_advanced', 1)) : ?>
                        <div class="uk-width-auto@s"><a href="#advancedSearch" uk-toggle="target: #advancedSearch" class="uk-button uk-button-default uk-width-1-1"><?= Text::_('COM_FINDER_ADVANCED_SEARCH_TOGGLE') ?></a></div>
                    <?php endif ?>
                </div>

            </div>
        </div>
    </fieldset>

    <?php if ($this->params->get('show_advanced', 1)) : ?>
        <div id="advancedSearch" class="uk-margin js-finder-advanced" <?php if (!$this->params->get('expand_advanced', 0)) echo ' hidden' ?>>

            <?php if (version_compare(JVERSION, '4.0', '<')) : ?>
                <?= Text::_('COM_FINDER_ADVANCED_TIPS') ?>
            <?php else : ?>
                <div>
                    <?php echo Text::_('COM_FINDER_ADVANCED_TIPS_INTRO'); ?>
                    <?php echo Text::_('COM_FINDER_ADVANCED_TIPS_AND'); ?>
                    <?php echo Text::_('COM_FINDER_ADVANCED_TIPS_NOT'); ?>
                    <?php echo Text::_('COM_FINDER_ADVANCED_TIPS_OR'); ?>
                    <?php if ($this->params->get('tuplecount', 1) > 1) : ?>
                        <?php echo Text::_('COM_FINDER_ADVANCED_TIPS_PHRASE'); ?>
                    <?php endif; ?>
                    <?php echo Text::_('COM_FINDER_ADVANCED_TIPS_OUTRO'); ?>
                </div>
            <?php endif ?>

            <div id="finder-filter-window">
                <?= HTMLHelper::_('filter.select', $this->query, $this->params) ?>
            </div>

        </div>
    <?php endif ?>

</form>
