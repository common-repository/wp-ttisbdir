<?php

$res = $this->get_cats();

print <<<EOF
<div class="wrap">
<h2 title="viewlist">Manage WPBDIR Categories (<a href="#cat">add new</a>)</h2>

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
	    <th scope="col">Category Name</th>
	    <th scope="col">Description</th>
	    <th scope="col">Edit</th>
            <th scope="col">Delete</th>
            <th scope="col">Add/Edit Subcategories</th>
	    </tr>
	</thead>
	<tbody id="the-list" class="list:cat">
EOF;

while ($result = mysql_fetch_assoc($res)) {
print "<tr id=cat-$result[catid] class=alternate>
            <td>$result[catid]</td>
	    <td><a class=row-title href='admin.php?page=wp-ttisbdir/cat.php&catid=$result[catid]'><b>$result[catname]</b></td>
            <td>$result[catdesc]</td>
	    <td><a href='admin.php?page=wp-ttisbdir/cat.php&catid=$result[catid]&edit=True'>Edit</a></td>
	    <td><a href='admin.php?page=wp-ttisbdir/cat.php&delete_catid=$result[catid]'>Delete</a></td>
            <td><a href='admin.php?page=wp-ttisbdir/cat.php&catid=$result[catid]'>Add/Edit Subcategories</a></td>
	</tr>";
        
        
        if ($this->sub_cat_on == "1") { echo " - <a href='admin.php?page=wp-ttisbdir/cat.php&catid=$result[id]'><b>Subcategories</b></a>"; }
    	echo "<dd>$result[description]</dd>";
        }
        
print <<<EOF

	</tbody>
    </table>
</form>

    <div class="tablenav">
    <div class="tablenav-pages">
    </div>
    <br class="clear">
    </div>

    <div class="wrap">
    <p><strong>Note:</strong><br>Deleting a category does not remove it from the database.Just change the deleted value from 0 to 1.</p>
    </div>
EOF;
?>
