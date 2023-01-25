<?php

defined('_JEXEC') or die;

use Joomla\CMS\Access\Access;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\Component\Tags\Site\Helper\RouteHelper;
use Joomla\Registry\Registry;
use YOOtheme\Path;

if (Path::get(__FILE__) !== $file = Path::get('~theme/html/layouts/joomla/content/tags.php')) {
    return include $file;
}

?>
<?php if (!empty($displayData)) : ?>
	<?php foreach ($displayData as $i => $tag) : ?>
		<?php if (in_array($tag->access, Access::getAuthorisedViewLevels(Factory::getUser()->get('id')))) : ?>
            <?php $seperator = $i !== array_key_last($displayData) ? ',' : '' ?>
			<?php $tagParams = new Registry($tag->params) ?>
			<?php $tagClass = trim(str_replace(['label-info', 'label'], '', $tagParams->get('tag_link_class', ''))) ?>
			<a href="<?= Route::_(RouteHelper::getTagRoute($tag->tag_id . '-' . $tag->alias)) ?>" class="<?= $tagClass ?>" property="keywords" vocab="https://schema.org/"><?= $this->escape($tag->title) ?></a><?= $seperator ?>
		<?php endif ?>
	<?php endforeach ?>
<?php endif ?>
