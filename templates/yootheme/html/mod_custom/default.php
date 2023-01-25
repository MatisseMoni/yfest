<?php

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Plugin\PluginHelper;

defined('_JEXEC') or die;

if ($params->def('prepare_content', 1)) {
    PluginHelper::importPlugin('content');
    $module->content = HTMLHelper::_('content.prepare', $module->content, '', 'mod_custom.content');
}

$image = $params->get('backgroundimage') ? HTMLHelper::_('image', $params->get('backgroundimage'), null, [], false, 1) : false;

?>

<?php if ($module->content) : ?>
<div class="uk-margin-remove-last-child custom" <?= $image ? " style=\"background-image:url({$image})\"" : '' ?>><?= $module->content ?></div>
<?php endif ?>
