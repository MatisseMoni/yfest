<?php

// Config
$mobile = '~theme.mobile';
$header = '~theme.header';
$navbar = '~theme.navbar';

$style = '';
$search = &$fields[0];
$toggle = [];
$layout = $config("$header.layout");

$attrs['class'] = array_merge(['uk-search'], (array) ($attrs['class'] ?? null));

// Style
if (in_array($position, ['logo', 'navbar', 'navbar-split', 'navbar-push', 'header', 'header-split'])) {
    $style = $config("$header.search_style");
} elseif (in_array($position, ['logo-mobile', 'navbar-mobile', 'header-mobile'])) {
    $style = $config("$mobile.header.search_style");
}

$search['type'] = 'search';
$search['class'][] = 'uk-search-input';

if ($style) {
    $search['autofocus'] = true;
}

// Modal
if ($style == 'modal') {
    $search['class'][] = 'uk-text-center';
    $attrs['class'][] = 'uk-search-large';
} else {
    $attrs['class'][] = 'uk-search-default';

    // Sidebar layouts
    if (preg_match('/^(sidebar|dialog(-push|-mobile(-push)?)?)$/', $position)) {
        $attrs['class'][] = 'uk-width-1-1';
    }

}

// Toggle
if ($style == 'modal') {

    // Navbar positions
    if (in_array($position, ['navbar', 'navbar-split', 'navbar-push', 'navbar-mobile', 'header-mobile']) ||
        (in_array($position, ['header', 'header-split']) && str_starts_with($layout, 'horizontal')) ||
        ($position == 'logo' && preg_match('/^(horizontal|stacked-center-split-[ab])/', $layout)) ||
        $position == 'logo-mobile') {

        $toggle['class'][] = 'uk-navbar-toggle';

        if (!empty($tag['id'])) {
            $toggle['id'] = $tag['id'];
        }

    } else {
        $toggle['class'][] = 'uk-search-toggle uk-display-block';
    }

}

?>

<?php if ($style == 'modal') : ?>

    <a<?= $this->attrs($toggle) ?> href="#<?= $id = $attrs['id'] . '-modal' ?>" uk-search-icon uk-toggle></a>

    <div id="<?= $id ?>" class="uk-modal-full" uk-modal="container: true">
        <div class="uk-modal-dialog uk-flex uk-flex-center uk-flex-middle" uk-height-viewport>
            <button class="uk-modal-close-full uk-close-large" type="button" uk-close uk-toggle="cls: uk-modal-close-full uk-close-large uk-modal-close-default; mode: media; media: @s"></button>
            <div class="uk-search uk-search-large">
                <?= $this->form($fields, $attrs) ?>
            </div>
        </div>
    </div>

<?php else : ?>

    <?= $this->form(array_merge([['tag' => 'span', 'uk-search-icon' => true, 'class' => $iconClass ?? '']], $fields), $attrs) ?>

<?php endif ?>




<?php // TODO include other search styles

// Dropdown + Justify
if (in_array($style, ['dropdown', 'justify'])) {
    $attrs['class'][] = 'uk-width-1-1';
}

?>

<?php if (false && $style == 'drop') : ?>

    <a<?= $this->attrs($toggle) ?> href="#" uk-search-icon></a>
    <div uk-drop="mode: click; pos: left-center; offset: 0">
        <?= $this->form($fields, $attrs) ?>
    </div>

<?php elseif (false && in_array($style, ['dropdown', 'justify'])) : ?>

    <?php

    $drop = [
        'mode' => 'click',
        'cls-drop' => 'uk-navbar-dropdown',
        'boundary' => $config("$navbar.dropdown_align") ? '!uk-navbar-container' : false,
        'boundary-align' => $config("$navbar.dropdown_boundary"),
        'pos' => $style == 'justify' ? 'bottom-justify' : 'bottom-right',
        'flip' => 'x',
    ];

    ?>

    <a<?= $this->attrs($toggle) ?> href="#" uk-search-icon></a>
    <div class="uk-navbar-dropdown" <?= $this->attrs(['uk-drop' => json_encode(array_filter($drop))]) ?>>

        <div class="uk-grid uk-grid-small uk-flex-middle">
            <div class="uk-width-expand">
                <?= $this->form($fields, $attrs) ?>
            </div>
            <div class="uk-width-auto">
                <a class="uk-navbar-dropdown-close" href="#" uk-close></a>
            </div>
        </div>

    </div>

<?php endif ?>
