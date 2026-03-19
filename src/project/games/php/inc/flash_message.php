<?php
require_once 'php/lib/session.php';
require_once 'php/lib/utils.php';

startSession();
$flash = getFlashMessage();
if ($flash) { ?>
    <p class="flash-message <?= h($flash['type']) ?>">
        <?= h($flash['message']) ?>
    </p>
<?php } ?>