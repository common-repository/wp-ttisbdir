<?php
require_once(ABSPATH.'wp-content/plugins/wp-ttisbdir/ttisbdir.class.php');
$bdir = new wp_ttisbdir();

if (isset($_REQUEST['id']) && $_REQUEST['approve'] == "True"){
	$bdir->approve_listing();
}
if (isset($_REQUEST['id']) && $_REQUEST['delete'] == "True"){
	$bdir->delete_listing();
}
?>
<div class="wrap">

    <?php $bdir->show_ap_listings(); ?>
</div>

