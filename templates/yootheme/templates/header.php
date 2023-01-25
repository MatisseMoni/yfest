<?php

// Config
$site = '~theme.site';
$header = '~theme.header';
$navbar = '~theme.navbar';
$dialog = '~theme.dialog';
$mobile = '~theme.mobile';

// Options
$layout = $config("$header.layout");
$class = ['tm-header', $config("$mobile.breakpoint") ? "uk-visible@{$config("$mobile.breakpoint")}" : ''];
$attrs = ['uk-header' => true];
$attrs_sticky = [];

// Navbar Container
$attrs_navbar_container = [];
$attrs_navbar_container['class'][] = 'uk-navbar-container';
$attrs_navbar_container['class'][] = $config("$navbar.style") ? "uk-navbar-{$config("$navbar.style")}" : '';

// Navbar
$attrs_navbar = [

    'class' => [
        'uk-navbar',
        'uk-navbar-justify' => in_array($layout, ['horizontal-justify', 'stacked-justify']),
    ],

    'uk-navbar' => array_filter([
        'align' => $config("$navbar.dropdown_align"),
        'container' => '.tm-header',
        'boundary' => '.tm-header .uk-navbar-container', // By default, it would be the navbar component's element
        'target-x' => $config("$navbar.dropdown_target") ? '.tm-header .uk-navbar' : null,
        'dropbar' => $config("$navbar.dropbar") ? true : null,
        'target-y' => $config("$navbar.dropbar") ? '.tm-header .uk-navbar-container' : null,
        'dropbar-anchor' => $config("$navbar.dropbar") ? '.tm-header .uk-navbar-container' : null, // Has to be navbar container because it has the style
    ]),

];

// Sticky
if ($sticky = $config("$navbar.sticky")) {

    $attrs_navbar['uk-navbar']['container'] = '.tm-header > [uk-sticky]';

    $attrs_sticky = array_filter([
        'uk-sticky' => true,
        'media' => $config("$mobile.breakpoint") ? "@{$config("$mobile.breakpoint")}" : false,
        'show-on-up' => $sticky == 2,
        'animation' => $sticky == 2 ? 'uk-animation-slide-top' : '',
        'cls-active' => 'uk-navbar-sticky',
        'sel-target' => '.uk-navbar-container',
    ]);

}

$attrs_navbar['uk-navbar'] = json_encode($attrs_navbar['uk-navbar']);

// Outside
$outside = $config("$site.layout") == 'boxed' && $config("$site.boxed.header_outside");

if ($outside && $config("$site.boxed.header_transparent")) {

    $attrs_headerbar = [
        'class' => ["uk-{$config("$site.boxed.header_transparent")}"],
    ];

    if ($sticky) {
        $attrs_sticky['cls-inactive'] = "uk-navbar-transparent uk-{$config("$site.boxed.header_transparent")}";
        $attrs_sticky['top'] = '300';
        if ($sticky == 1) {
            $attrs_sticky['animation'] = 'uk-animation-slide-top';
        }
    } else {
        $attrs_navbar_container['class'][] = "uk-navbar-transparent uk-{$config("$site.boxed.header_transparent")}";
    }

} else {

    $attrs_headerbar = [
        'class' => ['tm-headerbar-default'],
    ];

}

// Width Container
$attrs_width_container = [];
$attrs_width_container['class'][] = 'uk-container';

if ($outside) {
    $attrs_width_container['class'][] = $config("$header.width") == 'expand' ? 'uk-container-expand' : 'tm-page-width';
} else {
    $attrs_width_container['class'][] = $config("$header.width") != 'default' ? "uk-container-{$config("$header.width")}" : '';
}

$hasPositionWithModule = array_filter([
    'logo',
    'header',
    'header-split',
    'navbar',
    'navbar-push',
    'navbar-split',
], function ($position) { return $this->countModules($position); });

$toolbar = trim($view('~theme/templates/toolbar'));

?>

<?php if ($config("$mobile.breakpoint")) : ?>
<?= $view('~theme/templates/header-mobile') ?>
<?php endif ?>

<?php if (!$config("$site.toolbar_transparent")) : ?>
<?= $toolbar ?>
<?php endif ?>

<?php if ($hasPositionWithModule || $config("$site.toolbar_transparent") && $toolbar) : ?>

<div<?= $this->attrs(['class' => $class], $attrs) ?>>

<?php if ($config("$site.toolbar_transparent")) : ?>
<?= $toolbar ?>
<?php endif ?>

<?php

if ($hasPositionWithModule) :

// Horizontal layouts
if (str_starts_with($layout, 'horizontal')) :

    $attrs_width_container['class'][] = $this->countModules('logo') && $config("$header.logo_padding_remove") && $config("$header.width") == 'expand' && $layout != 'horizontal-center-logo' ? 'uk-padding-remove-left' : '';

    ?>

    <?php if ($sticky) : ?>
    <div<?= $this->attrs($attrs_sticky) ?>>
    <?php endif ?>

        <div<?= $this->attrs($attrs_navbar_container) ?>>

            <div<?= $this->attrs($attrs_width_container) ?>>
                <nav<?= $this->attrs($attrs_navbar) ?>>

                    <?php if (($layout != 'horizontal-center-logo' && $this->countModules('logo')) ||
                        (preg_match('/^horizontal-(left|justify|center-logo)/', $layout) && $this->countModules('navbar')) ||
                        ($layout == 'horizontal-justify' && $this->countModules('header'))
                    ) : ?>
                    <div class="uk-navbar-left">

                        <?php if ($layout != 'horizontal-center-logo') : ?>
                            <jdoc:include type="modules" name="logo" />
                        <?php endif ?>

                        <?php if (preg_match('/^horizontal-(left|justify|center-logo)/', $layout)) : ?>
                            <jdoc:include type="modules" name="navbar" />
                        <?php endif ?>

                        <?php if ($layout == 'horizontal-justify') : ?>
                            <jdoc:include type="modules" name="header" />
                        <?php endif ?>

                    </div>
                    <?php endif ?>

                    <?php if (($layout == 'horizontal-center-logo' && $this->countModules('logo')) ||
                        ($layout == 'horizontal-center' && $this->countModules('navbar'))
                    ) : ?>
                    <div class="uk-navbar-center">

                        <?php if ($layout == 'horizontal-center-logo') : ?>
                            <jdoc:include type="modules" name="logo" />
                        <?php endif ?>

                        <?php if ($layout == 'horizontal-center') : ?>
                            <jdoc:include type="modules" name="navbar" />
                        <?php endif ?>

                    </div>
                    <?php endif ?>

                    <?php if (($layout != 'horizontal-justify' && $this->countModules('header')) ||
                        ($layout == 'horizontal-right' && $this->countModules('navbar'))
                    ) : ?>
                    <div class="uk-navbar-right">

                        <?php if ($layout == 'horizontal-right') : ?>
                            <jdoc:include type="modules" name="navbar" />
                        <?php endif ?>

                        <?php if ($layout != 'horizontal-justify') : ?>
                            <jdoc:include type="modules" name="header" />
                        <?php endif ?>

                    </div>
                    <?php endif ?>

                </nav>
            </div>

        </div>

    <?php if ($sticky) : ?>
    </div>
    <?php endif ?>

<?php endif ?>

<?php

// Stacked Center layouts
if (preg_match('/^stacked-center-(split-)?[ab]/', $layout)) : ?>

    <?php if ((in_array($layout, ['stacked-center-a', 'stacked-center-b']) && $this->countModules('logo')) || $layout == 'stacked-center-a' && $this->countModules('header')) : ?>
    <div<?= $this->attrs($attrs_headerbar, ['class' => 'tm-headerbar tm-headerbar-top']) ?>>
        <div<?= $this->attrs($attrs_width_container) ?>>

            <?php if ($this->countModules('logo')) : ?>
                <jdoc:include type="modules" name="logo" style="grid-center" />
            <?php endif ?>

            <?php if ($layout == 'stacked-center-a' && $this->countModules('header')) : ?>
            <div class="tm-headerbar-stacked uk-grid-medium uk-child-width-auto uk-flex-center uk-flex-middle" uk-grid>
                <jdoc:include type="modules" name="header" style="cell" />
            </div>
            <?php endif ?>

        </div>
    </div>
    <?php endif ?>

    <?php if ($this->countModules('logo') || $this->countModules('navbar') || $this->countModules('navbar-split')) : ?>

        <?php if ($sticky) : ?>
        <div<?= $this->attrs($attrs_sticky) ?>>
        <?php endif ?>

            <div<?= $this->attrs($attrs_navbar_container) ?>>

                <div<?= $this->attrs($attrs_width_container) ?>>
                    <nav<?= $this->attrs($attrs_navbar) ?>>

                        <?php if ($layout == 'stacked-center-split-b' && $this->countModules('navbar-split')) : ?>
                        <div class="uk-navbar-left">
                            <jdoc:include type="modules" name="navbar-split" />
                        </div>
                        <?php endif ?>

                        <div class="uk-navbar-center">

                            <?php if ($layout == 'stacked-center-split-a') : ?>

                                <?php if ($this->countModules('navbar-split')) : ?>
                                <div class="uk-navbar-center-left uk-preserve-width">
                                    <jdoc:include type="modules" name="navbar-split" />
                                </div>
                                <?php endif ?>

                                <jdoc:include type="modules" name="logo" />

                                <?php if ($this->countModules('navbar')) : ?>
                                <div class="uk-navbar-center-right uk-preserve-width">
                                    <jdoc:include type="modules" name="navbar" />
                                </div>
                                <?php endif ?>

                            <?php elseif ($layout == 'stacked-center-split-b') : ?>
                                <jdoc:include type="modules" name="logo" />
                            <?php else : ?>
                                <jdoc:include type="modules" name="navbar" />
                            <?php endif ?>

                        </div>

                        <?php if ($layout == 'stacked-center-split-b' && $this->countModules('navbar')) : ?>
                        <div class="uk-navbar-right">
                            <jdoc:include type="modules" name="navbar" />
                        </div>
                        <?php endif ?>

                    </nav>
                </div>

            </div>

        <?php if ($sticky) : ?>
        </div>
        <?php endif ?>

    <?php endif ?>

    <?php if (in_array($layout, ['stacked-center-b', 'stacked-center-split-a', 'stacked-center-split-b']) && $this->countModules('header')) : ?>
    <div<?= $this->attrs($attrs_headerbar, ['class' => 'tm-headerbar tm-headerbar-bottom']) ?>>
        <div<?= $this->attrs($attrs_width_container) ?>>
            <jdoc:include type="modules" name="header" style="grid-center" />
        </div>
    </div>
    <?php endif ?>

<?php endif ?>

<?php

// Stacked Center C layout
if ($layout == 'stacked-center-c') : ?>

    <?php if ($this->countModules('logo') || $this->countModules('header') || $this->countModules('header-split')) : ?>
    <div<?= $this->attrs($attrs_headerbar, ['class' => 'tm-headerbar tm-headerbar-top']) ?>>
        <div<?= $this->attrs($attrs_width_container) ?>>
            <div class="uk-position-relative uk-flex uk-flex-center uk-flex-middle">

                <?php if ($this->countModules('header')) : ?>
                <div class="uk-position-center-left tm-position-z-index-high">
                    <jdoc:include type="modules" name="header" style="grid-middle" />
                </div>
                <?php endif ?>

                <?php if ($this->countModules('logo')) : ?>
                <jdoc:include type="modules" name="logo" style="grid-middle" />
                <?php endif ?>

                <?php if ($this->countModules('header-split')) : ?>
                <div class="uk-position-center-right tm-position-z-index-high">
                    <jdoc:include type="modules" name="header-split" style="grid-middle" />
                </div>
                <?php endif ?>

            </div>
        </div>
    </div>
    <?php endif ?>

    <?php if ($this->countModules('navbar')) : ?>

        <?php if ($sticky) : ?>
        <div<?= $this->attrs($attrs_sticky) ?>>
        <?php endif ?>

            <div<?= $this->attrs($attrs_navbar_container) ?>>

                <div<?= $this->attrs($attrs_width_container) ?>>
                    <nav<?= $this->attrs($attrs_navbar) ?>>

                        <div class="uk-navbar-center">
                            <jdoc:include type="modules" name="navbar" />
                        </div>

                    </nav>
                </div>

            </div>

        <?php if ($sticky) : ?>
        </div>
        <?php endif ?>

    <?php endif ?>

<?php endif ?>

<?php

// Stacked Left layout

if (preg_match('/^stacked-(left|justify)/', $layout)) :

    $attrs_width_container['class'][] = 'uk-flex uk-flex-middle';
    $attrs_navbar['class'][] = 'uk-flex-auto';

    ?>

    <?php if ($this->countModules('logo') || $this->countModules('header')) : ?>
    <div<?= $this->attrs($attrs_headerbar, ['class' => 'tm-headerbar tm-headerbar-top']) ?>>
        <div<?= $this->attrs($attrs_width_container) ?>>

            <?php if ($this->countModules('logo')) : ?>
            <jdoc:include type="modules" name="logo" style="grid-middle" />
            <?php endif ?>

            <?php if ($this->countModules('header')) : ?>
            <div class="uk-margin-auto-left">
                <jdoc:include type="modules" name="header" style="grid-middle" />
            </div>
            <?php endif ?>

        </div>
    </div>
    <?php endif ?>

    <?php if ($this->countModules('navbar') || $this->countModules('navbar-push')) : ?>

        <?php if ($sticky) : ?>
        <div<?= $this->attrs($attrs_sticky) ?>>
        <?php endif ?>

            <div<?= $this->attrs($attrs_navbar_container) ?>>

                <div<?= $this->attrs($attrs_width_container) ?>>
                    <nav<?= $this->attrs($attrs_navbar) ?>>

                        <?php if ($this->countModules('navbar')) : ?>
                        <div class="uk-navbar-left">
                            <jdoc:include type="modules" name="navbar" />
                        </div>
                        <?php endif ?>

                        <?php if ($this->countModules('navbar-push')) : ?>
                        <div class="uk-navbar-right">
                            <jdoc:include type="modules" name="navbar-push" />
                        </div>
                        <?php endif ?>

                    </nav>
                </div>

            </div>

        <?php if ($sticky) : ?>
        </div>
        <?php endif ?>

    <?php endif ?>

<?php endif ?>

<?php

// Dialog
$attrs_dialog = [];
$attrs_dialog_push = [];

if (preg_match('/^(offcanvas|modal|dropbar)-center/', $config("$dialog.layout"))) {
    $attrs_dialog['class'][] = 'uk-margin-auto-vertical';
} else {
    $attrs_dialog['class'][] = 'uk-margin-auto-bottom';
}
$attrs_dialog_push['class'][] = 'uk-grid-margin';

$attrs_dialog['class'][] = $config("$dialog.text_center") ? 'uk-text-center' : '';
$attrs_dialog_push['class'][] = $config("$dialog.text_center") ? 'uk-text-center' : '';

// Modal
$attrs_modal = [];
$attrs_modal['class'][] = 'uk-modal-body uk-padding-large uk-margin-auto uk-flex uk-flex-column uk-box-sizing-content';
$attrs_modal['class'][] = $config("$dialog.modal.width") ? 'uk-width-' .  $config("$dialog.modal.width") : 'uk-width-auto@s';
$attrs_modal['uk-height-viewport'] = true;

// Dropbar
if (str_starts_with($config("$dialog.layout"), 'dropbar')) {

    $attrs_dropbar = [];
    $attrs_dropbar['class'][] = 'uk-dropbar uk-dropbar-large';

    if (!$config("$dialog.dropbar.animation") || $config("$dialog.dropbar.animation") == 'reveal-top') {
        $attrs_dropbar['class'][] = 'uk-dropbar-top';
    } elseif ($config("$dialog.dropbar.animation") == 'slide-left') {
        $attrs_dropbar['class'][] = 'uk-dropbar-left';
        $attrs_dropbar['class'][] = $config("$dialog.dropbar.width") ? 'uk-width-' . $config("$dialog.dropbar.width") : '';
    }
    elseif ($config("$dialog.dropbar.animation") == 'slide-right') {
        $attrs_dropbar['class'][] = 'uk-dropbar-right';
        $attrs_dropbar['class'][] = $config("$dialog.dropbar.width") ? 'uk-width-' . $config("$dialog.dropbar.width") : '';
    }

    $attrs_dropbar['uk-drop'] = json_encode(array_filter([
        // Default
        'clsDrop' => 'uk-dropbar',
        'flip' => 'false', // Has to be a string
        'container' => '.tm-header',
        'target-y' => '.tm-header .uk-navbar-container',
        // New
        'mode' => 'click',
        'target-x' => '.tm-header .uk-navbar-container',
        'boundary-x' => $config("$site.layout") == 'boxed' && !$config("$site.boxed.header_outside") ? '.tm-header .uk-navbar-container' : null,
        'stretch' => in_array($config("$dialog.dropbar.animation"), ['slide-left', 'slide-right']) && $config("$dialog.dropbar.width") ? 'y' : true,
        'pos' => $config("$dialog.dropbar.animation") == 'slide-right' ? 'bottom-right' : null,
        'bgScroll' => 'false', // Has to be a string
        'animation' => $config("$dialog.dropbar.animation") ?: null,
        'animateOut' => true,
        'duration' => 300,
        'toggle' => 'false', // Has to be a string
    ]));

    $attrs_dropbar_content = [];
    $attrs_dropbar_content['class'][] = 'tm-height-min-1-1 uk-flex uk-flex-column';
    $attrs_dropbar_content['class'][] = $config("$dialog.dropbar.content_width") ? 'uk-' .  $config("$dialog.dropbar.content_width") . ' uk-margin-auto' : '';
    $attrs_dropbar_content['class'][] = $config("$dialog.dropbar.content_width") == 'container' ? 'uk-padding-remove-horizontal' : '';

}

?>

<?php if ($this->countModules('dialog') || $this->countModules('dialog-push')) : ?>

    <?php if (str_starts_with($config("$dialog.layout"), 'offcanvas')) : ?>
    <div id="tm-dialog" uk-offcanvas="container: true"<?= $this->attrs($config("$dialog.offcanvas") ?: []) ?>>
        <div class="uk-offcanvas-bar uk-flex uk-flex-column">

            <button class="uk-offcanvas-close uk-close-large" type="button" uk-close uk-toggle="cls: uk-close-large; mode: media; media: @s"></button>

            <?php if (($this->countModules('dialog'))) : ?>
            <div<?= $this->attrs($attrs_dialog) ?>>
                <jdoc:include type="modules" name="dialog" style="grid-stack" />
            </div>
            <?php endif ?>

            <?php if ($this->countModules('dialog-push')) : ?>
            <div<?= $this->attrs($attrs_dialog_push) ?>>
                <jdoc:include type="modules" name="dialog-push" style="grid-stack" />
            </div>
            <?php endif ?>

        </div>
    </div>
    <?php endif ?>

    <?php if (str_starts_with($config("$dialog.layout"), 'modal')) : ?>
    <div id="tm-dialog" class="uk-modal-full" uk-modal>
        <div class="uk-modal-dialog uk-flex">

            <button class="uk-modal-close-full uk-close-large" type="button" uk-close uk-toggle="cls: uk-close-large; mode: media; media: @s"></button>

            <div<?= $this->attrs($attrs_modal) ?>>

                <?php if (($this->countModules('dialog'))) : ?>
                <div<?= $this->attrs($attrs_dialog) ?>>
                    <jdoc:include type="modules" name="dialog" style="grid-stack" />
                </div>
                <?php endif ?>

                <?php if ($this->countModules('dialog-push')) : ?>
                <div<?= $this->attrs($attrs_dialog_push) ?>>
                    <jdoc:include type="modules" name="dialog-push" style="grid-stack" />
                </div>
                <?php endif ?>

            </div>

        </div>
    </div>
    <?php endif ?>

    <?php if (str_starts_with($config("$dialog.layout"), 'dropbar')) : ?>
    <div id="tm-dialog"<?= $this->attrs($attrs_dropbar) ?>>

        <div<?= $this->attrs($attrs_dropbar_content) ?>>

            <?php if (($this->countModules('dialog'))) : ?>
            <div<?= $this->attrs($attrs_dialog) ?>>
                <jdoc:include type="modules" name="dialog" style="grid-stack" />
            </div>
            <?php endif ?>

            <?php if ($this->countModules('dialog-push')) : ?>
            <div<?= $this->attrs($attrs_dialog_push) ?>>
                <jdoc:include type="modules" name="dialog-push" style="grid-stack" />
            </div>
            <?php endif ?>

        </div>

    </div>
    <?php endif ?>

<?php endif ?>

<?php endif ?>

</div>

<?php endif ?>
