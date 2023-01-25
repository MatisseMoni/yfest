<?php

// Link
$link = $this->el('a', [

    'class' => [
        'el-link',
        'uk-link-{link_style}',
    ],

]);

?>

<?php if ($props['link']) : ?>
    <?= $link($element, [
        'href' => $props['link'],
        'uk-scroll' => str_contains((string) $props['link'], '#'),
        'target' => $props['link_target'] ? '_blank' : '',
    ], $props['content']) ?>
<?php else : ?>
    <a class="el-content uk-disabled"><?= $props['content'] ?></a>
<?php endif ?>
