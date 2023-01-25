<?php if ($props['icon'] && $props['link']) : ?>
<a href="<?= $props['link'] ?>">[<?= $props['icon'] ?>]</a>
<?php elseif ($props['icon']) : ?>
[<?= $props['icon'] ?>]
<?php endif ?>