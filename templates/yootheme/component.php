<?php

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;

if (version_compare(JVERSION, '4.0', '>')) {

    $this->template = 'cassiopeia';

    $app = Factory::getApplication();
    $app->setTemplate((object) ['template' => $this->template, 'inheritable' => true]);

    $wa = $this->getWebAssetManager();
    $wa->getRegistry()->addTemplateRegistryFile($this->template, $app->getClientId());

    include JPATH_THEMES . "/{$this->template}/component.php";
    return;

}

$this->addStyleSheet("{$this->baseurl}/media/jui/css/bootstrap.min.css");
$this->addStyleSheet("{$this->baseurl}/media/jui/css/bootstrap-extended.css");
$this->addStyleSheet("{$this->baseurl}/media/jui/css/bootstrap-responsive.css");

?>
<!DOCTYPE HTML>
<html lang="<?= $this->language ?>" dir="<?= $this->direction ?>">
    <head>
        <meta charset="<?= $this->getCharset() ?>">
        <jdoc:include type="head" />
    </head>
    <body class="contentpane">
        <jdoc:include type="message" />
        <jdoc:include type="component" />
    </body>
</html>
