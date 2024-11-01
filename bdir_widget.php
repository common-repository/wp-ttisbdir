<?php
function widget_wp_ttisbdir_init() {
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
		return;	
	function widget_wp_ttisbdir() {
		echo '<li class="sidebox"><h2 class="sidebartitle">Business Directory</h2>';
		if (isset($_POST['lstnm']) && isset($_POST['lstlnk']) && $_REQUEST['add_bdir_widget'] == "True") {
			require_once(ABSPATH.'wp-content/plugins/wp-ttisbdir/ttisbdir.class.php');
			$dir = new wp_ttisbdir();
			$dir->add_listing("False");
			echo "Thanks!";
		} else {
			require_once(ABSPATH.'wp-content/plugins/wp-ttisbdir/ttisbdir.class.php');
			$dir = new wp_ttisbdir();
			echo "<form action=\"\" method=\"post\" name=\"listing\" id=\"listing\">
			Submit your company to our directory!<br />
			Name:<br />
			  <input type=\"text\" name=\"lstname\" value=\"$result[lstname]\" size=\"20\" /><br />
			  Link:<br />
			  <input type=\"text\" name=\"lstlnk\" value=\"$result[lstlnk]\" size=\"20\" /><br />
			  Category:<br />
			  <select name=\"cat_id\">";
				echo $dir->get_cats_for_options($result[sub_cat]);
			 echo " </select>  <br />    
			  Description:<br />
			  <textarea name=\"lstdsc\" cols=\"10\" rows=\"5\">$result[lstdsc]</textarea>     <br /> 
			  <input type=\"submit\" value=\"Submit\" />
			  <input type=\"hidden\" name=\"add_bdir_widget\" value=\"True\" />
			</form>";
		}
			//if these links are removed the plugin will not work!
			echo ' <div align="right">Powered by  <a href="http://www.linksback.org" title="Wordpress Plugins">WP-Directory</a>';
			
		echo "</li>";
	}	
register_sidebar_widget('bDirectory', 'widget_wp_ttisbdir');
}

add_action('plugins_loaded', 'widget_wp_ttisbdir_init');
?>