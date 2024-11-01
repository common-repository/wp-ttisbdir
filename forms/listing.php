<?php
	if($_REQUEST['edit'] == "True") {
		$res = $this->get_listing_info();
		$result = mysql_fetch_assoc($res);
	}
?>
<form method="post" name="listing" enctype="multipart/form-data" title="listing" action="">
  <table class="form-table">
        <tbody><tr class="form-field form-required"></tr>

		<tr class="form-field">
        <th scope="row" valign="top"><label for="lstnm">Listing Name</label></th>
        <td><input type="text" name="lstnm" value="<?php echo $result['lstnm']; ?>" size="32" /><br>
        The name is used to identify the category almost everywhere.</td>
        </tr>
        
        <tr class="form-field">
        <th scope="row" valign="top"><label for="lstcat">Category</label></th>
<td><select name="lstcat">
        <?php echo $this->get_cats_for_options($result['sub_cat']); ?>
      </select>      </td>
        </tr>
               
        <tr class="form-field">
        <th scope="row" valign="top"><label for="lstdsc">Description</label></th>
        <td><textarea name="lstdsc" cols="50" rows="5"><?php echo $result['lstdsc']; ?></textarea>      </td>
        </tr>
        
        <tr class="form-field">
        <th scope="row" valign="top"><label for="lstlnk">Link</label></th>
        <td><input type="text" name="lstlnk" value="<?php echo $result['lstlnk']; ?>" size="32" /><br>
        The name is used to identify the category almost everywhere.</td>
        </tr>
        
        <tr class="form-field">
        <th scope="row" valign="top"><label for="lstmail">E-mail</label></th>
        <td><input type="text" name="lstmail" value="<?php echo $result['lstmail']; ?>" size="32" /></td>
        </tr>
        
        <tr class="form-field">
        <th scope="row" valign="top"><label for="lstcnt">Contact Person</label></th>
        <td><input type="text" name="lstcnt" value="<?php echo $result['lstcnt']; ?>" size="32" /></td>
        </tr>
        
        <tr class="form-field">
        <th scope="row" valign="top"><label for="lstaddr">Address</label></th>
        <td><input type="text" name="lstaddr" value="<?php echo $result['lstaddr']; ?>" size="32" /></td>
        </tr>
        
        <tr class="form-field">
        <th scope="row" valign="top"><label for="lststate">State</label></th>
        <td><input type="text" name="lststate" value="<?php echo $result['lststate']; ?>" size="32" /></td>
        </tr>
        
        <tr class="form-field">
        <th scope="row" valign="top"><label for="lstcity">City</label></th>
        <td><input type="text" name="lstcity" value="<?php echo $result['lstcity']; ?>" size="32" /></td>
        </tr>
        
        <tr class="form-field">
        <th scope="row" valign="top"><label for="lsttel">Telephone</label></th>
        <td><input type="text" name="lsttel" value="<?php echo $result['lsttel']; ?>" size="32" /></td>
        </tr>
        
        <tr class="form-field">
        <th scope="row" valign="top"><label for="lsttel1">Telephone 2</label></th>
        <td><input type="text" name="lsttel1" value="<?php echo $result['lsttel1']; ?>" size="32" /></td>
        </tr>
        
        <tr class="form-field">
        <th scope="row" valign="top"><label for="lstfax">Fax</label></th>
        <td><input type="text" name="lstfax" value="<?php echo $result['lstfax']; ?>" size="32" /></td>
        </tr>


    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Screenshot:</td>
      <td><input type="file" name="screenshot" id="screenshot" /></td>
    </tr>
<?php if ($this->show_screenshot($result['id']) != "False") { ?>
    <tr>
    	<td nowrap="nowrap" align="right">Current Screenshot:</td>
        <td><img src="<?php echo $this->show_screenshot($result['id']); ?>" /></td>
    </tr>
<?php } ?>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Enabled:</td>
      <?php if ($result['enabled'] == '1' || $_REQUEST['edit'] != "True") { $checked = 'checked="checked"'; } else { $checked = ""; } ?>
      <td><input type="checkbox" name="enabled" value="1" <?php echo $checked; ?> /></td>
    </tr>
<!--
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Sponsored:</td>
      <td>
      	Categories Page - 
      	<?php //if ($result['main_spon'] == '1') { $checked = 'checked="checked"'; } else { $checked = ""; }  ?>
      		<input type="checkbox" name="main_spon" value="1" <?php //echo $checked; ?> /><br />
        	Subcategories Page -
        <?php //if ($result['sub_spon'] == '1') { $checked = 'checked="checked"'; } else { $checked = ""; }  ?>
      		<input type="checkbox" name="sub_spon" value="1" <?php //echo $checked; ?> /><br />
        	Site Page - 
        <?php //if ($result['site_spon'] == '1') { $checked = 'checked="checked"'; } else { $checked = ""; }  ?>
      		<input type="checkbox" name="site_spon" value="1" <?php //echo $checked; ?> />
       </td>
    </tr>-->
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Submit" /></td>
    </tr>
  </table>
  <?php if($_REQUEST['edit'] == "True") { ?>
  <input type="hidden" name="update_listing" value="True" />
  <input type="hidden" name="lstid" value="<?php echo $result['lstid']; ?>" />
  <?php } else { ?>
  <input type="hidden" name="add_listing" value="True" />
  <?php } ?>
</form>

