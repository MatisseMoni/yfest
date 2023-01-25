<?php

namespace YOOtheme;

defined('_JEXEC') or die;

$view = app(View::class);

?>

<div class="uk-grid uk-child-width-1-1" uk-grid>

    <?php foreach ($this->results as $result) : ?>

        <?php
            $article = [

                // Article
                'article' => $result,
                'content' => $result->text,
                'link' => $result->href,

                // Params
                'params' => [
                    'show_title' => true,
                    'link_titles' => true,
                ],

            ];
        ?>

        <div><?= $view('~theme/templates/article{-search,}', $article) ?></div>

    <?php endforeach ?>

</div>

<?php

echo $this->pagination->getPagesLinks();
