<?php
print <<<EOF
<div class="wrap">
<h2 title="viewlist">Deleted Listings</h2>

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
	    <th scope="col">Listing Name</th>
	    <th scope="col">Description</th>
            <th scope="col">Edit</th>
            <th scope="col">Restore</th>
	    </tr>
	</thead>
	<tbody id="the-list" class="list:cat">
EOF;

$res = $this->get_trash_listings();
$i = 1;
while ($result = mysql_fetch_assoc($res)) { 


print "<tr id=listing-$result[lstid] class=alternate>
            <td>$result[lstid]</td>
	    <td>$result[lstnm]</td>
            <td>$result[lstdsc]</td>
	    <td><a href=admin.php?page=wp-ttisbdir/listings.php&edit=True&site_id=$result[lstid]>Edit</a></td>
            <td><a href=admin.php?page=wp-ttisbdir/main.php&delete=True&id=$result[lstid]>Restore</a></td>
	</tr>"; 
$i++; }

print <<<EOF

	</tbody>
    </table>
</form>

    <div class="tablenav">
    <div class="tablenav-pages"></div><br class="clear">
    </div>
    <br class="clear">
    </div>

    <div class="wrap">
    <p><strong>Note:</strong><br>For support visit http://www.techteamis.gr</p>
    </div>
EOF;
?>