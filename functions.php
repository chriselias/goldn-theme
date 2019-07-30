<?php
/**
 * Generate child theme functions and definitions
 *
 * @package Generate
 */
 

/**
 * Create options page to pull in new elementor elements
 */
require get_stylesheet_directory() . '/inc/elementor-update.php';

add_action( 'after_setup_theme', 'patient_beacon_setup' );


function patient_beacon_setup() {
    // we need to include the file below because the activate_plugin() function isn't normally defined in the front-end
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    // activate plugins to run elementor properly
    activate_plugin('elementor/elementor.php');
    activate_plugin('elementor-pro/elementor-pro.php');
    activate_plugin('gp-premium/gp-premium.php');
    activate_plugin('cdn-enabler/cdn-enabler.php');

    $cdn_enabler_active = is_plugin_active('cdn-enabler/cdn-enabler.php');
    $gp_active = is_plugin_active('gp-premium/gp-premium.php');

    $option_name = 'cdn_enabler' ;


    $new_options = array(
		'url' => 'https://d2nl5qgekpei5j.cloudfront.net',
		'dirs' => 'wp-content/uploads/',
		'excludes' => '.php',
		'relative' => 1,
		'https' => 1
    );
    
   // update cdn_enabler with correct values
    if ( get_option($option_name ) !== false ) {
         update_option( $option_name, $new_options );
    }
    
    // update generate press plugin with correct options
    if($gp_active) {
        update_option('generate_package_backgrounds', 'activated');
        update_option('generate_package_blog', 'activated');
        update_option('generate_package_colors', 'activated');
        update_option('generate_package_copyright', 'activated');
        update_option('generate_package_disable_elements', 'activated');
        update_option('generate_package_hooks', 'activated');
        update_option('generate_package_import_export', 'activated');
        update_option('generate_package_menu_plus', 'activated');
        update_option('generate_package_page_header', 'activated');
        update_option('generate_package_secondary_nav', 'activated');
        update_option('generate_package_sections', 'activated');
        update_option('generate_package_spacing', 'activated');
        update_option('generate_package_typography', 'activated');
    }
}


add_action('switch_theme', 'patient_beacon_cleanup');


// overwrite default footer text

if ( ! function_exists( 'generate_add_footer_info' ) ) :
    add_action('generate_credits','generate_add_footer_info');
    function generate_add_footer_info()
    {
        $copyright = sprintf( '<span class="copyright">&copy; %1$s</span> &bull; <a href="%2$s" target="_blank" itemprop="url">%3$s</a>',
            date( 'Y' ),
            esc_url( 'http://mylocalbeacon.com' ),
            __( 'My Local Beacon','mylocalbeacon' )
        );
        
        echo apply_filters( 'generate_copyright', $copyright );
    }
    endif;

// cleanup query params for css and scripts

// function mlb_remove_script_version( $src ){
//     return remove_query_arg( 'ver', $src );
// }

// add_filter( 'script_loader_src', 'mlb_remove_script_version' );
// add_filter( 'style_loader_src', 'mlb_remove_script_version' );

if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'Rich Snippet Settings',
		'menu_title'	=> 'Rich Snippets',
		'menu_slug' 	=> 'rich-snippet-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
	
}

// function create_acf_save_point( $path ) {
//     // update path
//     $path = get_stylesheet_directory() . '/acf';

//     // return
//     return $path;
// }

function create_acf_load_point( $paths ) {
    // update path
    $paths[] = get_stylesheet_directory() . '/acf';

    // return
    return $paths;
}

// add_filter( 'acf/settings/save_json', 'create_acf_save_point' );
 add_filter( 'acf/settings/load_json', 'create_acf_load_point' );


function script_tag_shortcode( $atts = null, $content = null ) {
    $a = shortcode_atts( array(
          'src' => null
      ), $atts );
      if($a['src'] !== null)
        return '<script src=" '. $a['src'] . ' " type="text/javascript"></script>';
      else
        return '<script>'. $content.'</script>';
  }
  function shortcodes_to_exempt_from_wptexturize( $shortcodes ) {
    $shortcodes[] = 'mlb-script';
    return $shortcodes;
}
  add_shortcode( 'mlb-script', 'script_tag_shortcode' );
  
  add_filter( 'no_texturize_shortcodes', 'shortcodes_to_exempt_from_wptexturize' );


  require 'plugin-update-checker/plugin-update-checker.php';
  $myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
      'https://github.com/chriselias/goldn-theme/',
      __FILE__,
      'GOLDN'
  );
  
  $myUpdateChecker->setBranch('production');

  function post_to_third_party( $entry, $form ) {

	//To fetch input inserted into your gravity form//
	
	///to send that fetched data to third-party api///
	$url = 'https://app.mylocalbeacon.com/api/createFormEntry';

	global $wp_version;

	// $businessId = get_field( 'businessId', 'option' );

	function get_value_by_label( $form, $entry, $label ) {
		foreach ( $form['fields'] as $field ) {
	       
			$lead_key = $field->label;
			if ( strToLower( $lead_key ) == strToLower( $label ) ) {
				return $entry[ $field->id ];
			}
		}
		return false;
	}

	$businessId = get_value_by_label( $form, $entry, 'businessId' );
	$websiteId = get_value_by_label( $form, $entry, 'websiteId' );


$response = wp_remote_post( $url, array(
	'method' => 'POST',
	'timeout' => 45,
	'redirection' => 5,
	'httpversion' => '1.0',
	'blocking' => true,
	'headers' => array(),
	'body' => array( 'entry' => $entry, 'businessId' => $businessId, 'websiteId' => $websiteId),
	'cookies' => array()
    )
);

	
	if ( is_wp_error( $response ) ) {
	   $error_message = $response->get_error_message();
	   echo "Something went wrong: $error_message";
	} else {
	//    echo 'Response:<pre>';
	//    print_r( $response );
	//    echo '</pre>';
	}
	}
	add_action( 'gform_after_submission', 'post_to_third_party', 10, 2 );