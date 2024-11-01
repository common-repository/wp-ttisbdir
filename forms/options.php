<?php
$res = $this->get_options();
$result = mysql_fetch_assoc($res);
?>
<form method="post" name="cat" action="">
  <table class="form-table">
        <tbody>
    
        <tr class="form-field">
        <th scope="row" valign="top"><label for="catpar">Columns To Display</label></th>
        <td><input type="text" name="col_nums" value="<?php echo $result['col_nums']; ?>" size="32"><br>
        Set the number of columns for the page.</td>
        </tr>

  
        <tr class="form-field">
        <th scope="row" valign="top"><label for="catpar">Directory Name</label></th>
        <td><input type="text" name="dir_name" value="<?php echo $result['dir_name']; ?>" size="32"><br>
        The name of Directory</td>
        </tr>
    
        <tr class="form-field">
        <th scope="row" valign="top"><label for="catpar">Use Permalinks</label></th>
        <?php if (get_option('wp_dir_permalinks') == '1') { $checked = 'checked="checked"'; } ?> 
        <td><input type="checkbox" name="wp_dir_permalinks" value="1" <?php echo $checked; ?>></td>
        </tr><?php $checked = ""; ?>
    
        <tr class="form-field">
        <th scope="row" valign="top"><label for="catpar">Directory On</label></th>
        <?php if ($result['dir_on'] == '1') { $checked = 'checked="checked"'; } ?> 
        <td><input type="checkbox" name="dir_on" value="1" <?php echo $checked; ?>></td>
        </tr><?php $checked = ""; ?>
    
        <tr class="form-field">
        <th scope="row" valign="top"><label for="catpar">Display Directory Name</label></th>
        <?php if ($result['display_dir_name'] == '1') { $checked = 'checked="checked"'; } ?>
        <td><input type="checkbox" name="display_dir_name" value="1" <?php echo $checked; ?>> "This should be turned off if you plan to name you page the directory name!"</td>
        </tr>
        <?php $checked = ""; ?>
    
        <tr valign="baseline">
        <td nowrap align="right">&nbsp;</td>
        <td><input type="submit" value="Submit"></td>
        </tr>
  </tbody></table>
  <input type="hidden" name="update_options" value="True">
</form>

