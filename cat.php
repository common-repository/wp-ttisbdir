<?php
require_once(ABSPATH.'wp-content/plugins/wp-ttisbdir/ttisbdir.class.php');
$dir = new wp_ttisbdir();

// Κάλεσμα συναρτήσεων που σχετίζονται με τις κατηγορίες
if (isset($_REQUEST['add_cat']) && $_REQUEST['add_cat'] == "True"){ $dir->add_cat(); }

if ($_REQUEST['update_cat'] == "True") { $dir->update_cat(); }

if (isset($_REQUEST['add_sub_cat']) && $_REQUEST['add_sub_cat'] == "True"){ $dir->add_sub_cat(); }

if ($_REQUEST['update_sub_cat'] == "True") { $dir->update_sub_cat(); }

if (isset($_REQUEST['delete_catid']) && $_REQUEST['delete_catid'] != "") { $dir->delete_cat(); }

if (isset($_REQUEST['delete_sub_cat_id']) && $_REQUEST['delete_sub_cat_id'] != "") { $dir->delete_sub_cat(); }

// Κάλεσμα συναρτήσεων που σχετίζονται με τις επιχειρήσεις
if (isset($_REQUEST['delete_listing_id']) && $_REQUEST['delete_listing_id'] != "") { $dir->delete_listing(); }

if (isset($_REQUEST['catid']) && $_REQUEST['catid'] != "" && $_REQUEST['edit'] != "True" || $_REQUEST['edit_sub'] == "True") {

?>

<div class="wrap" align="left">
	<h2><?php if ($_REQUEST['edit_sub'] == "True") { echo "Edit"; } else { echo "Add"; } ?> Sub Category</h2>
    	<?php
			$dir->show_sub_cat_form();
		?>
</div>

<div class="wrap">
	<h2>Sub Categories for <?php $cat_res = $dir->get_cat_info();
								$cat_result = mysql_fetch_assoc($cat_res);
								echo $cat_result['name']; ?></h2>
    	<?php
			$dir->show_sub_cats();
		?>
</div>



<?php } else { ?>
<div class="wrap" align="left">
	<h2><?php if ($_REQUEST['edit'] == "True") { echo "Edit Category"; } else { echo "Add Category (<a href=#viewlist>View List</a>)"; } ?></h2>
    	<?php
			$dir->show_cat_form();
		?>
</div>

<div class="wrap">

    	<?php
			$dir->show_cats();
		?>
</div>
<?php } ?>