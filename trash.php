<?php
require_once(ABSPATH.'wp-content/plugins/wp-ttisbdir/ttisbdir.class.php');
$bdir = new wp_ttisbdir();
?>
<div class="wrap">

    <?php $bdir->show_trash_listings(); ?>
</div>