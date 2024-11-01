<?php
/*
Plugin Name:WP-ttisbdir
Plugin URI: http://www.techteamis.gr
Description: A simple business directory wordpress plugin
Version: 1.0.2
Author: Kostas Y - techteam internet services
Author URI: http://www.techteamis.gr
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/ 



require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
global $wpdb , $wp_roles;	
	
function wp_ttisbdir_install () {
	global $wpdb;
	$bdir_cats = "CREATE TABLE `".$wpdb->prefix."bdir_cats` (
	
				`catid` int(6) NOT NULL auto_increment,
  				`catname` varchar(240) NOT NULL,
  				`catdesc` text NOT NULL,
				`date` varchar(100) NOT NULL,
				`enabled` tinyint(4) NOT NULL default '0',
				`date_mod` varchar(100) NOT NULL,
			  	`deleted` tinyint(4) NOT NULL default '0',
  				PRIMARY KEY  (`catid`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
	

	$bdir_options = "CREATE TABLE `".$wpdb->prefix."bdir_options` (
  					`col_nums` int(2) NOT NULL,
				  	`dir_on` tinyint(4) NOT NULL default '1',
				  	`dir_name` varchar(255) NOT NULL default 'WP-ttisbdirectory',
				  	`sub_cat_num` int(5) NOT NULL default '3',
				  	`sub_cat_on` tinyint(4) NOT NULL default '1',
				  	`display_dir_name` tinyint(4) NOT NULL default '1'
					) TYPE=MyISAM;";





	$bdir_listings = "CREATE TABLE `".$wpdb->prefix."bdir_listings` (
 `lstid` int(9) NOT NULL auto_increment,
  `lstnm` varchar(180) NOT NULL,
  `lstcat` int(9) NOT NULL,
  `sub_cat` int(10) NOT NULL,
  `lstdsc` text NOT NULL,
  `lstlnk` varchar(180) NOT NULL,
  `lstmail` varchar(180) NOT NULL,
  `lstcnt` varchar(180) NOT NULL,
  `lstaddr` varchar(260) NOT NULL,
  `lststate` varchar(120) NOT NULL,
  `lstcity` varchar(120) NOT NULL,
  `lsttel` varchar(16) NOT NULL,
  `lsttel1` varchar(16) NOT NULL,
  `lstfax` varchar(16) NOT NULL,
  `date` varchar(100) NOT NULL,
  `date_mod` varchar(100) NOT NULL,
  `enabled` tinyint(4) NOT NULL default '0',
  `deleted` tinyint(4) NOT NULL default '0',
  `main_spon` varchar(4) NOT NULL,
  `sub_spon` varchar(4) NOT NULL,
  `site_spon` tinyint(4) NOT NULL,
  PRIMARY KEY  (`lstid`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
	
	$bdir_sub_cat = "CREATE TABLE `".$wpdb->prefix."bdir_sub_cat` (
			  `id` int(10) NOT NULL auto_increment,
			  `name` varchar(255) NOT NULL,
			  `description` mediumtext NOT NULL,
			  `cat_id` int(10) NOT NULL,
			  `date` varchar(100) NOT NULL,
			  `enabled` tinyint(4) NOT NULL default '0',
			  `deleted` tinyint(4) NOT NULL default '0',
			  `date_mod` varchar(100) NOT NULL,
			  PRIMARY KEY  (`id`)
			) TYPE=MyISAM;";
	
	maybe_create_table(($wpdb->prefix."bdir_cats"),$bdir_cats);  
	maybe_create_table(($wpdb->prefix."bdir_listings"),$bdir_listings); 
	maybe_create_table(($wpdb->prefix."bdir_options"),$bdir_options); 
	maybe_create_table(($wpdb->prefix."bdir_sub_cat"),$bdir_sub_cat); 

	mysql_query("INSERT INTO `".$wpdb->prefix."bdir_options` (`col_nums`, `dir_on`, `dir_name`, `sub_cat_num`) VALUES (3, 1, 'WP-ttisbdir', 2)");



}

add_option("wp_dir_permalinks", '0', 'Permalinks');

if (isset($_GET['activate']) && $_GET['activate'] == 'true') {
   add_action('init', 'wp_ttisbdir_install');
}

 add_action('init', 'dir_fancy_url');

function dir_fancy_url($var='REQUEST_URI')
{
   // WP passes an empty string as argument unless I tell it explicitly that 
   // the function takes no parameters. But you can't specify number of args in WP1.5
   // so the empty string is replaced below.
   if (!in_array($var, array('REQUEST_URI', 'PATH_INFO'))) $var = 'REQUEST_URI';
   $req = $_SERVER[$var];
   
   //Check url to see if it is a search url!
   if (preg_match('!^(.+/)wpdir-search/(.*)/?!', $req, $match) && (url_to_postid($req) == 0)) {
       $_REQUEST['wp_dir'] = "search";
       $req = $match[1].$match[3];
       $_SERVER[$var] = $req;
   }
   
   //Check url to see if it is an add url!
   if (preg_match('!^(.+/)wpdir-add/(.*)/?!', $req, $match) && (url_to_postid($req) == 0)) {
       $_REQUEST['wp_dir'] = "add";
       $req = $match[1].$match[3];
       $_SERVER[$var] = $req;
   }
   
   if (preg_match('!^(.+/)wpdir-listings/(.*)/?!', $req, $match) && (url_to_postid($req) == 0)) {
   		preg_match("!cat-([0-9]+)/!", $_SERVER['REQUEST_URI'], $matches);
       	$_REQUEST['sub_cat_id'] = $matches[1];
   		$_REQUEST['wp_dir'] = "listings";
   		$req = $match[1].$match[3];
       	$_SERVER[$var] = $req;  	
   }
   
   if (preg_match('!^(.+/)wpdir-sub/(.*)/?!', $req, $match) && (url_to_postid($req) == 0)) {
   		preg_match("!cat-([0-9]+)/!", $_SERVER['REQUEST_URI'], $matches);
       	$_REQUEST['catid'] = $matches[1];
   		$_REQUEST['wp_dir'] = "sub";
   		$req = $match[1].$match[3];
       	$_SERVER[$var] = $req;  	
   }
   
   
   // do the same for PATH_INFO
   if (($var != 'PATH_INFO') && isset($_SERVER['PATH_INFO'])) {
       dir_fancy_url('PATH_INFO');
   }
}  


include('bdir_widget.php');
function bdir_add_admin_pages() {
	$base_page = "wp-ttisbdir/main.php";
	add_menu_page("WP-Bdir", "WP-bdir",7, "$base_page");
	
	add_submenu_page($base_page,"Categories", "Categories",7, "wp-ttisbdir/cat.php");
	add_submenu_page($base_page,"Options", "Options",7, "wp-ttisbdir/options.php");
	add_submenu_page($base_page,"Listings", "Listings",7, "wp-ttisbdir/listings.php");
	//add_submenu_page($base_page,"Adv Listings", "Adv Listings",7, "wp-ttisbdir/adv_listings.php");
	//add_submenu_page($base_page,"Statistics", "Statistics",7, "wp-ttisbdir/statistics.php");
	add_submenu_page($base_page,"Trash", "Trash",7, "wp-ttisbdir/trash.php");
	add_submenu_page($base_page,"About", "About",7, "wp-ttisbdir/about.php");

}
//include ('wp-dir-widget.php');
add_action("admin_menu", "bdir_add_admin_pages");


function wp_ttisbdir_on_page($data){
	if(!preg_match("/<!--wp-ttisbdir-->/", $data)) {
		return $data;
	} else {
		include('ttisbdir.class.php');
	 	$dir = new wp_ttisbdir();
	 	$m = "";
	 	$dir_name_on = $dir->display_dir_name();
	 	if ($dir_name_on == "1"){
	 		$dir_name = $dir->get_option("dir_name");
	 		$m .= "<h3>$dir_name</h3>";
                        //$m .= "<br />";
	 	}
	 	$m .= $dir->breadcrumb();
	 	$section = $_REQUEST['wp_dir'];
	 	switch ($section) {
	 		case "home":
                                $m .= $dir->show_categories();
                                $m .= $dir->show_search_front();
	 		break;
	 		
	 		case "sub":
                                $m .= "<br />";
				$m .= $dir->show_sub_categories();
                                $m .= "<br />";
				//$m .= $dir->show_listings_for_cat_front();
	 		break;
	 		
	 		case "listings":
                                $m .= "<br />";
	 			$m .= $dir->show_listings_front();
                                $m .= "<br />";	
                        break;
	 		
	 		case "add";
	 			if ($_REQUEST['add_listing'] == "True"){
	 				$dir->add_listing("False");
	 			}
	 			$m .= $dir->show_listing_form_user();
	 		break;
	 		
	 		case "search":
	 			$m .= $dir->show_search_results();
	 		break;
	 		
	 		default:
	 			$m .= $dir->show_categories();
                                $m .= $dir->show_search_front();
	 		break;
	 	}
	 	$link = $dir->format_base();
	 	if ($dir->permalinks){
	 		$link = $link."wpdir-add/";
	 	} else {
	 		$link = $link."&wp_dir=add";
	 	}
	 	$m .= "<h2>Submit A Listing!</h2><p>Press <a href='$link'>here</a> to submit a new listing.</p>";
	
		
	} 
	 	return str_replace("<!--wp-ttisbdir-->", $m, $data);
	 	
	 
	}
if( function_exists('add_filter') ) {
		add_filter('the_content', 'wp_ttisbdir_on_page'); 
}




?>