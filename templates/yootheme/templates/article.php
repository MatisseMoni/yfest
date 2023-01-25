<?php
// The template for displaying categorized articles.

use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
use YOOtheme\Str;

$attrs_container = [];

// Image
$attrs_image['class'][] = 'uk-text-center';
$attrs_image['class'][] = $this->margin($params['image_margin']);

// Container
if ((!isset($columns) || $columns == 1) && $params['content_width'] && ($params['content_width'] != $params['width'])) {
    $attrs_container['class'][] = "uk-container uk-container-{$params['content_width']}";
}

// Title
$title_element = !$single ? 'h2' : 'h1';
$attrs_title['property'] = 'headline';
$attrs_title['class'][] = "{$this->margin($params['title_margin'])} uk-margin-remove-bottom";
$attrs_title['class'][] = $params['header_align'] ? 'uk-text-center' : '';
$attrs_title['class'][] = $params['title_style'] ? "uk-{$params['title_style']}" : 'uk-article-title';

// Content
$attrs_content['class'][] = $this->margin($params['content_margin']);
$attrs_content['class'][] = $params['content_align'] ? 'uk-text-center' : '';
$attrs_content['class'][] = $single && $params['content_dropcap'] ? 'uk-dropcap' : '';

// Tags
$attrs_tags['class'][] = $params['header_align'] ? 'uk-text-center' : '';

// Button
$attrs_button['class'][] = "uk-button uk-button-{$params['button_style']}";
$attrs_button_container['class'][] = $params['header_align'] ? 'uk-text-center' : '';
$attrs_button_container['class'][] = "uk-margin-{$params['button_margin']}";

// Image template
$imagetpl = function ($attr) use ($image, $params) {

    if (!$image) {
        return '';
    }

    $width = $params['image_width'];
    $height = $params['image_height'];

    $attrs = [
        'loading' => 'lazy',
        'alt' => $image->attrs['alt'],
        'class' => $image->attrs['class'],
    ];

    if ($this->isImage($image->attrs['src']) == 'svg') {
        $img = $this->image($image->attrs['src'], compact('width', 'height') + $attrs);
    } else {
        $img = $this->image([$image->attrs['src'], 'thumbnail' => [$width, $height], 'srcset' => true], $attrs);
    }

    ?>

    <div<?= $this->attrs($attr) ?> property="image" typeof="ImageObject">
        <meta property="url" content="<?= Uri::base() . $image->attrs['src'] ?>">
        <?php if ($image->link) : ?>
            <a href="<?= $image->link ?>"><?= $img ?></a>
        <?php else : ?>
            <?= $img ?>
        <?php endif ?>
    </div>

    <?php
};

?>

<article id="article-<?= $article->id ?>" class="uk-article"<?= $this->attrs(['data-permalink' => $permalink]) ?> typeof="Article" vocab="https://schema.org/">

    <meta property="name" content="<?= $this->e(strip_tags($title)) ?>">
    <meta property="author" typeof="Person" content="<?= $this->e($article->author) ?>">
    <meta property="dateModified" content="<?= $this->date($article->modified, 'c') ?>">
    <meta property="datePublished" content="<?= $this->date($article->publish_up, 'c') ?>">
    <meta class="uk-margin-remove-adjacent" property="articleSection" content="<?= $this->e($article->category_title) ?>">

    <?php if ($params['image_align'] == 'top') : ?>
    <?php $imagetpl($attrs_image) ?>
    <?php endif ?>

    <?php if ($attrs_container) : ?>
    <div<?= $this->attrs($attrs_container) ?>>
    <?php endif ?>

        <?php if (!$article->params['info_block_position']) : ?>
        <?= $view('~theme/templates/meta') ?>
        <?php endif ?>

        <?php if ($title) : ?>
            <<?= $title_element . $this->attrs($attrs_title) ?>>
                <?= $title ?>
            </<?= $title_element ?>>
        <?php endif ?>

        <?php if ($article->params['info_block_position']) : ?>
        <?= $view('~theme/templates/meta') ?>
        <?php endif ?>

        <?php if ($event) echo $event->afterDisplayTitle ?>

        <?php if ($params['image_align'] == 'between') : ?>

            <?php if ($attrs_container) : ?>
            </div>
            <?php endif ?>

            <?php $imagetpl($attrs_image) ?>

            <?php if ($attrs_container) : ?>
            <div<?= $this->attrs($attrs_container) ?>>
            <?php endif ?>

        <?php endif ?>

        <?php if ($event) echo $event->beforeDisplayContent ?>

        <?php if ($content) : ?>
        <div <?= $this->attrs($attrs_content) ?> property="text">

            <?php if (isset($article->toc) && $article->toc) : ?>
                <?= $article->toc ?>
            <?php endif ?>

            <?php if (is_numeric($params['content_length']) && $params['content_length'] >= 0) : ?>
                <?= Str::limit(strip_tags($content), $params['content_length'], '...', false) ?>
            <?php else : ?>
                <?= $content ?>
            <?php endif ?>

        </div>
        <?php endif ?>

        <?php if ($tags) : ?>
        <p<?= $this->attrs($attrs_tags) ?>><?= $tags ?></p>
        <?php endif ?>

        <?php if ($readmore) : ?>
        <p<?= $this->attrs($attrs_button_container) ?>>
            <a <?= $this->attrs($attrs_button) ?> href="<?= $readmore->link ?>"><?= $readmore->text ?></a>
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

    <?php if ($attrs_container) : ?>
    </div>
    <?php endif ?>

</article>
