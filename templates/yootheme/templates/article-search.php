<?php
// The template for displaying articles on the search page.

?>

<article class="uk-article">

    <?php if ($title) : ?>
    <h2><?= $title ?></h2>
    <?php endif ?>

    <?= $content ?>

</article>
