<?php

// Config
$mobile = '~theme.mobile';
$header = '~theme.mobile.header';
$navbar = '~theme.mobile.navbar';
$dialog = '~theme.mobile.dialog';

// Options
$layout = $config("$header.layout");
$class = ['tm-header-mobile', $config("$mobile.breakpoint") ? "uk-hidden@{$config("$mobile.breakpoint")}" : ''];
$attrs = ['uk-header' =>  $config("$mobile.header.transparent") ? true : false];
$attrs_sticky = [];

// Navbar Container
$attrs_navbar_container = [];
$attrs_navbar_container['class'][] = 'uk-navbar-container';

// Navbar
$attrs_navbar = [

    'class' => [
        'uk-navbar',
    ],

    'uk-navbar' => array_filter([
        'container' => '.tm-header-mobile',
    ]),

];

// Sticky
if ($sticky = $config("$navbar.sticky")) {

    $attrs_navbar['uk-navbar']['container'] = '.tm-header-mobile > [uk-sticky]';

    $attrs_sticky = array_filter([
        'uk-sticky' => true,
        'show-on-up' => $sticky == 2,
        'animation' => $sticky == 2 ? 'uk-animation-slide-top' : '',
        'cls-active' => 'uk-navbar-sticky',
        'sel-target' => '.uk-navbar-container',
    ]);

}

$attrs_navbar['uk-navbar'] = json_encode($attrs_navbar['uk-navbar']);

// Width Container
$attrs_width_container = [];
$attrs_width_container['class'][] = 'uk-container uk-container-expand';

?>

<?php if ($this->countModules('logo-mobile') || $this->countModules('navbar-mobile') || $this->countModules('header-mobile')) :?>

<div<?= $this->attrs(['class' => $class], $attrs) ?>>

<?php

// Horizontal layouts
if (str_starts_with($layout, 'horizontal')) :

    $attrs_width_container['class'][] = $this->countModules('logo-mobile') && $config("$header.logo_padding_remove") && $layout != 'horizontal-center-logo' ? 'uk-padding-remove-left' : '';

    ?>

    <?php if ($sticky) : ?>
    <div<?= $this->attrs($attrs_sticky) ?>>
    <?php endif ?>

        <div<?= $this->attrs($attrs_navbar_container) ?>>

            <div<?= $this->attrs($attrs_width_container) ?>>
                <nav<?= $this->attrs($attrs_navbar) ?>>

                    <?php if (($layout != 'horizontal-center-logo' && $this->countModules('logo-mobile')) || (preg_match('/^horizontal-(left|center-logo)/', $layout) && $this->countModules('navbar-mobile'))) : ?>
                    <div class="uk-navbar-left">

                        <?php if ($layout != 'horizontal-center-logo') : ?>
                            <jdoc:include type="modules" name="logo-mobile" />
                        <?php endif ?>

                        <?php if (preg_match('/^horizontal-(left|center-logo)/', $layout)) : ?>
                            <jdoc:include type="modules" name="navbar-mobile" />
                        <?php endif ?>

                    </div>
                    <?php endif ?>

                    <?php if (($layout == 'horizontal-center-logo' && $this->countModules('logo-mobile')) || ($layout == 'horizontal-center' && $this->countModules('navbar-mobile'))) : ?>
                    <div class="uk-navbar-center">

                        <?php if ($layout == 'horizontal-center-logo') : ?>
                            <jdoc:include type="modules" name="logo-mobile" />
                        <?php endif ?>

                        <?php if ($layout == 'horizontal-center') : ?>
                            <jdoc:include type="modules" name="navbar-mobile" />
                        <?php endif ?>

                    </div>
                    <?php endif ?>

                    <?php if ($this->countModules('header-mobile') || ($layout == 'horizontal-right' && $this->countModules('navbar-mobile'))) : ?>
                    <div class="uk-navbar-right">

                        <?php if ($layout == 'horizontal-right') : ?>
                            <jdoc:include type="modules" name="navbar-mobile" />
                        <?php endif ?>

                        <jdoc:include type="modules" name="header-mobile" />

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
$attrs_modal['uk-toggle'] = json_encode(array_filter([
    'cls' => 'uk-padding-large',
    'mode' => 'media',
    'media' => '@s',
]));

// Dropbar
if (str_starts_with($config("$dialog.layout"), 'dropbar')) {

    $attrs_dropbar = [];
    $attrs_dropbar['class'][] = 'uk-dropbar';

    if (!$config("$dialog.dropbar.animation") || $config("$dialog.dropbar.animation") == 'reveal-top') {
        $attrs_dropbar['class'][] = 'uk-dropbar-top';
    } elseif ($config("$dialog.dropbar.animation") == 'slide-left') {
        $attrs_dropbar['class'][] = 'uk-dropbar-left';
    }
    elseif ($config("$dialog.dropbar.animation") == 'slide-right') {
        $attrs_dropbar['class'][] = 'uk-dropbar-right';
    }

    $attrs_dropbar['uk-drop'] = json_encode(array_filter([
        // Default
        'clsDrop' => 'uk-dropbar',
        'flip' => 'false', // Has to be a string
        'container' => '.tm-header-mobile',
        'target-y' => '.tm-header-mobile .uk-navbar-container',
        // New
        'mode' => 'click',
        'target-x' => '.tm-header-mobile .uk-navbar-container',
        'stretch' => true,
        'pos' => $config("$dialog.dropbar.animation") == 'slide-right' ? 'bottom-right' : null,
        'bgScroll' => 'false', // Has to be a string
        'animation' => $config("$dialog.dropbar.animation") ?: null,
        'animateOut' => true,
        'duration' => 300,
        'toggle' => 'false', // Has to be a string
    ]));

}

?>

<?php if ($this->countModules('dialog-mobile') || $this->countModules('dialog-mobile-push')) : ?>

    <?php if (str_starts_with($config("$dialog.layout"), 'offcanvas')) : ?>
    <div id="tm-dialog-mobile" uk-offcanvas="container: true; overlay: true"<?= $this->attrs($config("$dialog.offcanvas") ?: []) ?>>
        <div class="uk-offcanvas-bar uk-flex uk-flex-column">

            <?php if ($config("$dialog.close")) : ?>
            <button class="uk-offcanvas-close uk-close-large" type="button" uk-close uk-toggle="cls: uk-close-large; mode: media; media: @s"></button>
            <?php endif ?>

            <?php if (($this->countModules('dialog-mobile'))) : ?>
            <div<?= $this->attrs($attrs_dialog) ?>>
                <jdoc:include type="modules" name="dialog-mobile" style="grid-stack" />
            </div>
            <?php endif ?>

            <?php if ($this->countModules('dialog-mobile-push')) : ?>
            <div<?= $this->attrs($attrs_dialog_push) ?>>
                <jdoc:include type="modules" name="dialog-mobile-push" style="grid-stack" />
            </div>
            <?php endif ?>

        </div>
    </div>
    <?php endif ?>

    <?php if (str_starts_with($config("$dialog.layout"), 'modal')) : ?>
    <div id="tm-dialog-mobile" class="uk-modal-full" uk-modal>
        <div class="uk-modal-dialog uk-flex">

            <?php if ($config("$dialog.close")) : ?>
            <button class="uk-modal-close-full uk-close-large" type="button" uk-close uk-toggle="cls: uk-modal-close-full uk-close-large uk-modal-close-default; mode: media; media: @s"></button>
            <?php endif ?>

            <div<?= $this->attrs($attrs_modal) ?>>

                <?php if (($this->countModules('dialog-mobile'))) : ?>
                <div<?= $this->attrs($attrs_dialog) ?>>
                    <jdoc:include type="modules" name="dialog-mobile" style="grid-stack" />
                </div>
                <?php endif ?>

                <?php if ($this->countModules('dialog-mobile-push')) : ?>
                <div<?= $this->attrs($attrs_dialog_push) ?>>
                    <jdoc:include type="modules" name="dialog-mobile-push" style="grid-stack" />
                </div>
                <?php endif ?>

            </div>

        </div>
    </div>
    <?php endif ?>

    <?php if (str_starts_with($config("$dialog.layout"), 'dropbar')) : ?>
    <div id="tm-dialog-mobile"<?= $this->attrs($attrs_dropbar) ?>>

        <div class="tm-height-min-1-1 uk-flex uk-flex-column">

            <?php if (($this->countModules('dialog-mobile'))) : ?>
            <div<?= $this->attrs($attrs_dialog) ?>>
                <jdoc:include type="modules" name="dialog-mobile" style="grid-stack" />
            </div>
            <?php endif ?>

            <?php if ($this->countModules('dialog-mobile-push')) : ?>
            <div<?= $this->attrs($attrs_dialog_push) ?>>
                <jdoc:include type="modules" name="dialog-mobile-push" style="grid-stack" />
            </div>
            <?php endif ?>

        </div>

    </div>
    <?php endif ?>

<?php endif ?>

</div>

<?php endif ?>

