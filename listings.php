<?php 
require_once(ABSPATH.'wp-content/plugins/wp-ttisbdir/ttisbdir.class.php');
$dir = new wp_ttisbdir();

if($_REQUEST['add_listing'] == "True") {
	$dir->add_listing();
}
if($_REQUEST['update_listing'] == "True") {
	$dir->update_listing();
}
if (isset($_REQUEST['lstid']) && $_REQUEST['delete'] == "True"){
	$dir->delete_listing();
}
if ($_REQUEST['edit'] == "True" && $_REQUEST['lstid'] != "") {
?>
<div class="wrap">
	<h2>Edit Listing</h2>
    <?php
            $dir->show_listing_form();
        ?>
</div>
<?php } else { ?>
<div class="wrap">
	<h2>Add A Listing</h2>
    <a href="javascript:collapse2.slideit()"> (Add A Listing!)</a>
    <div id="add_listing">
		<?php
            $dir->show_listing_form();
        ?>
        
   	</div>

</div>
<?php } ?>
<?php
	$dir->show_listings();
?>

