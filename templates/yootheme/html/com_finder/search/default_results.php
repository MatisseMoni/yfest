<?php

namespace YOOtheme;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\String\StringHelper;

$view = app(View::class);

// Article template
$article = $view('~theme/templates/article{-search,}', function ($result) {

    $article = [

        // Article
        'article' => $result,
        'link' => $result->route,

        // Params
        'params' => [
            'show_title' => true,
            'link_titles' => true,
        ],

    ];

    if ($this->params->get('show_description', 1)) {

        // Calculate number of characters to display around the result
        $term_length = StringHelper::strlen($this->query->input);
        $desc_length = $this->params->get('description_length', 255);
        $pad_length = $term_length < $desc_length ? floor(($desc_length - $term_length) / 2) : 0;

        // Find the position of the search term
        $pos = $term_length ? StringHelper::strpos(StringHelper::strtolower($result->description), StringHelper::strtolower($this->query->input)) : false;

        // Find a potential start point
        $start = ($pos && $pos > $pad_length) ? $pos - $pad_length : 0;

        // Find a space between $start and $pos, start right after it.
        $space = StringHelper::strpos($result->description, ' ', $start > 0 ? $start - 1 : 0);
        $start = ($space && $space < $pos) ? $space + 1 : $start;

        $article['content'] = HTMLHelper::_('string.truncate', StringHelper::substr($result->description, $start), $desc_length, true);
    }

    // Get the route with highlighting information.
    if (!empty($this->query->highlight) && empty($result->mime) && $this->params->get('highlight_terms', 1) && PluginHelper::isEnabled('system', 'highlight')) {
        $article['link'] .= '&highlight=' . base64_encode(json_encode($this->query->highlight));
    }

    return $article;
});

?>
<?php // Display the suggested search if it is different from the current search. ?>
<?php if (($this->suggested && $this->params->get('show_suggested_query', 1)) || ($this->explained && $this->params->get('show_explained_query', 1))) : ?>
<p>
    <?php // Display the suggested search query. ?>
    <?php if ($this->suggested && $this->params->get('show_suggested_query', 1)) : ?>
        <?php // Replace the base query string with the suggested query string. ?>
        <?php $uri = Uri::getInstance($this->query->toUri()) ?>
        <?php $uri->setVar('q', $this->suggested) ?>

        <?php // Compile the suggested query link. ?>
        <?php $linkUrl = Route::_($uri->toString(['path', 'query'])) ?>
        <?php $link = '<a href="' . $linkUrl . '">' . $this->escape($this->suggested) . '</a>' ?>

        <?= Text::sprintf('COM_FINDER_SEARCH_SIMILAR', $link) ?>

    <?php // Display the explained search query. ?>
    <?php elseif ($this->explained && $this->params->get('show_explained_query', 1)) : ?>
        <?= $this->explained ?>
    <?php endif ?>
</p>
<?php endif ?>

<?php // Display the 'no results' message and exit the template. ?>
<?php if ($this->total == 0) : ?>
    <h1 class="uk-h2"><?= Text::_('COM_FINDER_SEARCH_NO_RESULTS_HEADING') ?></h1>
    <?php $multilang = Factory::getApplication()->getLanguageFilter() ? '_MULTILANG' : '' ?>
    <p><?= Text::sprintf('COM_FINDER_SEARCH_NO_RESULTS_BODY' . $multilang, $this->escape($this->query->input)) ?></p>
    <?php // Exit this template. ?>
<?php else : ?>

<?php // Activate the highlighter if enabled. ?>
<?php if (!empty($this->query->highlight) && $this->params->get('highlight_terms', 1)) : ?>
    <?php HTMLHelper::_('behavior.highlighter', $this->query->highlight) ?>
<?php endif ?>

<?php // Display a list of results ?>
<br id="highlighter-start" />
<?php foreach ($this->results as $result) : ?>
    <?= $article($result) ?>
<?php endforeach ?>
<br id="highlighter-end" />

<?= $this->pagination->getPagesLinks() ?>
<?php endif ?>
