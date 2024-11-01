<?php

class wp_ttisbdir {
	
	var $db_pre = "";
	var $site_name = "";
	var $base_dir = "";
	var $url = '';
	var $anchor = "";
	var $wp_db_pre = "";
	var $image_base = "";
	var $col_num = "";
	var $dir_var = "";
	var $permalinks = false;
	
	function wp_ttisbdir(){
		global $wpdb;
		$this->wp_db_pre = $wpdb->prefix;
		$this->url = 'http://'.$_SERVER['HTTP_HOST'];
		$blog_name = $this->get_blog_name();
		$blog_url = $this->get_blog_url();
		$this->base_dir = ABSPATH."wp-content/plugins/wp-ttisbdir/";
		$this->image_base = "{$blog_url}/wp-content/plugins/wp-ttisbdir/";
		$this->db_pre = $wpdb->prefix."bdir_";
		$this->anchor = $blog_name;
		$this->site_name = $blog_name;
		$this->col_num = $this->get_option("col_nums");
		$dir_status = $this->show_dir();
		$dir_var = $this->key();
		if (get_option('wp_dir_permalinks') == '1'){
			$this->permalinks = true;
		} else {
			$this->permalinks = false;
		}
		$dir_status;
		if (preg_match("/$bdir_var/",$bdir_status)) {
			$status_bdir = "Running";
		} else {
			exit;
		}
	}
	

//Συνάρτηση για τον διαχωρισμών των tr	
	function OddOrEven($intNumber){
		if ($intNumber % 2 == 0 ){
			//your number is even
			return 0;
		} else {
			//your number is odd
			return 1;
		}
	}
	
//Συνάρτηση για εμφάνιση του ονόματος του blog
	function get_blog_name(){
		$sql = "SELECT * FROM ".$this->wp_db_pre."options WHERE option_name = 'blogname' ORDER BY option_id DESC";
		$res = mysql_query($sql);
		$result = mysql_fetch_assoc($res);
		$option = $result['option_value'];
		return $option;
	}

	
	function key() {
		$m = "www.techteamis.com";
		return $m;
	}



//==============================================
//===========ΣΥΝΑΡΤΗΣΕΙΣ OPTIONS=============
//==============================================
//Συνάρτηση για λήψη των ιδιοτήτων
	function get_option($option) {
		$sql = "SELECT $option FROM ".$this->db_pre."options LIMIT 1";
		$res = mysql_query($sql);
		$result = @mysql_fetch_assoc($res);
		$op = $result["$option"];
		return $op;
	}

//Συνάρτηση για εμφάνιση του ονόματος του καταλόγου
	function display_dir_name() {
		$display = $this->get_option("display_dir_name");
		return $display;
	}

//Συνάρτηση για εμφάνιση του link του blog	
	function get_blog_url() {
		$sql = "SELECT * FROM ".$this->wp_db_pre."options WHERE option_name = 'siteurl'";
		$res = mysql_query($sql);
		$result = mysql_fetch_assoc($res);
		$option = $result['option_value'];
		return $option;
	}
	
//Συνάρτηση για ενημέρωση του πίνακα options του καταλόγου
	function update_options(){
		$col_num = $_REQUEST['col_nums'];
		$dir_name = $_REQUEST['dir_name'];
		$dir_on = $_REQUEST['dir_on'];
		$display_dir_name = $_REQUEST['display_dir_name'];
		$sql = "UPDATE ".$this->db_pre."options SET 
						col_nums = '{$col_num}', 
						dir_on = '{$dir_on}', 
						dir_name = '{$dir_name}', 
						display_dir_name = '{$display_dir_name}'";
		mysql_query($sql);
		$this->display_error_sucsess("Options Updated!");
	}
	
//Συνάρτηση για την εφμάνιση της φόρμας των options του καταλόγου
	function show_option_form() {
		include($this->base_dir.'forms/options.php');
	}
	
	function get_options() {
		$sql = "SELECT * FROM ".$this->db_pre."options LIMIT 1";
		$res = mysql_query($sql);
		return $res;
	}
	
//==============================================
//========ΤΕΛΟΣ ΣΥΝΑΡΤΗΣΕΙΣ OPTIONS==========
//==============================================




//==============================================
//===========ΣΥΝΑΡΤΗΣΕΙΣ ΚΑΤΗΓΟΡΙΩΝ=============
//==============================================

//Συνάρτηση για την προσθήκη κατηγορίας	
	function add_cat() {
		$catname = $_POST['catname'];
		$catdesc = $_POST['catdesc'];
		$date = $this->mysql_timestamp();
		$enabled = $_POST['enabled'];
		$sql = "INSERT INTO ".$this->db_pre."cats (`catname`, `catdesc`, `date`, `enabled`) VALUES ('{$catname}', '{$catdesc}', '{$date}', '{$enabled}')";
		mysql_query($sql);
		$this->display_error_sucsess("Category Added");
	}
	
//Συνάρτηση για την ενημέρωση κατηγορίας		
	function update_cat() {
		$catname = $_POST['catname'];
		$catdesc = $_POST['catdesc'];
		$enabled = $_POST['enabled'];
		$date_mod = $this->mysql_timestamp();
		$catid = $_REQUEST['catid'];
		$sql = "UPDATE ".$this->db_pre."cats SET `catname` = '{$catname}', `catdesc` = '{$catdesc}', `enabled` = '{$enabled}', `date_mod` = '{$date_mod}' WHERE catid = '{$catid}'";
		mysql_query($sql);
		$this->display_error_sucsess("Category Updated");
	}
	
//Συνάρτηση για την διαγραφή κατηγοριών	
	function delete_cat() {
		$id = $_REQUEST['delete_catid'];
		$sql = "UPDATE ".$this->db_pre."cats SET deleted = '1' WHERE catid = '{$id}'";
		mysql_query($sql);
		$this->display_error_sucsess("Category Deleted");
	}
	
//Συνάρτηση για query στην βάση των κατηγοριών	
	function get_cats() {
		$sql = "SELECT * FROM ".$this->db_pre."cats WHERE deleted = '0' ORDER BY `catname`";
		$res = mysql_query($sql);
		return $res;
	}

//Συνάρτηση για query στην βάση των κατηγοριών	
	function get_cats_without_subs() {
		$sql = "SELECT * FROM ".$this->db_pre."cats WHERE deleted = '0' AND catpar = '0' ORDER BY `catname`";
		$res = mysql_query($sql);
		return $res;
	}
	
//Συνάρτηση για εμφάνιση των κατηγοριών		
	function show_cats() {
		include($this->base_dir.'lists/cat.php');
	}

	function cats_array() {
		$res = $this->get_cats();
		
		while ($result = mysql_fetch_assoc($res)){
			$cats[$result['catid']] = $result['catname']; 
		}
		return $cats;
	}
	
//Συνάρτηση για εμφάνιση της φόρμας προσθήκης και επεξεργασίας κατηγορίας
	function show_cat_form() {
		include($this->base_dir.'forms/cat.php');
	}

//Συνάρτηση για εμφάνιση πλροφοριών κατηγορίας	
	function get_cat_info() {
		$sql = "SELECT * FROM ".$this->db_pre."cats WHERE catid = '{$_REQUEST['catid']}' AND deleted = '0'";
		$res = mysql_query($sql);
		return $res;
	}
	
//Συνάρτηση για εμφάνιση των κατηγοριών σε μορφή Combo BOX	

	function get_cats_for_options($selected_id = "") {
		$sql = "SELECT catid, `catname` FROM ".$this->db_pre."cats WHERE enabled = '1' AND deleted = '0' ORDER BY `catname`";
		$res = mysql_query($sql);
		$option = "";
		while ($result = mysql_fetch_assoc($res)){
			if ($selected_id == $result['catid']) { $selected = 'selected="selected"'; }
			$option .= "<option value='$result[catid],-1' $selected>$result[catname]</option>";
			$selected = '';
			
			$sqla = "SELECT `name`, id FROM ".$this->db_pre."sub_cat WHERE enabled = '1' AND deleted = '0' AND cat_id = '{$result['catid']}' ORDER BY `name`";
			$resa = mysql_query($sqla);
			while ($resulta = mysql_fetch_assoc($resa)) {
				if ($selected_id == $resulta['id']) { $selected = 'selected="selected"'; }
				$option .= "<option value='$result[catid],$resulta[id]' $selected>$result[catname] - $resulta[name]</option>";
				$selected = '';
			}
		}
		return $option;
		
	}

	function get_sub_cat_info() {
		$sql = "SELECT * FROM ".$this->db_pre."sub_cat WHERE id = '{$_REQUEST['sub_cat_id']}' AND deleted = '0'";
		$res = mysql_query($sql);
		return $res;
	}
	
	function add_sub_cat() {
		$name = $_POST['name'];
		$description = $_POST['description'];
		$enabled = $_POST['enabled'];
		$date = $this->mysql_timestamp();
		$cat_id = $_REQUEST['catid'];
		$sql = "INSERT INTO ".$this->db_pre."sub_cat (`name`, `description`, `enabled`, `date`, `cat_id`) VALUES ('{$name}', '{$description}', '{$enabled}', '{$date}', '{$cat_id}')";
		mysql_query($sql);
		$this->display_error_sucsess("Sub Category Added");
	}
	
		function update_sub_cat() {
		$name = $_POST['name'];
		$description = $_POST['description'];
		$enabled = $_POST['enabled'];
		$date = $this->mysql_timestamp();
		$id = $_REQUEST['sub_cat_id'];
		$sql = "UPDATE ".$this->db_pre."sub_cat SET `name` = '{$name}', `description` = '{$description}', `enabled` = '{$enabled}', `date_mod` = '{$date}' WHERE id = '{$id}'";
		mysql_query($sql);
		$this->display_error_sucsess("Sub Category Updated");
	}
	
	function delete_sub_cat() {
		$id = $_REQUEST['delete_sub_cat_id'];
		$sql = "UPDATE ".$this->db_pre."sub_cat SET deleted = '1' WHERE id = '{$id}'";
		mysql_query($sql);
		$this->display_error_sucsess("Sub Category Deleted");
	}
	function get_sub_cats() {
		$id = $_REQUEST['catid'];
		$sql = "SELECT * FROM ".$this->db_pre."sub_cat WHERE cat_id = '{$id}' AND deleted = '0' ORDER BY `name`";
		$res = mysql_query($sql);
		return $res;
	}
	
	function sub_cats_array() {
		$res = $this->get_all_sub_cats();
		
		while ($result = mysql_fetch_assoc($res)){
			$cats[$result['id']] = $result['name']; 
		}
		return $cats;
	}
	
	function show_sub_cats() {
		include($this->base_dir.'lists/sub_cat.php');
	}
	
	function show_sub_cat_form() {
		include($this->base_dir.'forms/sub_cat.php');
	}
//==============================================
//========ΤΕΛΟΣ ΣΥΝΑΡΤΗΣΕΙΣ ΚΑΤΗΓΟΡΙΩΝ==========
//==============================================	
	
	
	function breadcrumb() {
		$base = $this->format_base();
		// Directory Home - Category - Subcategory
		$m = "<a href='$base'>Home</a>";
		if ($_REQUEST['wp_dir'] == "sub"){
			$res = $this->get_cat_info();
			$result = mysql_fetch_assoc($res);
			if ($this->permalinks){
				$cat = $this->format_for_url($result['catname']);
				$m.= " - <a href='{$base}wpdir-sub/cat-$result[catid]/$cat/'>$result[catname]</a>";
			} else {
				$m .= " - <a href='{$base}&cat_id=$result[catid]&wp_dir=sub'>$result[catname]</a>";
			}
		}
		if ($_REQUEST['wp_dir'] == "listings"){
			$res = $this->get_sub_cat_info();
			$result = mysql_fetch_assoc($res);
			$_REQUEST['catid'] = $result['cat_id'];
			$cat_res = $this->get_cat_info();
			$cat_result = mysql_fetch_assoc($cat_res);
			
			if ($this->permalinks){
				$cat = $this->format_for_url($cat_result['catname']);
				$m.= " - <a href='{$base}wpdir-sub/cat-$cat_result[catid]/$cat/'>$cat_result[catname]</a>";
			} else {
				$m .= " - <a href='{$base}&cat_id=$cat_result[catid]&wp_dir=sub'>$cat_result[catname]</a>";
			}
			if ($this->permalinks){
				$cat = $this->format_for_url($cat_result['catname']);
				$sub_cat = $this->format_for_url($result['name']);
				$m.= " - <a href='{$base}wpdir-listings/cat-$result[id]/$cat/$sub_cat/'>$result[name]</a>";
			} else {
				$m .= " - <a href='{$base}&wp_dir=listings&sub_cat_id=$result[id]'>$result[name]</a>";
			}
		}
		return $m;
	}


//==============================================
//==============ΣΥΝΑΡΤΗΣΕΙΣ LISTINGS============
//==============================================	


	
//Συνάρτηση για το query των εταιρειών που περιμένουν εγγριση
	function get_nondisplayed_listings() {
		$sql = "SELECT * FROM ".$this->db_pre."listings WHERE deleted = '0' AND enabled = '0'";
		$res = mysql_query($sql);
		return $res;
	echo $res;
	echo $sql;
	}
	
//Συνάρτηση για την εμφάνιση των εταιρειών που περιμένουν έγγριση	
	function show_ap_listings() {
		include($this->base_dir.'lists/ap_listings.php');
	}

//Συνάρτηση για το query των εταιρειών που βρίσκονται στον κάδο ανακύκλωσης
	function get_trash_listings() {
		$sql = "SELECT * FROM ".$this->db_pre."listings WHERE deleted = '1' AND enabled = '0'";
		$res = mysql_query($sql);
		return $res;
	echo $res;
	echo $sql;
	}

//Συνάρτηση για το query των εταιρειών που βρίσκονται στον κάδο ανακύκλωσης
	function get_listings() {
		$sql = "SELECT * FROM ".$this->db_pre."listings WHERE deleted = '0' AND enabled = '1'";
		$res = mysql_query($sql);
		return $res;
	echo $res;
	echo $sql;
	}

//Συνάρτηση για την εμφάνιση των εταιρειών που βρίσκονται στον κάδο ανακύκλωσης	
	function show_trash_listings() {
		include($this->base_dir.'lists/trash_listings.php');
	}
	
//Συνάρτηση για την ενεργοποίηση εταιρείας
	function approve_listing() {
		$id = $_REQUEST['id'];
		$sql = "UPDATE ".$this->db_pre."listings SET enabled = '1' WHERE lstid = '{$id}'";
		mysql_query($sql);
		$this->display_error_sucsess("Listing Approved");
	}

//Συνάρτηση για την εμφάνιση των Listings	
	function show_listings() {
		include($this->base_dir.'lists/listing.php');
	}

//Συνάρτηση για την εμφάνιση των Διαφημιζόμενων Listings	
	function show_sponsored_listings() {
		include($this->base_dir.'lists/spon_listings.php');
	}
	
//Συνάρτηση για την εμφάνιση της φόρμας των Listings	
	function show_listing_form() {
		include($this->base_dir.'forms/listing.php');
	}
	
//Συνάρτηση για την προσθήκη Listing	
	function add_listing($show_error="True") {
		$lstnm = $_POST['lstnm'];
		
		$cats = $_POST['lstcat'];
		$cats = explode(",",$cats);	
		$lstcat = $cats[0];
		$sub_cat = $cats[1];

		$lstdsc = $_POST['lstdsc'];
		$lstlnk = $_POST['lstlnk'];
		$lstlnk = str_replace("http://","",$lstlnk);
		$lstmail = $_POST['lstmail'];
		$lstcnt = $_POST['lstcnt'];
		$lstaddr = $_POST['lstaddr'];
		$lststate = $_POST['lststate'];
		$lstcity = $_POST['lstcity'];
		$lsttel = $_POST['lsttel'];
		$lsttel1 = $_POST['lsttel1'];
		$lstfax = $_POST['lstfax'];
		$date = $this->mysql_timestamp();
		$enabled = $_POST['enabled'];	
		//$main_spon = $_REQUEST['main_spon'];
		//$sub_spon = $_REQUEST['sub_spon'];
		//$site_spon = $_REQUEST['site_spon'];
		$main_spon = "0";
		$sub_spon = "0";
		$site_spon = "0";
		
			$sql = "	INSERT INTO 
						".$this->db_pre."listings (
		

		`lstnm`,
		`lstcat`,
		`sub_cat`,
		`lstdsc`,
		`lstlnk`,
		`lstmail`,
		`lstcnt`,
		`lstaddr`,
		`lststate`,
		`lstcity`,
		`lsttel`,
		`lsttel1`,
		`lstfax`,
		`date`,
		`enabled`,
		`main_spon`,
		`sub_spon`,
		`site_spon`
		
		) VALUES (
		'{$lstnm}',
		'{$lstcat}',
		'{$sub_cat}',
		'{$lstdsc}',
		'{$lstlnk}',
		'{$lstmail}',
		'{$lstcnt}',
		'{$lstaddr}',
		'{$lststate}',
		'{$lstcity}',
		'{$lsttel}',
		'{$lsttel1}',
		'{$lstfax}',
		'{$date}',
		'{$enabled}',
		'{$main_spon}',
		'{$sub_spon}',
		'{$site_spon}')";
		
		mysql_query($sql);
		if ($_FILES['screenshot']['tmp_name'] != ""){
			$id = mysql_insert_id();
			$this->upload_screen_shot($id);
		}
		if ($show_error == "True"){
			$this->display_error_sucsess("Site Added");
		} else {
			echo "Your listing will be approved soon! ";
			//echo $sql;
		}
	}
	
//Συνάρτηση για την ενημέρωση Listing
	function update_listing($show_error="true") {
		$id = $_REQUEST['lstid'];
		$lstnm = $_POST['lstnm'];
		$cats = $_POST['lstcat'];
		$cats = explode(",",$cats);	
		$lstcat = $cats[0];
		$sub_cat = $cats[1];

		$lstdsc = $_POST['lstdsc'];
		$lstlnk = $_POST['lstlnk'];
		$lstlnk = str_replace("http://","",$lstlnk);
		$lstmail = $_POST['lstmail'];
		$lstcnt = $_POST['lstcnt'];
		$lstaddr = $_POST['lstaddr'];
		$lststate = $_POST['lststate'];
		$lstcity = $_POST['lstcity'];
		$lsttel = $_POST['lsttel'];
		$lsttel1 = $_POST['lsttel1'];
		$lstfax = $_POST['lstfax'];
		$date_mod = $this->mysql_timestamp();
		$enabled = $_POST['enabled'];
		//$main_spon = $_REQUEST['main_spon'];
		//$sub_spon = $_REQUEST['sub_spon'];
		//$site_spon = $_REQUEST['site_spon'];
		$sql = "	UPDATE  
						".$this->db_pre."listings SET
						
		lstnm = '{$lstnm}',
		lstcat = '{$lstcat}',
		sub_cat = '{$sub_cat}',
		lstdsc = '{$lstdsc}',
		lstlnk = '{$lstlnk}',
		lstmail = '{$lstmail}',
		lstcnt = '{$lstcnt}',
		lstaddr = '{$lstaddr}',
		lststate = '{$lststate}',
		lstcity = '{$lstcity}',
		lsttel = '{$lsttel}',
		lsttel1 = '{$lsttel1}',
		lstfax = '{$lstfax}',
		date_mod = '{$date_mod}',
		enabled = '{$enabled}'
		WHERE lstid = '{$id}'";
		mysql_query($sql);
		if ($_FILES['screenshot']['tmp_name'] != ""){
			$this->upload_screen_shot($id);
		}

		if ($show_error == "True"){
			$this->display_error_sucsess("Listing Updated");
		} else {
			echo "Your site will be approved soon! ";
			echo $sql;
		}


	}

//Συνάρτηση για την διαγραφή εταιρειών		
	function delete_listing() {
		$id = $_REQUEST['id'];
		$sql = "UPDATE ".$this->db_pre."listings SET deleted = '1' WHERE lstid = '{$id}'";
		mysql_query($sql);
		$this->display_error_sucsess("Listing Deleted Succesfully");
	}
	
	
	function display_error_sucsess($message){
		?>
			<div style="background-color:rgb(207, 235, 247);" id="message" class="updated fade">
						<?php if (is_array($message)) { ?>
							<?php foreach ($message as $value) { ?>
								<p class="GlobalErr"><?php echo $value;?></p>
							<?php } ?>
						<?php } else { ?>
							<p class="GlobalErr"><?php echo $message;?></p>	
						<?php } ?>
			</div><?php
	}
	
	function mysql_timestamp(){
		$date = date("Y-m-d H:i:s");
		return $date;
	}
	

	

	

	
	function get_listings_for_cat(){
		$cid = $_REQUEST['catid'];
		$sql = "SELECT * FROM ".$this->db_pre."listings WHERE lstcat = '{$cid}' AND deleted = '0' AND enabled = '1' ORDER BY `lstnm` ";
		$res = mysql_query($sql);
		return $res;
	}
	
	function get_listings_for_sub_cat() {
		$sub_cat = $_REQUEST['sub_cat_id'];
		$sql = "SELECT * FROM ".$this->db_pre."listings WHERE sub_cat = '{$sub_cat}' AND enabled = '1' AND deleted = '0' ORDER BY `lstnm`";
		$res = mysql_query($sql);
		return $res;
	}
	
	function get_unapproved_sites() {
		$sql = "SELECT id FROM {$this->db_pre}sites WHERE deleted = '0' AND enabled = '0'";
		$res = mysql_query($sql);
		return $res;
	}
	
//	function get_listings($details = "False") {
//			$sql = "	SELECT 
//							{$this->db_pre}listings.*
//						FROM 
//							{$this->db_pre}listings
//						WHERE 
//							{$this->db_pre}listings.deleted = 0";
//		$search_array = array("lstnm","lstdesc"); 	 	 	 	 	
//		$sql .= $this->order_limit_sql("listings","lstnm DESC", $search_array);
//		$res = mysql_query($sql);
//		return $res;	
//	}
	
	function get_count_sites() {
		$sql = "SELECT id FROM {$this->db_pre}sites WHERE enabled = '1' AND deleted = '0'";
		$res = mysql_query($sql);
		return $res;
	}
	
	function get_stats() {
		$cat_res = $this->get_cats();
		$cats = mysql_num_rows($cat_res);
		$sub_res = $this->get_all_sub_cats();
		$subs = mysql_num_rows($sub_res);
		$site_res = $this->get_count_sites();
		$sites = mysql_num_rows($site_res);
		$un_res = $this->get_unapproved_sites();
		$un = mysql_num_rows($un_res);
		$return = array($cats, $subs, $sites,$un);
		return $return;
	}
	
	function get_all_sub_cats() {
		$sql = "SELECT id, name FROM ".$this->db_pre."sub_cat WHERE deleted = '0' AND enabled = '1'";
		$res = mysql_query($sql);
		return $res;
	}
	
//Συνάρτηση
	function get_listing_info() {
		$id = $_REQUEST['lstid'];
		$sql = "SELECT * FROM ".$this->db_pre."listings WHERE lstid = '{$id}' AND deleted = '0'";
		$res = mysql_query($sql);
		return $res;
	}
	
	
	
	function show_search_form() {
		include($this->base_dir.'forms/search.php');
	}
	
	function order_limit_sql($table,$colum, $search_array=Array(), $pre = "True") {
		if ($pre != "True"){
			$db_pre = $this->db_pre;
			$this->db_pre = "";
		}
		if ( $_REQUEST['search_term'] != "" ){ 
				$ai = 1;
			foreach ($search_array as $value) {
				if ($ai == 1) { $op = "AND"; } else { $op = "OR"; }
				$search_term = $_REQUEST['search_term'];
				$sql .= " 	$op {$this->db_pre}$table.$value LIKE '%$search_term%'";
				$ai++;
			}
		}
		if ( isset ( $_REQUEST['order'] ) && $_REQUEST['order'] != "") {
				$order = "$_REQUEST[order]";
				$order_direction = $_REQUEST['order_direction'];
			$sql .= "	ORDER BY	
							$order $order_direction";
		} else {
			$sql .= "	ORDER BY 
							{$this->db_pre}$table.$colum ";
			}
		if (isset($_REQUEST['limit_start']) && $_REQUEST['limit_start'] != ""){
				$limit_start = $_REQUEST['limit_start'];
			$sql .= " 	LIMIT $limit_start,20 ";
			} else {
			$sql .= " LIMIT 0,20 ";
		}
		if ($pre != "True"){
			$this->db_pre = $db_pre;
		}
		//echo $sql;
		return $sql;
	}
	
	function is_odd($number) {
  		return $number & 1; // 0 = even, 1 = odd
	}
	
	function optional_query() {
		$query_string = "&page_id=$_REQUEST[page_id]";
		return $query_string;
	}
	
	function make_order_links ($link_name, $sql_name, $default_image="False",$op_query="")
	{
		$arraow = "arrow";
		$oq = $this->optional_query();
		$order_link = "admin.php?page=$_REQUEST[page]&$op_query{$oq}";
		
		if ($_REQUEST['order'] == $sql_name || ($default_image == "True" && !isset($_REQUEST['order'])))
		{ 
			$arraow = "arrow_down"; 
		}
		if (isset($_REQUEST['limit_start']))
		{
			$order_link .= "&limit_start=$_REQUEST[limit_start]";
		}
		if (isset($_REQUEST['user_id'])) {
			$order_link .= "&user_id=$_REQUEST[user_id]";
		}
		if (isset($_REQUEST['order']) && $_REQUEST['order'] == $sql_name && !isset($_REQUEST['order_direction']))
		{
			$order_link .= "&order_direction=DESC";
			$arraow = "arrow_down";
		}
		if (isset($_REQUEST['order_direction']) && $_REQUEST['order'] == $sql_name) 
		{ 
			$arraow = "arrow_up"; 
		}
		if (isset($_REQUEST['search_term']) && $_REQUEST['search_term'] != "")
		{
			$order_link .= "&search_term=$_REQUEST[search_term]";
		}
		$order_link .= "&order=$sql_name";
		
		echo "<a href='$order_link'>$link_name</a> <img src='{$this->image_base}images/$arraow.gif'>";
		
	}
	
	function make_paging_links($table_name, $table_colum, $pages="False", $offset=20)
	{
		//url base
		$oq = $this->optional_query();
		$url_base = "admin.php?page=$_REQUEST[page]{$oq}";
		
		if (isset($_REQUEST['order_direction'])){
			$url_base .= "&order_direction=DESC";
		}
		
		if (isset($_REQUEST['order'])){
			$url_base .= "&order=$_REQUEST[order]";
		}
		if (isset($_REQUEST['search_term'])){
			$url_base .= "&search_term=$_REQUEST[search_term]";
		}
		
		//start link
		$start = "";
		if ($_REQUEST['limit_start'] > 5 )
		{
			$url_start = $url_base.'&limit_start=0';
			$start .= "<a href='$url_start' border='0'><img src='{$this->image_base}images/start.gif' border='0'>Start</a>";
		} else {
			$start .= "<img src='{$this->image_base}images/start_off.gif' border='0'><font color='grey'>Start</font>";
		}
		
		//prvious link
		if (isset($_REQUEST['limit_start']) && ($_REQUEST['limit_start'] != "" && $_REQUEST['limit_start'] != 0)){
			$previous_number = $_REQUEST['limit_start'] - $offset;
			$url_previous = $url_base.'&limit_start='.$previous_number;
			$previous = "<a href='$url_previous' border='0'><img src='{$this->image_base}images/previous.gif' border='0'>Previous</a>";
		}else {
			$previous = "<img src='{$this->image_base}images/previous_off.gif' border='0'><font color='grey'>Previous</font>";
		}
		
		//paging numbers
		$sql = "SELECT $table_colum FROM $table_name WHERE deleted = 0";
		if ($pages != "False"){
			$sql .= " AND page_id = '{$_REQUEST['page_id']}'";
		}
		//echo "<br />$sql";
		$count = mysql_query($sql);
		$num_rows_count = mysql_num_rows($count);
		//echo $num_rows_count;
		
		$limit_start = $_REQUEST['limit_start'];
		$limit_start1 = ($limit_start + 1);
		$num_rows = ($limit_start + 20);
		$paging = "&nbsp;&nbsp;<span class='pageNumbers'>( $limit_start1  - $num_rows  of  $num_rows_count )</span>&nbsp;&nbsp;";

		//next link
		if (($_REQUEST['limit_start']+20 < $num_rows_count && $num_rows_count > $offset) || (!isset($_REQUEST['limit_start']) && $_REQUEST['limit_start'] < $num_rows_count && $num_rows_count > $offset)){
			$next_number = $_REQUEST['limit_start'] + $offset;
			$url_next = $url_base.'&limit_start='.$next_number;
			$next = "<a href='$url_next' border='0'>Next</a><img src='{$this->image_base}images/next.gif' border='0'>";
		}else {
			$next = "<font color='grey'>Next</font><img src='{$this->image_base}images/next_off.gif' border='0'>";
		}
		
		//last link
		if (($_REQUEST['limit_start']+20 < $num_rows_count && $num_rows_count > $offset) || (!isset($_REQUEST['limit_start']) && $_REQUEST['limit_start'] < $num_rows_count && $num_rows_count > $offset)){
			$length = strlen($num_rows_count);
			$total_length = strlen($num_rows_count);
			//$number = str_split($num_rows_count);
			$number = "";
			for($j=0;$j<$total_length;$j++){
				$thisLetter = substr($num_rows_count, $j, 1); 
				$number.="$thisLetter";
				//echo $number;
			}
			if ($length == 2){ 
				if ($this->OddOrEven($number[0]) == 0){
					$last_number = $number[0]."0";
					//echo "$last_number<br />";
				} else {
					$last_number = ($number[0]-1)."0";
					//echo "$last_number<br />";
				}
			} elseif ($length == 3) {
				if ($this->OddOrEven($number[1]) == 0){
					$last_number = $number[0].$number[1]."0";
					//echo "$last_number<br />";
				} else {
					$last_number = $number[0].($number[1]-1)."0";
					//echo "$last_number<br />";
				}
			}
			//$last_number = round($num_rows_count,$offset);
			$url_last = $url_base.'&limit_start='.$last_number;
			$last = "<a href='$url_last' border='0'>Last<img src='{$this->image_base}images/end.gif' border='0'></a>";
		}else {
			$last = "<font color='grey'>Last</font><img src='{$this->image_base}images/end_off.gif' border='0'>";
		}
	
		//echo $start; echo " $previous"; echo $paging; echo $next; echo " $last";
		$return = "$start $previous $paging $next $last";
		return $return;

	}
	
	function format_date($date){
		$year = substr("$date", 0, 4);
		$month = substr("$date", 5, 2);
		$day = substr("$date", 8, 2);
		$hour = substr("$date", 11, 2);
		$min = substr("$date", 14, 2);
		$sec = substr("$date", 17, 2);
		
		$formatted_date = date ('d', mktime (0, 0, 0, $month, $day, $year));
		$formatted_date .= " ";
		$formatted_date .= date ('M', mktime (0, 0, 0, $month, $day, $year));
		$formatted_date .= ", ";
		$formatted_date .= date ('Y', mktime (0, 0, 0, $month, $day, $year));
		$formatted_date .= " At {$hour}:{$min}";
		return $formatted_date;
	}
	
//Συνάρτηση	
	function show_categories() {
		$base = $this->format_base();
		$res = $this->get_cats();
		$m .= "<style type=\"text/css\"> .dir_category { font-size:15px; color:#aaaaaa; } </style>";
		$m .= "<br /><br /><table width='100%' cellspacing='0'><tr>";
		$i=1;
		$width = 100 / $this->get_option("col_nums");
		$width = round($width, 0);
		while ($result = mysql_fetch_assoc($res)) {
			$num = $this->count_listings_in_cat($result['catid']);

// Αν ενεργοποιήσουμε την παρακάτω γραμή θα εμφανίζονται και οι υποκατηγορίες στην αρχική σελίδα
			//$sub_cat = $this->get_three_sub_cats($result['catid'], $result['name']);
			$m .= "<td width='$width%' align='left' valign='top' height='30'>";
			if ($this->permalinks){
				$cat = $this->format_for_url($result['catname']);
				$m .= "<a href='{$base}wpdir-sub/cat-$result[catid]/$cat/'><span class='dir_category'><img border='0'src='{$image_base}/wp-content/plugins/wp-ttisbdir/images/catgif.gif'> $result[catname] ($num) $sub_cat</span></a></td>";
			} else {
				$m .= "<a href='{$base}&wp_dir=sub&catid=$result[catid]'><span class='dir_category'>$result[catname] ($num) $sub_cat</span></a></td>";
			}

			if ($i == $this->col_num){ $i = 0; $m.= "</tr><tr>"; }
			$i++;
		}
		$m .= "</tr></table>";
//		$m .= $this->show_sponsored_listings_front("main_spon");
		$m .= $this->show_dir();
		return $m;
	}
	
//Συνάρτηση	
	function get_three_sub_cats($cat, $cat_name="") {
		$base = $this->format_base();
		$m = "";
		$limit = $this->get_option("sub_cat_num");
		$sql = "SELECT name, id FROM ".$this->db_pre."sub_cat WHERE deleted = '0' AND enabled = '1' AND cat_id = '{$cat}'";
		$res = mysql_query($sql);
		while ($result = mysql_fetch_assoc($res)){
			$num = $this->count_listings_in_sub_cat($result['id']);
			if ($this->permalinks){
				$cat_name = $this->format_for_url($cat_name); // Otan to allaksa se cat epexe
				$sub_cat = $this->format_for_url($result['name']);
				$m .= "<a href='{$base}wpdir-listings/cat-$result[id]/$cat_name/$sub_cat/'>$result[name]</a>($num) ";
			} else {
				$m .= "<a href='{$base}&wp_dir=listings&sub_cat_id=$result[id]'>$result[name]</a>($num) ";
			}
			$show_dots = "True";
		}
		if ($show_dots == "True") {
			$m .= "...";
		}
		return $m;
	}
	
	function count_listings_in_cat($lstcat) {
		$sql = "SELECT lstid FROM ".$this->db_pre."listings WHERE deleted = '0' AND enabled = '1' AND lstcat = '{$lstcat}'";
		$res = mysql_query($sql);
		$num = mysql_num_rows($res);
		return $num;
	}
	
	function count_listings_in_sub_cat($lstcat) {
		$sql = "SELECT lstid FROM ".$this->db_pre."listings WHERE deleted = '0' AND enabled = '1' AND sub_cat = '{$lstcat}'";
		$res = mysql_query($sql);
		$num = mysql_num_rows($res);
		return $num;
	}
	

//Συνάρτηση για την εμφάνιση των υποκατηγοριών στην σελίδα του Directory	
	function show_sub_categories() {
		$res = $this->get_cat_info();
		$result = mysql_fetch_assoc($res);
		$base = $this->format_base();
		$res = $this->get_sub_cats();
		if (mysql_num_rows($res) > 0) {
		//	$m = "<h3>Subcategories for $result[catname]</h3>";
		}
		$cat = $this->format_for_url($result['catname']);
		$m .= "<table width='100%'><tr>";
		$i=1;
		$width = 100 / $this->get_option("col_nums");
		$width = round($width, 0);
		while ($result = mysql_fetch_assoc($res)) {
			$num = $this->count_listings_in_sub_cat($result['id']);
			$m .= "<style type=\"text/css\"> .dir_subcategory { font-size:15px; color:#aaaaaa; } </style>";
			$m .= "<td width='$width%' align='top' valign='left' height='25'>";
			if ($this->permalinks){
				$sub_cat = $this->format_for_url($result['name']);
				
				$m .= "<a href='{$base}wpdir-listings/cat-$result[id]/$cat/$sub_cat/'><span class='dir_subcategory'><img border='0'src='{$image_base}/wp-content/plugins/wp-ttisbdir/images/catgif.gif'> $result[name] ($num)</a></span></td>";
			} else {
				$m .= "<a href='{$base}&wp_dir=listings&sub_cat_id=$result[id]'><span class='dir_subcategory'><img border='0'src='{$image_base}/wp-content/plugins/wp-ttisbdir/images/catgif.gif'> $result[name] ($num)</a></span></td>";
			}
			//$m .= " ($num)</td>";
			if ($i == $this->col_num){ $i = 0; $m.= "</tr><tr>"; }
			$i++;
		}
		$m .= "</tr></table>";
		//$m .= $this->show_sponsored_listings_front("sub_spon");
		return $m;
	}
	
	function show_dir() {
		$m = "";
		$m .= "<div align='right'><a href='http://www.techteamis.gr' title='Wordpress Business Directory Plugin'>WP-ttisbdir</a></div>";
		return $m;
	}
	
	function show_listings_front() {
		$m = "";
		$res = $this->get_sub_cat_info();
		$result = mysql_fetch_assoc($res);
		//$m .= $this->show_sponsored_listings_front("site_spon");
		//$m .= "<h3>Companies for $result[name]</h3>";
		$m .= "<p>$result[description]</p><br />";
		$res = $this->get_listings_for_sub_cat();
		
		$m .= "<style type=\"text/css\">
		.dir_grey { font-size:12px; color:#aaaaaa; }
		.dir_tas { font-size:12px; color:#719bc7; }
		.bdir_entry { margin-top: 0pt; margin-right: 0pt; margin-bottom: 20px; margin-left: 0pt;
		padding-top: 0pt; padding-right: 20px; padding-bottom: 20px; padding-left: 0pt;
		border-bottom-width: 1px;border-bottom-style: solid;border-bottom-color: #cccccc;}
		
		.bdirtitle { float: left; width: 100%; margin-bottom: 5px; padding-top: 5px; padding-bottom: 5px; background-color: transparent;
		background-image: url($base/wp-content/plugins/wp-ttisbdir/images/bg_body.gif); background-repeat: repeat; background-attachment: scroll; background-position: 0pt 0pt;
		border-bottom: 1px solid #cccccc; }
		</style>";
		while ($result = mysql_fetch_assoc($res)) {

			if ($this->show_screenshot($result['id']) != "False") { 
				$img = $this->show_screenshot($result[id]);
  				$m .= "<img src=\"$img\" style=\"float:left; margin-bottom:5px;\" />";
   			}
			$m .="<div class=bdirtitle><a href='http://$result[lstlnk]' target='_blank' title='$result[lstnm]'>$result[lstnm]</a></div>";
			$m .= "<div class=bdir_entry><p>$result[lstdsc]<br />";
			$m .="<span class='dir_tas'>URL:</span> <span class='dir_grey'><a href='http://$result[lstlnk]' target='_blank' title='$result[lstnm]'>http://$result[lstnm]</a></span> <br />";
			$m .="<span class='dir_tas'>Region:</span><span class='dir_grey'> $result[lststate]>$result[lstcity]>$result[lstaddr]</span></p></div>";
		}
		$m .= "<p style='clear:both;'></p>";
		$m .= $this->show_dir();
		return $m;
	}
	
	function show_listings_for_cat_front() {
		$m = "";
		$res = $this->get_cat_info();
		$result = mysql_fetch_assoc($res);	

		$m .= "<h2>Companies for $result[catname]</h2>";
		$m .= "<p style='clear:both;'>";
		$m .= "$result[catdesc]</p><br />";
		$res = $this->get_listings_for_cat();
		
		$m .= "<style type=\"text/css\"> .dir_grey { font-size:10px; color:#CCCCCC; } </style>";
		while ($result = mysql_fetch_assoc($res)) {
			$m .= "<p style='clear:both;'>";
			if ($this->show_screenshot($result['id']) != "False") { 
				$img = $this->show_screenshot($result[id]);
  				$m .= "<img src=\"$img\" style=\"float:left; margin-bottom:5px;\" />";
   			}
			$m.="<a href='http://$result[lstlnk]' target='_blank' title='$result[lstnm]'>$result[lstnm]</a> - <span class='dir_grey'>$result[lstlnk]</span> <br />";
			$m .= "$result[lstdsc]</p>";
		}
		$m .= "<p style='clear:both;'></p>";
		$m .= $this->show_dir();
		return $m;
	}
	
	function show_search_results() {
		$res = $this->get_search_results();
		$m = "";
		$m .= "<h2>Search Results</h2>";
		$m .= "<style type=\"text/css\"> .dir_grey { font-size:11px; color:#CCCCCC; } </style>";
		while ($result = mysql_fetch_assoc($res)) {
			$m .= "<a href='http://$result[link]' target='_blank' title='$result[title]'>$result[title]</a> - <span class='dir_grey'>$result[link]</span> <br />";
			if ($this->show_screenshot($result['id']) != "False") { 
  				$m .= "<img src=\"$this->show_screenshot($result[id])\" />";
   			}
			$m .= "$result[description]<br /><br />";
			$r="r";
		}
		if ($r != "r"){
			$m .= "Didn't find any results for \"$_REQUEST[search_term]\"";
		}
		$m .= $this->show_dir();
		
		return $m;
	}


	function get_sponsored_listings($spon_type){
		$sql = "SELECT * FROM ".$this->db_pre."listings WHERE deleted = '0' AND enabled = '1' AND $spon_type = '1'";
		if ($spon_type == "sub_spon" && $_REQUEST['page'] != "wp-ttisbdir/spon_listings.php") {
			$sql .= " AND cat_id = '{$_REQUEST['cat_id']}' ";
		}
		if ($spon_type == "site_spon" && $_REQUEST['page'] != "wp-ttisbdir/spon_listings.php"){
			$sql .= " AND sub_cat = '{$_REQUEST['sub_cat_id']}' ";
		}
		$res = mysql_query($sql);
		return $res;
	}
	
	function show_sponsored_listings_front($spon_type){
		$m = "";
		$res = $this->get_sponsored_listings($spon_type);
		if (mysql_num_rows($res) > 0){
			$m .= "<h2>Sponsored Sites</h2>";
		}
		$m .= "<style type=\"text/css\"> .dir_grey { font-size:11px; color:#CCCCCC; } </style>";
		while ($result = mysql_fetch_assoc($res)) {
			$m .= "<p style='clear:both;'>";
			if ($this->show_screenshot($result['id']) != "False") { 
				$img = $this->show_screenshot($result['id']);
  				$m .= "<img src=\"$img\" style=\"float:left; margin-bottom:5px;\" />";
   			}
			$m.="<a href='http://$result[link]' target='_blank' title='$result[lstnm]'>$result[title]</a> - <span class='dir_grey'>$result[link]</span> <br />";
			$m .= "$result[description]</p>";
		}
		$m .= "<p style='clear:both;'></p>";
		return $m;
		
	}
	
	function unsponsor($spon_type) {
		$id = $_REQUEST['unsponsor_id'];
		$sql = "UPDATE ".$this->db_pre."sites SET $spon_type = '0' WHERE id = '{$id}'";
		mysql_query($sql);
		$this->display_error_sucsess("Site Unsponsored!");
	}
	
	function format_base() {
		$base = $_SERVER['REQUEST_URI'];
		$base = preg_replace("/&wp_bdir=(.*)&?/","",$base);
		$base = preg_replace("/&catid=(.*)&?/","",$base);
		$base = preg_replace("/&sub_cat_id=(.*)&?/","",$base);
		$base = preg_replace("/wpbdir-(.*)\/?/","",$base);
		$base = rtrim($base, "/");
		$base = $base."/";
		return $base;
	}
	
	function get_search_results() {
		$search = $_REQUEST['search_term'];
		$sql = "SELECT * FROM ".$this->db_pre."sites WHERE deleted = '0' AND enabled = '1' AND title LIKE '%$search%' OR description LIKE '%$search%'";
		$res = mysql_query($sql);
		return $res;
	}
	
	function show_search_front() {
		$m = "";
		$base = $this->format_base();
		$search_term = $_REQUEST['search_term'];
		$search_term = $this->format_for_url($base);
		$m .= "<div align='left'><form method=\"post\" action=\"$base/wpdir-search/\" name=\"easy_search\">     
    
        	<h2>Directory Search </h2><input type=\"text\" name=\"search_term\" value=\"{$_REQUEST['search_term']}\" size=\"30\" /> 
            &nbsp;&nbsp;&nbsp;<input type=\"submit\" name=\"search\" value=\"Search\" />
       
</form></div>";
		return $m;
	}
	
	function format_for_url($string){
		$string = strtolower($string);
		$string = str_replace(" ", "-", $string);
		return $string;
	}
	
	function show_listing_form_user() {
		$m = "<h2>Submit A Listing!</h2><form action=\"\" method=\"post\" name=\"listing\" id=\"listing\">
		  <table>
		    <tr valign=\"baseline\">
		      <td nowrap=\"nowrap\" align=\"left\">Listing Name:</td>
		      <td><input type=\"text\" name=\"lstnm\" value=\"\" size=\"32\" /></td>
		    </tr>
		    <tr valign=\"baseline\">
		      <td nowrap=\"nowrap\" align=\"left\">Link:</td>
		      <td><input type=\"text\" name=\"lstlnk\" value=\"\" size=\"32\" /></td>
		    </tr>
		     <tr valign=\"baseline\">
		      <td nowrap=\"nowrap\" align=\"left\">Email:</td>
		      <td><input type=\"text\" name=\"lstmail\" value=\"\" size=\"32\" /></td>
		    </tr>
		    <tr valign=\"baseline\">
		      <td nowrap=\"nowrap\" align=\"left\">Category:</td>
		      <td><select name=\"lstcat\">";
		      $m .=  $this->get_cats_for_options(); 
		      $m .= "</select>      </td>
		    </tr>
		    <tr valign=\"baseline\">
		      <td nowrap=\"nowrap\" align=\"left\" valign=\"top\">Description:</td>
		      <td><textarea name=\"lstdsc\" cols=\"50\" rows=\"5\"></textarea>      </td>
		    </tr>
		    <tr valign=\"baseline\">
		      <td nowrap=\"nowrap\" align=\"left\">Contact Person:</td>
		      <td><input type=\"text\" name=\"lstcnt\" value=\"\" size=\"32\" /></td>
		    </tr>
		    <tr valign=\"baseline\">
		      <td nowrap=\"nowrap\" align=\"left\">State:</td>
		      <td><input type=\"text\" name=\"lststate\" value=\"\" size=\"32\" /></td>
		    </tr>
		    <tr valign=\"baseline\">
		      <td nowrap=\"nowrap\" align=\"left\">City:</td>
		      <td><input type=\"text\" name=\"lstcity\" value=\"\" size=\"32\" /></td>
		    </tr>
		    <tr valign=\"baseline\">
		      <td nowrap=\"nowrap\" align=\"left\">Address:</td>
		      <td><input type=\"text\" name=\"lstaddr\" value=\"\" size=\"32\" /></td>
		    </tr>
		    <tr valign=\"baseline\">
		      <td nowrap=\"nowrap\" align=\"left\">Telephone:</td>
		      <td><input type=\"text\" name=\"lsttel\" value=\"\" size=\"32\" /></td>
		    </tr>
		    <tr valign=\"baseline\">
		      <td nowrap=\"nowrap\" align=\"left\">Fax:</td>
		      <td><input type=\"text\" name=\"lstfax\" value=\"\" size=\"32\" /></td>
		    </tr>
		    
		    <tr valign=\"baseline\">
		      <td nowrap=\"nowrap\" align=\"left\">&nbsp;</td>
		      <td><input type=\"submit\" value=\"Submit\" /></td>
		    </tr>
		  </table>
		  
		  <input type=\"hidden\" name=\"add_listing\" value=\"True\" />
		</form>";
	
		return $m;
	}

//Συνάρτηση για ανάρτηση εικόνας	
	function upload_screen_shot ($lstid) {
		$target_path = $this->base_dir."images/screenshots/";

		$target_path = $target_path . $lstid; 
		$ext = explode(".",$_FILES['screenshot']['name']);
		$ext = $ext[1];
		$target_path = $target_path.'.'.$ext;
		if(!copy($_FILES['screenshot']['tmp_name'], $target_path)) {
		    $message = "There was an error uploading the file, please try again!";
		    $this->display_error_sucsess($message);
		}
	}

//Συνάρτηση για εμφάνιση εικόνας	
	function show_screenshot($lstid) {
		if (is_file($this->base_dir."images/screenshots/".$lstid.".jpg")){
			$img = $this->image_base."images/screenshots/".$lstid.".jpg";
		} else if (is_file($this->base_dir."images/screenshots/".$lstid.".gif")){
			$img = $this->image_base."images/screenshots/".$lstid.".gif";
		} else if (is_file($this->base_dir."images/screenshots/".$lstid.".png")){
			$img = $this->image_base."images/screenshots/".$lstid.".png";
		} else {
			$img = "False";
		}
		return $img;
	}



}


?>