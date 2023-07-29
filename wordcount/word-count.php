<?php
// wordpress plugin template
/*
Plugin Name:  Word Count
Plugin URI:   https://extenddeveloper.com/ed-users-list-table
Description:  Count total word from the blog post.
Version:      1.0
Author:       Jewel Hossain
Author URI:   https://extenddeveloper.com
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  wordcount
Domain Path:  /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Do something when activate plugin function
 *
 * @return void
 */
function wordcount_activation_hook(){

}
register_activation_hook( __FILE__, 'wordcount_activation_hook' );

/**
 * Do something when deactivate plugin function
 *
 * @return void
 */
function wordcount_deactivation_hook(){

}
register_deactivation_hook( __FILE__, 'wordcount_deactivation_hook' );

function wordcount_plugin_load_textdomain(){
    load_plugin_textdomain( 'wordcount', false, dirname(__FILE__)."/languages" );
}
add_action( 'plugin_loaded', 'wordcount_plugin_load_textdomain' );


include_once plugin_dir_path( __FILE__ ) . '/shortcode.php';




/**
 * count total words form the post.
 *
 * @param [type] $content
 * @return void
 */
function wordcount_count_wordcount($content){
    $current_post_type = get_post_type( get_the_iD() );
    if($current_post_type != 'post'){
        return $content;
    }
    $strip_word = strip_tags($content);
    $wordn = str_word_count($strip_word);
    $label = __("Total World Count","wordcount");
    $label = apply_filters( 'wordcount_title', $label );
    $tag = apply_filters( 'wordcount_title_tag', 'h3' );
    $content .= sprintf("<%s>%s:%s</%s>", $tag, $label,$wordn, $tag);

    return $content;
}
add_filter( 'the_content', 'wordcount_count_wordcount' );

/**
 * Count post reading time
 *
 * @param [type] $content
 * @return void
 */
function wordcount_count_reading_time($content){

    // get_post_reading_time(get_the_ID());
    // getReadingTimeAnyLanguage($content, 'bn');
    $strip_content = strip_tags($content);
    $wordn = str_word_count($strip_content);
    $reading_minute = floor($wordn / 200);
    $reading_second = floor($wordn % 200 / (200 / 60));
    $is_visible = apply_filters( 'wordcount_display_reading_time', true );
    $current_post_type = get_post_type( get_the_iD() );
    if($is_visible && $current_post_type == 'post'){
        $label = __("Total reading time","wordcount");
        $label = apply_filters( 'wordcount_reading_time_title', $label );
        $tag = apply_filters( 'wordcount_reading_time_tag', 'h3' );
        $content .= sprintf("<%s>%s : %s minute %s second</%s>", $tag, $label,$reading_minute, $reading_second, $tag);
    }
    return $content;

}
add_filter( 'the_content', 'wordcount_count_reading_time' );

function wordcount_reading_tag_change($tag){
    return $tag = 'h4';
}

add_filter( 'wordcount_reading_time_tag', 'wordcount_reading_tag_change');




function wordcount_generate_url_qrcode($content){

    $post_id = get_the_ID();
    $url =  get_the_permalink($post_id);
    $title = get_the_title( $post_id );
    $current_post_type = get_post_type( $post_id );
    $excluded_post_type = apply_filters( 'wordcount_qr_excluded_post_type', array() );

    if(in_array($current_post_type, $excluded_post_type)){
        return $content;
    }

    $height = get_option( 'qrcode_height');
    $width = get_option( 'qrcode_height');
    $height = $height ? $height : 180;
    $width = $width ? $width : 180;
    $dimension = apply_filters( 'wordcount_qr_dimension', "{$height}x{$width}");
    $imgsrc = sprintf("http://www.qr-code-generator.com/phpqrcode/getCode.php?cht=qr&chl=%s&chs=%s&choe=UTF-8&chld=L|0", $url, $dimension);
    $content .= sprintf("<div class='qrcode'><img src='%s' alt='%s' width='{$width}px' height='{$height}px'></div>", $imgsrc, $title);

    return $content;
}
add_filter( 'the_content', 'wordcount_generate_url_qrcode' );

function wordcount_qr_excluded_post_types($types){
    $types[] = 'page';
    return $types;
}

add_filter( 'wordcount_qr_excluded_post_type', 'wordcount_qr_excluded_post_types' );


// function wordcount_qr_size($size){
//     return $size = '100x100';
// }
// add_filter( 'wordcount_qr_dimension', 'wordcount_qr_size' );





/**
 * adding setting option for QR code in Dashboard
 */

 $wordcount_countries = array(
    'None',
    'Bangladesh',
    'India',
    'Nepal',
    'Vhutan',
    'Maldip',
    'Srilanka',
    'Indonesia',
    'japan',
    'China'
);
function wordcount_init(){
    global $wordcount_countries;
    $wordcount_countries = apply_filters( 'wordcount_country_list', $wordcount_countries );
}
add_action( 'init', 'wordcount_init' );


function wordcount_qrcode_settings_init(){

    add_settings_section( 'qrcode_section', __('Posts to QR Code', 'wordcount'), 'qrcode_settiongs_cbf', 'general' );
    add_settings_field( 'qrcode_height', __('QR Code Height', 'wordcount'), 'qrcode_display_field', 'general', 'qrcode_section', array('qrcode_height') );
    add_settings_field( 'qrcode_width', __('QR Code Width', 'wordcount'), 'qrcode_display_field', 'general', 'qrcode_section', array('qrcode_width') );
    add_settings_field( 'qrcode_select', __('QR Code Dropdown', 'wordcount'), 'qrcode_display_select', 'general', 'qrcode_section' );
    add_settings_field( 'qrcode_checkbox', __('QR Code Dropdown', 'wordcount'), 'qrcode_display_checkbox_field', 'general', 'qrcode_section');

    register_setting( 'general', 'qrcode_height', array('sanitize_callback'=>'esc_attr'));
    register_setting( 'general', 'qrcode_width', array('sanitize_callback'=>'esc_attr'));
    register_setting( 'general', 'qrcode_select', array('sanitize_callback'=>'esc_attr'));
    register_setting( 'general', 'qrcode_checkbox');

}

function qrcode_settiongs_cbf(){
    echo "<p> " .__('QR Code settings options','wordcount') ."</p>";
}

function qrcode_display_field($args){
    $option = get_option($args[0]);
    printf("<input type='text' id='%s' name='%s' value='%s'/>", $args[0], $args[0], $option);
}

function qrcode_display_select(){
    global $wordcount_countries;
    // $wordcount_countries = apply_filters( 'wordcount_country_list', $wordcount_countries );
    $option = get_option( 'qrcode_select' );
    printf("<select id='%s' name='%s'>", 'qrcode_select', 'qrcode_select');
    foreach($wordcount_countries as $country){
        $selected = '';
        if($option == $country) $selected = 'selected';
        printf("<option value='%s' %s >%s</option>", $country, $selected, $country);
    }
    echo "</select>";

}

function qrcode_display_checkbox_field(){
    global $wordcount_countries;
    $option = get_option( 'qrcode_checkbox');
    // $wordcount_countries = apply_filters( 'wordcount_country_list', $wordcount_countries );
    foreach($wordcount_countries as $country){
        $checked = '';
        if(is_array($option) && in_array($country, $option)){
            $checked = 'checked';
        }
        printf("<input type='checkbox' name='qrcode_checkbox[]' value='%s' %s /> %s <br>", $country, $checked, $country);
    }

}


function philosophy_regenerate_country($countries){
    array_push($countries, 'Spain');
    $countries = array_diff($countries, array('India', 'Indonesia'));
    return $countries;
}
add_filter( 'wordcount_country_list', 'philosophy_regenerate_country' );

// function qrcode_display_width(){
//     $width = get_option('qrcode_width');
//     printf("<input type='text' id='%s' name='%s' value='%s'/>", 'qrcode_width', 'qrcode_width', $width);
// }

add_action( 'admin_init', 'wordcount_qrcode_settings_init' );


function wordcount_enqueue_scripts($screen){
    if('options-general.php' == $screen){
        wp_enqueue_style( 'wordcount-maincss', plugin_dir_url(__FILE__). "/assets/css/main.css", null, time() );
        wp_enqueue_script( 'wordcount-mainjs', plugin_dir_url( __FILE__)."/assets/js/main.js", array('jquery'), time(), true);
    }
}

add_action( 'admin_enqueue_scripts', 'wordcount_enqueue_scripts' );


















// function getReadingTimeAnyLanguage($text, $language, $readingSpeed = 200) {
//     // Load the text segmentation rules for the specified language
//     $rules = \BreakIterator::createWordInstance(new \Locale($language));
  
//     // Split the text into words
//     $rules->setText($text);
//     $words = [];
//     foreach ($rules as $word) {
//       $words[] = $word;
//     }
  
//     // Count the number of words
//     $wordCount = count($words);
  
//     // Calculate the reading time
//     $readingTime = round($wordCount / $readingSpeed);
  
//     // Return the reading time in minutes
//     return $readingTime;
//   }

  
//   function get_post_reading_time($post_id) {
//     // Get the post content
//     $post = get_post($post_id);
//     $content = $post->post_content;
  
//     // Get the language of the post
//     $language = get_bloginfo('language');
  
//     // Load the text segmentation rules for the language
//     $rules = \BreakIterator::createWordInstance(new \Locale($language));
  
//     // Split the text into words
//     $rules->setText($content);
//     $words = [];
//     foreach ($rules as $word) {
//       $words[] = $word;
//     }
  
//     // Count the number of words
//     $wordCount = count($words);
  
//     // Assume an average reading speed of 200 words per minute
//     $readingTime = round($wordCount / 200);
  
//     // Return the reading time in minutes
//     return $readingTime;
//   }
  

