<?php

$mobile = str_ends_with($position, '-mobile');
$header = '~theme.' . ($mobile ? 'mobile.header' :'header');
$links = array_filter(array_slice((array) $config("{$header}.social_links", []), 0, 5));

// Attrs
$attrs = [];
$attrs['class'][] = $config("{$header}.social_style") ? 'uk-icon-button' : 'uk-icon-link';
$attrs['class'][] = 'uk-preserve-width';

// Grid
$attrs_grid = [];
$attrs_grid['class'][] = 'uk-flex-inline uk-flex-middle uk-flex-nowrap';
$attrs_grid['class'][] = $config("{$header}.social_gap") ? "uk-grid-{$config("{$header}.social_gap")}" : '';
$attrs_grid['uk-grid'] = true;

?>

<?php if (count($links)) : ?>
    <ul<?= $this->attrs($attrs_grid) ?>>
        <?php foreach ($links as $link) :
            $attrs['target'] = $config("{$header}.social_target") && (preg_match('/(tel:|mailto:)/', $link) == 0) ? '_blank' : false;

            $attrs_icon = [
                'uk-icon' => [
                    'icon: {0};' => $this->e($link, 'social'),
                    'width: {0};' => $config("{$header}.social_width"),
                ],
            ];

            ?>
            <li>
                <a<?= $this->attrs(['href' => $link], $attrs) ?> <?= $this->attrs($attrs_icon) ?>></a>
            </li>
        <?php endforeach ?>
    </ul>
<?php endif ?>
