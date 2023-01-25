<?php

defined('_JEXEC') or die;

use Joomla\CMS\Helper\RouteHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Component\Tags\Site\Helper\RouteHelper as TagsRouteHelper;

?>

<?php if ($list) : ?>
<ul class="tagssimilar">
<?php foreach ($list as $item) : ?>
	<?php if (empty($item->core_title)) continue ?>
	<li>
		<?php if (($item->type_alias == 'com_users.category') || ($item->type_alias == 'com_banners.category')) : ?>
			<?= htmlspecialchars($item->core_title, ENT_COMPAT, 'UTF-8') ?>
		<?php else: $item->route = new RouteHelper() ?>
			<a href="<?= Route::_(TagsRouteHelper::getItemRoute($item->content_item_id, $item->core_alias, $item->core_catid, $item->core_language, $item->type_alias, $item->router)) ?>">
				<?= htmlspecialchars($item->core_title, ENT_COMPAT, 'UTF-8') ?>
			</a>
		<?php endif ?>
	</li>
<?php endforeach ?>
</ul>
<?php else : ?>
<span><?= Text::_('MOD_TAGS_SIMILAR_NO_MATCHING_TAGS') ?></span>
<?php endif ?>
