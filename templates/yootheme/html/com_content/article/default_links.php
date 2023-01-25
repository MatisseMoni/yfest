<?php

use Joomla\CMS\HTML\HTMLHelper;

defined('_JEXEC') or die;

// Parameter shortcuts
$params = $this->item->params;
$urls = json_decode($this->item->urls);

?>

<?php if ($urls && (!empty($urls->urla) || !empty($urls->urlb) || !empty($urls->urlc))) : ?>
<ul class="uk-list">
    <?php
    $urlarray = [
        [$urls->urla, $urls->urlatext, $urls->targeta, 'a'],
        [$urls->urlb, $urls->urlbtext, $urls->targetb, 'b'],
        [$urls->urlc, $urls->urlctext, $urls->targetc, 'c'],
    ];

    foreach ($urlarray as $url) :
        $link = $url[0];
        $label = $url[1];
        $target = $url[2];
        $id = $url[3];

        if (!$link) continue;

        // If no label is present, take the link
        $label = $label ?: $link;

        // If no target is present, use the default
        $target = $target ?: $params->get("target{$id}");
    ?>
    <li>
        <?php
            // Compute the correct link
            switch ($target)
            {
                case 1:
                    // open in a new window
                    echo '<a href="' . htmlspecialchars($link) . '" target="_blank"  rel="nofollow">' .
                        htmlspecialchars($label) . '</a>';
                    break;

                case 2:
                    // open in a popup window
                    $attribs = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=600,height=600';
                    echo '<a href="' . htmlspecialchars($link) . "\" onclick=\"window.open(this.href, 'targetWindow', '" . $attribs . "'); return false;\">" .
                        htmlspecialchars($label) . '</a>';
                    break;
                case 3:
                    // open in a modal window
                    HTMLHelper::_('behavior.modal', 'a.modal') ?>
                    <a class="modal" href="<?= htmlspecialchars($link) ?>"  rel="{handler: 'iframe', size: {x:600, y:600}}">
                        <?= htmlspecialchars($label) . ' </a>';
                    break;

                default:
                    // open in parent window
                    echo '<a href="' . htmlspecialchars($link) . '" rel="nofollow">' .
                        htmlspecialchars($label) . ' </a>';
                    break;
            }
        ?>
    </li>
    <?php endforeach ?>
</ul>
<?php endif;
