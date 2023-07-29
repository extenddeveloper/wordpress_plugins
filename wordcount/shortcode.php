<?php


function wordcount_button($attributes){

    $default = array(
        'url' => '',
        'type' => 'primary',
        'title' => 'button'
    );
    $button_attributes = shortcode_atts( $default, $attributes );
    return sprintf("<a href='%s' class='btn btn-%s'>%s</a>", $button_attributes['url'], $button_attributes['type'], $button_attributes['title']);

}
add_shortcode( 'button', 'wordcount_button' );


function wordcount_button2($attributes, $content='Button'){

    $default = array(
        'url' => '',
        'type' => 'primary',
        'title' => 'button'
    );
    $button_attributes = shortcode_atts( $default, $attributes );
    return sprintf("<a href='%s' class='btn btn-%s'>%s</a>", $button_attributes['url'], $button_attributes['type'], do_shortcode( $content ));
}
add_shortcode( 'button2', 'wordcount_button2' );
function wordcount_uppercase($attributes, $content){
    return strtoupper(do_shortcode( $content ));
}
add_shortcode( 'uc', 'wordcount_uppercase' );

function wordcount_google_map($attributes){
    $default = array(
        'place' => 'Khailkur, Gazipur',
        'width' => '100%',
        'height'=> 500,
        'zoom'  => 14
    );

    $map_attributes = shortcode_atts( $default, $attributes);


    print_r($map_attributes['place']);
    print_r($map_attributes['width']);
    print_r($map_attributes['height']);


    $map = <<<EOD
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14586.66101073401!2d90.38703463883068!3d23.9369049519631!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c4c719157dd7%3A0x50685521ab584af1!2sKhailkur!5e0!3m2!1sen!2sbd!4v1673176267839!5m2!1sen!2sbd" width="{$map_attributes['width']}" height="{$map_attributes['height']}" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    EOD;

    return $map;

}
add_shortcode( 'gmap', 'wordcount_google_map' );