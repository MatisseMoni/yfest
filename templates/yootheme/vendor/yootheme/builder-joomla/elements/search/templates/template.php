<?php

namespace YOOtheme;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Component\Finder\Site\Helper\RouteHelper;

$el = $this->el('div');

// Form
$form = $this->el('form', [

    'role' => 'search',
    'class' => [
        'uk-search',
        'uk-search-default {@search_style:}',
        'uk-search-{!search_style:}',
        'uk-width-1-1',
    ],

]);

// Search
$search = $this->el('input', [

    'type' => 'search',
    'placeholder' => Text::_('TPL_YOOTHEME_SEARCH'),
    'class' => [
        'uk-search-input',
        'uk-form-{search_size} {@!search_style}',
    ],
    'required' => true,
    'aria-label' => Text::_('TPL_YOOTHEME_SEARCH'),

]);

// Icon
$icon = $props['search_icon'] ? $this->el($props['search_icon'] == 'right' ? 'button' : 'span', [

    'uk-search-icon' => true,

    'class' => [
        'uk-search-icon-flip {@search_icon: right}',
    ],

]) : null;

/** @var Config $config */
$config = app(Config::class);

$input = Factory::getApplication()->input;

if ($config('~theme.search_module') === 'mod_finder') {

    $form->attr([
        'action' => Route::_(RouteHelper::getSearchRoute()),
        'method' => 'get',
    ]);

    $search->attr([
        'name' => 'q',
        'value' => $input->getCmd('option') === 'com_finder' ? urldecode(Factory::getApplication()->input->getString('q', '')) : '',
    ]);

    $hidden = '<input type="hidden" name="option" value="com_finder">';

} else {

    $form->attr([
        'action' => Route::_('index.php'),
        'method' => 'post',
    ]);

    $search->attr([
        'name' => 'searchword',
        'value' => $input->getCmd('option') === 'com_search' ? urldecode(Factory::getApplication()->input->getString('searchword', '')) : '',
        'minlength' => '3',
    ]);

    $hidden = '<input type="hidden" name="task" value="search"><input type="hidden" name="option" value="com_search">';

}

?>

<?= $el($props, $attrs) ?>

    <?= $form($props) ?>

        <?php if ($props['search_icon']) : ?>
        <?= $icon($props, '') ?>
        <?php endif ?>

        <?= $search($props) ?>
        <?= $hidden ?>

    <?= $form->end() ?>

<?= $el->end() ?>
