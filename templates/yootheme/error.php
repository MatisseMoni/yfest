<?php

// no direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use YOOtheme\File;
use YOOtheme\Url;

$app = Factory::getApplication();
$doc = Factory::getDocument();

$template = $app->getTemplate(true);
$params = $template->params->get('config');

if (!is_array($params)) {
    $params = json_decode($params, true);
}

// Prefer child theme's error.php
if (isset($params['child_theme']) && file_exists($file = "{$directory}_{$params['child_theme']}/error.php")) {
    return include $file;
}

$error = $this->error->getCode();
$message = $this->error->getMessage();

if ($error == 404) {
    $app->triggerEvent('onLoad404', [$result = new ArrayObject()]);
}

$favicon = isset($params['favicon'])
    ? "{$this->baseurl}/{$params['favicon']}"
    : "{$this->baseurl}/templates/yootheme/vendor/yootheme/theme-joomla/assets/images/favicon.png";
$favicon_svg = isset($params['favicon_svg'])
    ? "{$this->baseurl}/{$params['favicon_svg']}"
    : false;
$touchicon = isset($params['touchicon'])
    ? "{$this->baseurl}/{$params['touchicon']}"
    : "{$this->baseurl}/templates/yootheme/vendor/yootheme/theme-joomla/assets/images/apple-touch-icon.png";

$rtl = $doc->direction == 'ltr' ? '' : '.rtl';
$style = class_exists(File::class)
    ? Url::to(File::find("~theme/css/theme{.{$template->id},}{$rtl}.css"))
    : "{$this->baseurl}/templates/system/css/theme{$rtl}.css";

$customCss = isset($params['child_theme']) && file_exists("{$directory}_{$params['child_theme']}/css/custom.css")
    ? "{$this->baseurl}/templates/{$template->template}_{$params['child_theme']}/css/custom.css"
    : false;

$customJs = isset($params['child_theme']) && file_exists("{$directory}_{$params['child_theme']}/js/custom.js")
    ? "{$this->baseurl}/templates/{$template->template}_{$params['child_theme']}/js/custom.js"
    : false;

?>

<!DOCTYPE html>
<html lang="<?= $doc->language ?>" dir="<?= $doc->direction ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= $favicon ?>" sizes="any">
        <?php if ($favicon_svg) : ?>
        <link rel="icon" href="<?= $favicon_svg ?>" type="image/svg+xml">
        <?php endif ?>
        <link rel="apple-touch-icon" href="<?= $touchicon ?>">
        <title><?= $error ?> - <?= $message ?></title>
        <link rel="stylesheet" href="<?= $style ?>" type="text/css"<?= !empty($result['customizer']) ? ' id="theme-style"' : '' ?> />
        <?php if ($customCss) : ?>
            <link rel="stylesheet" href="<?= $customCss ?>" type="text/css" />
        <?php endif ?>
        <script src="<?= $this->baseurl ?>/templates/yootheme/vendor/assets/uikit/dist/js/uikit.min.js"></script>
        <script src="<?= $this->baseurl ?>/templates/yootheme/vendor/assets/uikit/dist/js/uikit-icons.min.js"></script>
        <?php if ($customJs) : ?>
            <script src="<?= $customJs ?>"></script>
        <?php endif ?>
        <?php if (!empty($result['customizer'])) : ?>
            <script src="<?= $this->baseurl ?>/templates/yootheme/vendor/yootheme/theme/assets/js/customizer.min.js"></script>
            <?= $result['customizer'] ?>
        <?php endif ?>
    </head>
    <body>

        <?php if (!empty($result['404'])) : ?>

        <?= $result['404'] ?>

        <?php else : ?>

        <div class="uk-section uk-section-default uk-flex uk-flex-center uk-flex-middle uk-text-center" uk-height-viewport>
            <div>
                <h1 class="uk-heading-xlarge"><?= $error ?></h1>
                <p class="uk-h3"><?= $message ?></p>
                <a class="uk-button uk-button-primary" href="<?= $this->baseurl ?>/index.php"><?= Text::_('JERROR_LAYOUT_HOME_PAGE') ?></a>

                <?php if ($this->debug) : ?>
                <div class="uk-margin-large-top">
                    <?= $this->renderBacktrace() ?>

                    <?php if ($this->error->getPrevious()) : ?>

                        <?php $loop = true ?>

                        <?php $this->setError($this->_error->getPrevious()) ?>

                        <?php while ($loop === true) : ?>
                            <p><strong><?= Text::_('JERROR_LAYOUT_PREVIOUS_ERROR') ?></strong></p>
                            <p>
                                <?= htmlspecialchars($this->_error->getMessage(), ENT_QUOTES, 'UTF-8') ?>
                                <br/><?= htmlspecialchars($this->_error->getFile(), ENT_QUOTES, 'UTF-8') ?>: <?= $this->_error->getLine() ?>
                            </p>
                            <?= $this->renderBacktrace() ?>
                            <?php $loop = $this->setError($this->_error->getPrevious()) ?>
                        <?php endwhile ?>

                        <?php $this->setError($this->error) ?>

                    <?php endif ?>
                </div>
                <?php endif ?>

            </div>
        </div>

        <?php endif ?>

    </body>
</html>
