<?php
// The template for displaying uncategorized articles.

use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

?>

<article id="article-<?= $article->id ?>" class="uk-article"<?= $this->attrs(['data-permalink' => $permalink]) ?> typeof="Article" vocab="https://schema.org/">

    <meta property="name" content="<?= $this->e($title) ?>">
    <meta property="author" typeof="Person" content="<?= $this->e($article->author) ?>">
    <meta property="dateModified" content="<?= $this->date($article->modified, 'c') ?>">
    <meta property="datePublished" content="<?= $this->date($article->publish_up, 'c') ?>">
    <meta class="uk-margin-remove-adjacent" property="articleSection" content="<?= $this->e($article->category_title) ?>">

    <?php if ($image) : ?>
    <div class="uk-margin-large-bottom" property="image" typeof="ImageObject">
        <meta property="url" content="<?= Uri::base() . $image->attrs['src'] ?>">
        <?php if ($image->link) : ?>
        <a href="<?= $image->link ?>"><img<?= $this->attrs($image->attrs) ?>></a>
        <?php else : ?>
        <img<?= $this->attrs($image->attrs) ?>>
        <?php endif ?>
    </div>
    <?php endif ?>

    <?php if ($title) : ?>
    <h1 class="uk-article-title"><?= $title ?></h1>
    <?php endif ?>

    <?= $view('~theme/templates/meta', ['meta_style' => 'sentence', 'margin' => '', 'header_align' => false] + $params->toArray()) ?>

    <?php if ($event) echo $event->afterDisplayTitle ?>

     <?php if ($event) echo $event->beforeDisplayContent ?>

    <div class="uk-margin-medium" property="text"><?= $content ?></div>

    <?php if ($tags) : ?>
    <p class="uk-margin-medium"><?= Text::sprintf('TPL_YOOTHEME_TAGS', $tags) ?></p>
    <?php endif ?>

    <?php if ($readmore) : ?>
    <p class="uk-margin-medium">
        <a class="uk-button uk-button-text" href="<?= $readmore->link ?>"><?= $readmore->text ?></a>
    </p>
    <?php endif ?>

    <?php if ($created || $modified || $hits) : ?>
    <ul class="uk-list">

        <?php if ($created) : ?>
            <li><?= Text::sprintf('TPL_YOOTHEME_META_DATE_CREATED', $created) ?></li>
        <?php endif ?>

        <?php if ($modified) : ?>
            <li><?= Text::sprintf('TPL_YOOTHEME_META_DATE_MODIFIED', $modified) ?></li>
        <?php endif ?>

        <?php if ($hits) : ?>
            <li><?= Text::sprintf('TPL_YOOTHEME_META_HITS', $hits) ?></li>
        <?php endif ?>

    </ul>
    <?php endif ?>

    <?php if ($icons) : ?>
    <ul class="uk-subnav">
        <?php foreach ($icons as $icon) : ?>
        <li><?= $icon ?></li>
        <?php endforeach ?>
    </ul>
    <?php endif ?>

    <?php if ($pagination) : ?>
    <?= $pagination ?>
    <?php endif ?>

    <?php if ($event) echo $event->afterDisplayContent ?>

</article>
