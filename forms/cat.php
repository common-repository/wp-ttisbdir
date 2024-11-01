<?php
if (isset($_REQUEST['catid']) && $_REQUEST['catid'] != "") {
$res = $this->get_cat_info();
$result = mysql_fetch_assoc($res);
$action = "admin.php?page=wp-ttisbdir/cat.php&catid={$_REQUEST['catid']}&edit=True";
} else {
	$action = "admin.php?page=wp-ttisbdir/cat.php";
}
?>
<form method="post" name="cat" title="cat" action="<?php echo $action; ?>">
  <table class="form-table">
        <tbody><tr class="form-field form-required">
        
        <th scope="row" valign="top"><label for="catname">Category Name</label></th>
        <td><input type="text" name="catname" value="<?php echo $result['catname']; ?>" size="32"><br>
        The name is used to identify the category almost everywhere.</td>
        </tr>
               
        <tr class="form-field">
        <th scope="row" valign="top"><label for="catdesc">Description</label></th>
        <td><textarea name="catdesc" cols="50" rows="5"><?php echo $result['catdesc']; ?></textarea></td>
        </tr>       
 
        <tr class="form-field">
        <th scope="row" valign="top"><label for="enabled">Enabled</label></th>
        <?php if ($result['enabled'] == '1' || $_REQUEST['edit'] != "True" ) { $checked = 'checked="checked"'; } ?>
        <td><input type="checkbox" name="enabled" value="1" <?php echo $checked; ?><br>
        Unchek to set the category inactive.</td>
        </tr>    
    </tbody></table>
    <p class="submit"><input class="button" name="submit" value="Submit" type="submit"></p>

 

  <?php if ($_REQUEST['edit'] == "True") { ?>
  <input type="hidden" name="update_cat" value="True" />
  <?php } else { ?>
  <input type="hidden" name="add_cat" value="True">
  <?php } ?>
</form>

