<?php

$res = $this->get_sub_cats();

print <<<EOF
<div class="wrap">
<br class="clear">
    <div class="tablenav">
	<div class="tablenav-pages"></div>

<br class="clear">
	</div>

<br class="clear">

    <table class="widefat">
	<thead>
	    <tr>
            <th scope="col">ID</th>
	    <th scope="col">SubCategory Name</th>
	    <th scope="col">Description</th>
	    <th scope="col">Edit</th>
            <th scope="col">Delete</th>
	    </tr>
	</thead>
	<tbody id="the-list" class="list:cat">
EOF;



while ($result = mysql_fetch_assoc($res)) {
print "<tr id=cat-$result[id] class=alternate>
            <td>$result[id]</td>
	    <td><b>$result[name]</b></td>
            <td>$result[description]</td>
	    <td><a href='admin.php?page=wp-ttisbdir/cat.php&sub_cat_id=$result[id]&edit_sub=True&catid=$result[cat_id]'>Edit</a></td>
	    <td><a href='admin.php?page=wp-ttisbdir/cat.php&cat_id={$_REQUEST['cat_id']}&delete_sub_cat_id=$result[id]'>Delete</a></td>
	</tr>";


}
print <<<EOF

	</tbody>
    </table>
</form>

    <div class="tablenav">
    <div class="tablenav-pages"></div>
    <br class="clear">
    </div>

    <div class="wrap">
    <p><strong>Note:</strong><br>Deleting a category does not remove it from the database.Just change the deleted value from 0 to 1.</p>
    </div>
EOF;
?>