<?php
/*
filename: functions.php
description: Contains functions that can be used anywhere in your theme.
It's a good place to factor out commonly used tasks.
*/




/*
Gets all images associated with the post
excludes images with an order >=99 which 
allows you to omit specific images.

Must be used within the Loop!
*/

function get_post_image_array(){
	global $post;
	$postID = $post->ID;
	
	//tell wordpress what information we need to get from the post
	$args = array(
		'post_parent' => $postID, 
		'post_status' => 'inherit', 
		'post_type' => 'attachment', 
		'post_mime_type' => 'image', 
		'order' => 'ASC', 
		'orderby' => 'menu_order ID'
		);
	$postImages = get_children( $args );
	//create an empty array to fill with our image info
	$filteredImages = array();
	//filter out all images with order >= 99

//print_r($postImages);

	foreach($postImages as $image){
		if($image->menu_order < 99) $filteredImages[] = $image;
	}
	return $filteredImages;
}


/*
Makes each image in the array compatible with
Timthumb which makes it easier to scale images
and not use too much bandwidth. This will make 
your website load faster. 

You should not use images larger than approx. 1MB in size, 
otherwise timthumb can choke. You shouldn't be using very
large images frequently anyway.

This function requires that you pass in an image array
like the one from the previous function get_post_image_array

A timthumb url will look something like this:
http://www.yoursite.com/wp-content/timthumb/timthumb.php?src=http://www.yoursite.com/images/image.jpg&h=250&w=150&zc=0&q=100

The url is long and unweildly, but if you can automate it,
as we're doing here, it'll save you and your users time and resources

If you need to generate multiple sizes of images, you can do that here as well.
You would need to define alternates for $maxwidth, $maxheight etc, and just
push an additional key/value pair back into the $image object where you can 
access it back in your theme. Some reasons you might do this is if you
need thumbnails, or a larger size for a lightbox.

see timthumb documentation at:
http://www.binarymoon.co.uk/projects/timthumb/
http://code.google.com/p/timthumb/
*/


function make_timthumb_image_array(){
	global $post;
	global $blog_id;
	$postID = $post->ID;
	
	//get the post image array from the function in this file
	$images = get_post_image_array();
	//define the location of timthumb in your file structure
	$timthumb_url = get_bloginfo('url') . "/wp-content/timthumb/timthumb.php";
	//define the maximum width of your images

	//create an empty array to place our new image objects into
	$imageArray = array();
	
	//loop through each image and generate a timthumb url
	foreach ($images as $image){
		$site_directory = '/http:\/\/mlhplayground.org\/media\/([A-Za-z1-9])*?\//'; //for multisite
		$media_root = 'http://mlhplayground.org/media/wp-content/blogs.dir/'. $blog_id ."/"; //for multisite
		$scaled_image = wp_get_attachment_image_src($image->ID, 'large');
		$root_image = preg_replace($site_directory, $media_root, $scaled_image[0]); //for multisite
		if($scaled_image[0] == false){
			$timthumb = $timthumb_url . "?src=" . $image->guid ;
		}else{
			$timthumb = $timthumb_url . "?src=" . $root_image ;
		}
		//place the timthumb url back into the image object with the key "timthumb"
		//we will have to retrieve this url later in our theme
		//note that $image is not an array, but an object and we must assign it accordingly
		$image -> timthumb = $timthumb;
		//roll the image object back into our new array
		$imageArray[] = $image;
	}
	return $imageArray;
}


/*
	Add stylesheets and js to admin pages
*/

define('MY_WORDPRESS_FOLDER',$_SERVER['DOCUMENT_ROOT']);
define('MY_THEME_FOLDER',str_replace("\\",'/',dirname(__FILE__)));
define('MY_THEME_PATH','/' . substr(MY_THEME_FOLDER,stripos(MY_THEME_FOLDER,'wp-content')));

add_action('admin_init','my_meta_init');
 
function my_meta_init()
{
	// review the function reference for parameter details
	// http://codex.wordpress.org/Function_Reference/wp_enqueue_script
	// http://codex.wordpress.org/Function_Reference/wp_enqueue_style
	//wp_enqueue_script('my_jquery_js', MY_THEME_PATH . '/assets/jquery/jquery-1.6.1.min.js'); 
	wp_enqueue_script('my_meta_js', MY_THEME_PATH . '/assets/jquery-ui/js/jquery-ui-1.8.13.datepicker.min.js'); 
	wp_enqueue_script('my_map_js', 'http://maps.google.com/maps/api/js?sensor=false');
	wp_enqueue_script('my_admin_js', MY_THEME_PATH . '/assets/admin.js');
	wp_enqueue_style('my_datepicker_css', MY_THEME_PATH . '/assets/jquery-ui/css/smoothness/jquery-ui-1.8.13.custom.css');


}

$new_meta_boxes =
	array(
		
		"date" => array(
			"name" => "date",
			"std" => "",
			"title" => "Date",
			"description" => "Enter in a date. (YYYY-MM-DD)",
			'type' => 'text'
		),
		"location" => array(
			"name" => "location",
			"std" => "",
			"title" => "Location",
			"description" => "Enter a location. Drag the marker around or type in a target destination and press tab, or click elsewhere to exit the field (not Enter!)",
			'type' => 'map'
		)	,
			"lat" => array(
				"name" => "lat",
				"std" => "",
				"title" => "lat",
				"description" => "Latitude",
				'type' => 'hidden',
				'readonly' => true
		),
			"lng" => array(
				"name" => "lng",
				"std" => "",
				"title" => "lng",
				"description" => "Longitude",
				'type' => 'hidden',
				'readonly' => true
				
		),
		 	"zoom" => array(
		 		"name" => "zoom",
		 		"std" => "",
		 		"title" => "zoom",
		 		"description" => "",
		 		'type' => 'hidden'
         
		 )
		
);

function new_meta_boxes() {
	global $post, $new_meta_boxes;
	global $user_identity;
	get_currentuserinfo();
 
	foreach($new_meta_boxes as $meta_box) {
		$meta_box_value = get_post_meta($post->ID, $meta_box['name'].'_value', true);
 
		if($meta_box_value == "") $meta_box_value = $meta_box['std']; 
 
		echo'<input type="hidden" name="'.$meta_box['name'].'_noncename" id="'.$meta_box['name'].'_noncename" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';
 
		if($meta_box['type'] != 'hidden') echo'<h4>'.$meta_box['title'].'</h4>';
 
		if($meta_box['type'] == "text"){
			if($meta_box['readonly']) $readonly = 'readonly';
			echo'<input type="text" id="'.$meta_box['name'].'" name="'.$meta_box['name'].'_value" value="'.$meta_box_value.'" size="55" '.$readonly.'/><br />';
 		}elseif($meta_box['type'] == 'checkbox'){
			$checked = "";
			//$editable = "disabled";
			//if($meta_box['name'] == 'stage2' || $user_identity == "joseph" ||$user_identity == "Alixb"||$user_identity == "peter")
			//	$editable = "";
			if($meta_box_value == "true")
				$checked = 'checked="checked"';
			echo'<input type="checkbox" name="'.$meta_box['name'].'_value" value="true"'. $checked .' '. $editable .' /><br />';	
			
		}elseif($meta_box['type'] == 'map'){
			echo '<div id="map_canvas" style="height:300px;width:500px"></div>';
			echo'<input type="text" id="'.$meta_box['name'].'" name="'.$meta_box['name'].'_value" value="'.$meta_box_value.'" size="55" /><br />';
			echo'<input type="checkbox" name="clear-map" id="clear-map"/><p> empty the map data?</p><br />';	
		}elseif($meta_box['type'] == 'hidden'){
		 	echo'<input type="hidden" id="'.$meta_box['name'].'" name="'.$meta_box['name'].'_value" value="'.$meta_box_value.'"  />';
		 	
		}
		
		if($meta_box['type'] != 'hidden') echo'<p><label for="'.$meta_box['name'].'_value">'.$meta_box['description'].'</label></p>';
	}
}

function create_meta_box() {
global $theme_name;
if ( function_exists('add_meta_box') ) {
add_meta_box( 'new-meta-boxes', 'GSD Post Meta', 'new_meta_boxes', 'post', 'normal', 'high' );
}
}

function save_postdata( $post_id ) {
global $post, $new_meta_boxes;
 
foreach($new_meta_boxes as $meta_box) {
// Verify
if ( !wp_verify_nonce( $_POST[$meta_box['name'].'_noncename'], plugin_basename(__FILE__) )) {
return $post_id;
}
 
if ( 'page' == $_POST['post_type'] ) {
if ( !current_user_can( 'edit_page', $post_id ))
return $post_id;
} else {
if ( !current_user_can( 'edit_post', $post_id ))
return $post_id;
}
 
$data = $_POST[$meta_box['name'].'_value'];
 
if(get_post_meta($post_id, $meta_box['name'].'_value') == "")
add_post_meta($post_id, $meta_box['name'].'_value', $data, true);
elseif($data != get_post_meta($post_id, $meta_box['name'].'_value', true))
update_post_meta($post_id, $meta_box['name'].'_value', $data);
elseif($data == "")
delete_post_meta($post_id, $meta_box['name'].'_value', get_post_meta($post_id, $meta_box['name'].'_value', true));
}
}

add_action('admin_menu', 'create_meta_box');
add_action('save_post', 'save_postdata');





/*
define('MY_WORDPRESS_FOLDER',$_SERVER['DOCUMENT_ROOT']);
define('MY_THEME_FOLDER',str_replace("\\",'/',dirname(__FILE__)));
define('MY_THEME_PATH','/' . substr(MY_THEME_FOLDER,stripos(MY_THEME_FOLDER,'wp-content')));
 
add_action('admin_init','my_meta_init');
 
function my_meta_init()
{
	// review the function reference for parameter details
	// http://codex.wordpress.org/Function_Reference/wp_enqueue_script
	// http://codex.wordpress.org/Function_Reference/wp_enqueue_style
	//wp_enqueue_script('my_meta_js', MY_THEME_PATH . '/assets/jquery/jquery-1.6.1.min.js', array('jquery'));
	wp_enqueue_script('my_meta_js', MY_THEME_PATH . '/assets/jquery-ui/js/jquery-ui-1.8.13.datepicker.min.js'); 
	wp_enqueue_style('my_meta_css', MY_THEME_PATH . '/custom/meta.css');
	wp_enqueue_script('my_datepicker_js', MY_THEME_PATH . '/assets/admin.js');
	wp_enqueue_style('my_datepicker_css', MY_THEME_PATH . '/assets/jquery-ui/css/smoothness/jquery-ui-1.8.13.custom.css');
	
	
 
	// review the function reference for parameter details
	// http://codex.wordpress.org/Function_Reference/add_meta_box
 
	// add a meta box for each of the wordpress page types: posts and pages
	foreach (array('post','page') as $type) 
	{
		add_meta_box('my_all_meta', 'GSD Meta', 'my_meta_setup', $type, 'normal', 'high');
	}
 
	// add a callback function to save any data a user enters in
	add_action('save_post','my_meta_save');
}
 
function my_meta_setup()
{
	global $post;
 
	// using an underscore, prevents the meta variable
	// from showing up in the custom fields section
	$meta = get_post_meta($post->ID,'_my_meta',TRUE);
 
	// instead of writing HTML here, lets do an include
	include(MY_THEME_FOLDER . '/custom/meta.php');
 
	// create a custom nonce for submit verification later
	echo '<input type="hidden" name="my_meta_noncename" value="' . wp_create_nonce(__FILE__) . '" />';
}
 
function my_meta_save($post_id) 
{
	// authentication checks
 
	// make sure data came from our meta box
	if (!wp_verify_nonce($_POST['my_meta_noncename'],__FILE__)) return $post_id;
 
	// check user permissions
	if ($_POST['post_type'] == 'page') 
	{
		if (!current_user_can('edit_page', $post_id)) return $post_id;
	}
	else 
	{
		if (!current_user_can('edit_post', $post_id)) return $post_id;
	}
 
	// authentication passed, save data
 
	// var types
	// single: _my_meta[var]
	// array: _my_meta[var][]
	// grouped array: _my_meta[var_group][0][var_1], _my_meta[var_group][0][var_2]
 
	$current_data = get_post_meta($post_id, '_my_meta', TRUE);	
 
	$new_data = $_POST['_my_meta'];
 
	my_meta_clean($new_data);
 
	if ($current_data) 
	{
		if (is_null($new_data)) delete_post_meta($post_id,'_my_meta');
		else update_post_meta($post_id,'_my_meta',$new_data);
	}
	elseif (!is_null($new_data))
	{
		add_post_meta($post_id,'_my_meta',$new_data,TRUE);
	}
 
	return $post_id;
}
 
function my_meta_clean(&$arr)
{
	if (is_array($arr))
	{
		foreach ($arr as $i => $v)
		{
			if (is_array($arr[$i])) 
			{
				my_meta_clean($arr[$i]);
 
				if (!count($arr[$i])) 
				{
					unset($arr[$i]);
				}
			}
			else 
			{
				if (trim($arr[$i]) == '') 
				{
					unset($arr[$i]);
				}
			}
		}
 
		if (!count($arr)) 
		{
			$arr = NULL;
		}
	}
}
*/



/*

$key = "GSD"; //table prefix do not change once set
$meta_boxes = array(
	"callnumber" => array(
		"name" => "callnumber",
		"title" => "Call Number",
		"description" => "enter in the unique library call number for this record",
		"type" => "text"
	),
	"curated" => array(
		"name" => "curated",
		"title" => "Curated",
		"description" => "Is this post curated?",
		"type" => "checkbox"
	)
);
 
function create_meta_box() {
	global $key;
 
	if( function_exists( 'add_meta_box' ) ) {
		add_meta_box( 'new-meta-boxes', ucfirst( $key ) . ' Custom Options', 'display_meta_box', 'post', 'normal', 'high' );
	}
}
 
function display_meta_box() {
	global $post, $meta_boxes, $key;
?>
 
	<div class="form-wrap">
 
	<?php
	wp_nonce_field( plugin_basename( __FILE__ ), $key . '_wpnonce', false, true );
 
	foreach($meta_boxes as $meta_box) {
		$data = get_post_meta($post->ID, $key, true);
		?>
 
		<div class="form-field form-required">
		<label for="<?php echo $meta_box[ 'name' ]; ?>"><?php echo $meta_box[ 'title' ]; ?></label>
		
		<?php if( $meta_box[ 'type' ]  == "text"): ?>
			<input type="type" name="<?php echo $meta_box[ 'name' ]; ?>" value="<?php echo htmlspecialchars( $data[ $meta_box[ 'name' ] ] ); ?>" />
		<?php elseif($meta_box[ 'type' ]  == "checkbox") : ?>
			<input type="checkbox" name="<?php echo $meta_box[ 'name' ]; ?>" <?php if( $data[ $meta_box[ 'name' ] ] ) echo "checked='checked'"; ?> value="true"/>
		<?php endif; ?>
		
		<p><?php echo $meta_box[ 'description' ]; ?></p>
		</div>
 
	<?php } ?>
 
	</div>
<?php
}
 
function save_meta_box( $post_id ) {
	global $post, $meta_boxes, $key;
 
	foreach( $meta_boxes as $meta_box ) {
		$data[ $meta_box[ 'name' ] ] = $_POST[ $meta_box[ 'name' ] ];
	}
 
	if ( !wp_verify_nonce( $_POST[ $key . '_wpnonce' ], plugin_basename(__FILE__) ) )
		return $post_id;
 
	if ( !current_user_can( 'edit_post', $post_id ))
		return $post_id;
 
	update_post_meta( $post_id, $key, $data );
}
 
add_action( 'admin_menu', 'create_meta_box' );
add_action( 'save_post', 'save_meta_box' );


*/


/* custom taxonomy */

function people_init() {
	// create a new taxonomy
	register_taxonomy(
		'people',
		'post',
		array(
			'hierarchical' => false,
			'query_var' => true,
			'label' => __('People'),
			'sort' => true,
			'rewrite' => true,
			'args' => array('orderby' => 'term_order'),
			'rewrite' => array('slug' => 'person')
		)
	);
}
add_action( 'init', 'people_init' );

/* custom taxonomy */

function exhibitions_init() {
	// create a new taxonomy
	register_taxonomy(
		'exhibition',
		'post',
		array(
			'hierarchical' => true,
			'label' => __('Exhibition'),
			'sort' => true,
			'query_var' => true,
			'rewrite' => true,
			'args' => array('orderby' => 'term_order'),
			'rewrite' => array('slug' => 'exhibition')
		)
	);
}
add_action( 'init', 'exhibitions_init' );


/* custom login style */
function custom_login() { 
echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('template_directory').'/assets/css/custom-login.css" />'; 
}
add_action('login_head', 'custom_login');


//remove admin bar
add_action( 'admin_print_scripts-profile.php', 'hide_admin_bar_prefs' );
function hide_admin_bar_prefs() {
	echo '<style type="text/css">.show-admin-bar { display: none; }</style>';
}
add_filter( 'show_admin_bar', '__return_false' );

remove_action('wp_head', 'wp_generator');