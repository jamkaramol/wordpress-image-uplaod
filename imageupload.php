<?php
/*
Plugin Name: Wordpress - admin panel upload image 
Description: Upload image from admin panel and show the uploaded image on web page by using short code
Author: Amol b jamkar
Version: 0.1
*/

add_action('admin_menu', 'add_menu_to_admin_page');
add_shortcode('Show_uploaded_image', 'imageurl');


function add_menu_to_admin_page(){
        add_menu_page( 'Test Plugin Page', 'Image Upload', 'manage_options', 'test-plugin', 'test_init' );
}

function image_view()
{
global $wpdb; 
$your_query="SELECT `ID` FROM `wp_posts` WHERE `post_title`='imageToDisplay' ORDER BY `ID` DESC LIMIT 1";
$results = $wpdb->get_results($your_query);
echo  wp_get_attachment_image( $results[0]->ID, 'thumbnail' );
}

function imageurl(){
global $wpdb; 
$your_query="SELECT `ID` FROM `wp_posts` WHERE `post_title`='imageToDisplay' ORDER BY `ID` DESC LIMIT 1";
$results = $wpdb->get_results($your_query);
echo  wp_get_attachment_image( $results[0]->ID, '' );
}



function test_init(){
        echo  "
        <h1>Upload Your Image</h1>
        <form action='#' method='POST' enctype='multipart/form-data'
        style='background:#f8f8f8;box-shadow:0 0 10px #aaa;border:1px solid #999;border-radius:5px;margin:20px auto;padding:10px;'>
        <div class='form-group' style='padding:10px;'>
        <input type='file' name='image' accept='image/*' style='background:#fff;padding:10px;text-align:center;color:#111; border-radius:5px;' />
        <input type='submit' style='background:#eee;padding:10px;text-align:center;color:#111; border-radius:5px;' value='submit' class='btn-block' />
        </div>
        </form>";
        function my_cust_filename($dir, $name, $ext){
                 $name="imageToDisplay";
               return $name.$ext;
             }
        function upload_user_file( $file = array() ) {
         require_once( ABSPATH . 'wp-admin/includes/admin.php' );
           $file_return = wp_handle_upload( $file, array('test_form' => false,'unique_filename_callback' => 'my_cust_filename' ) );
           if( isset( $file_return['error'] ) || isset( $file_return['upload_error_handler'] ) ) {
                 return false;
             } else {
                 $filename = $file_return['file'];
                 $attachment = array(
                     'post_mime_type' => $file_return['type'],
                     'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
                     'post_content' => '',
                     'post_status' => 'inherit',
                     'guid' => $file_return['url']
                 );
                 $attachment_id = wp_insert_attachment( $attachment, $file_return['file'] );
                 require_once(ABSPATH . 'wp-admin/includes/image.php');
                 $attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );
                 wp_update_attachment_metadata( $attachment_id, $attachment_data );
                 if( 0 < intval( $attachment_id ) ) {
                     return true;
                 }
             }

             return false;
        }


        if(isset($_FILES)){
        if( ! empty( $_FILES ) ) {
        		if(empty($_FILES['image']['name'])){
        			echo "<div class='woocommerce-Message woocommerce-Message--error woocommerce-error' >
        			Please select Image to upload</div>";
        		}else{

            	foreach( $_FILES as $file ) {
           		 if( is_array( $file ) ) {
        		 $attachment_id = upload_user_file( $file );
        		    }
        		if($attachment_id > 0 ){
        		echo "<div class='woocommerce-Message woocommerce-Message--info woocommerce-info' >
        		File uploaded Successfully!!</div>";
                }
        		else{
        		echo "<div class='woocommerce-Message woocommerce-Message--error woocommerce-error' >
        		Error At Uploading image on server, Please Contact to Website Administrator</div>";
        		}
          }

        	}
        }
        }
}
?>
