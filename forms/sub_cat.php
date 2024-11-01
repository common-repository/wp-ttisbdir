<?php
 if ($_REQUEST['edit_sub'] == "True") {
 	$res = $this->get_sub_cat_info();
	$result = mysql_fetch_assoc($res);
}
?>

<form method="post" name="cat" title="cat" action="">
  <table class="form-table">
        <tbody><tr class="form-field form-required">
        
        <th scope="row" valign="top"><label for="name">Sub Category Name</label></th>
        <td><input type="text" name="name" value="<?php echo $result['name']; ?>" size="32"><br>
        The name is used to identify the category almost everywhere.</td>
        </tr>
	
	<th scope="row" valign="top"><label for="description">Description</label></th>
        <td><textarea name="description" cols="50" rows="5"><?php echo $result['description']; ?></textarea></td>
        </tr>
	
        <th scope="row" valign="top"><label for="enabled">Enabled</label></th>
        <?php if ($result['enabled'] == '1' || $_REQUEST['edit_sub'] != "True" ) { $checked = 'checked="checked"'; }?>
        <td><input type="checkbox" name="enabled" value="1" <?php echo $checked; ?>></td>
        </tr>

 </tbody></table>
    <p class="submit"><input class="button" name="submit" value="Submit" type="submit"></p>

  <input type="hidden" name="cat_id" value="<?php echo $_REQUEST['cat_id']; ?>" />
  <?php if ($_REQUEST['edit_sub'] == "True") { ?>
  <input type="hidden" name="update_sub_cat" value="True" />
  <input type="hidden" name="sub_cat_id" value="<?php echo $_REQUEST['sub_cat_id']; ?>" />
  <?php } else { ?>
  <input type="hidden" name="add_sub_cat" value="True">
  <?php } ?>
</form>

