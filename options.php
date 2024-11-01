<?php
require_once(ABSPATH.'wp-content/plugins/wp-ttisbdir/ttisbdir.class.php');
$dir = new wp_ttisbdir();
	if ($_REQUEST['update_options'] == "True") {
		$dir->update_options();
		update_option("wp_dir_permalinks", $_REQUEST['wp_dir_permalinks'], 'Permalinks');
	}

	
	

		$table = "{$dir->db_pre}options";
		$fields = mysql_list_fields(DB_NAME, $table);
		$columns = mysql_num_fields($fields);
		for ($i = 0; $i < $columns; $i++) {$field_array[] = mysql_field_name($fields, $i);}


		if (!in_array('sub_cat_on', $field_array))
		{
			
			$result = mysql_query("ALTER TABLE `{$dir->db_pre}options` ADD `sub_cat_on` TINYINT NOT NULL DEFAULT '1';");
			$dir->display_error_sucsess("Subcategory Option Added!");
		}
		if (!in_array('display_dir_name', $field_array))
		{
			$resulta = mysql_query("ALTER TABLE `{$dir->db_pre}options` ADD `display_dir_name` TINYINT NOT NULL DEFAULT '1';");
			$dir->display_error_sucsess("Display Directory Name Option Added!");
		}
?>
<div class="wrap">
	<h2>Options</h2>
    	<?php $dir->show_option_form(); ?>
</div>