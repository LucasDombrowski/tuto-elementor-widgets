<?php 
/**
 * Plugin Name:     Tuto Elementor Widgets
 * Description:     My awesome description!
 * Text Domain:     tuto-elementor-widgets
 * Version:         1.0.0
 */

// Array containing the slugs of the widgets to register, the slugs correspond to the folder and file names.
$tew_widgets_slugs = ["hero-banner-widget"]; 

// Path to the directory where the widget folders are located.
$tew_widgets_path = __DIR__."/inc/widgets";

/**
 * Function to register new widgets in Elementor.
 *
 * @param \Elementor\Widgets_Manager $widgets_manager The instance of the Elementor widgets manager.
 */
function tew_register_new_widgets($widgets_manager) {
    global $tew_widgets_slugs, $tew_widgets_path;

    // Include each PHP file containing the definition of a widget from its slug.
    foreach($tew_widgets_slugs as $widget_slug){
        require_once($tew_widgets_path."/".$widget_slug."/".$widget_slug.".php");
    }

    // Initialize a list of widget class instances.
    // These classes must be defined in the files included above.
    $widgets_classes = [
        new \HeroBannerWidget(),
    ];

    // Register each widget in the Elementor widgets manager.
    foreach($widgets_classes as $widget_class){
        $widgets_manager->register($widget_class);
    }
}

/**
 * Function to register the CSS styles of the widgets.
 */
function tew_register_styles(){
    global $tew_widgets_slugs, $tew_widgets_path;

    // URI to the folder containing the plugin's widgets.
    $widgets_uri = plugin_dir_url(__FILE__)."inc/widgets";

    // Loop through each widget slug to register an associated style.
    foreach($tew_widgets_slugs as $tew_widgets_slug){
        wp_register_style(tew_get_style($tew_widgets_slug), $widgets_uri."/".$tew_widgets_slug."/style.css");
    }
}

/**
 * Function to generate a unique CSS style identifier for each widget.
 *
 * @param string $widget_slug The widget slug.
 * @return string The unique CSS style identifier.
 */
function tew_get_style(string $widget_slug): string {
    return "tew-".$widget_slug."-style";
}

// Add an action to register the widgets when Elementor loads the widgets.
add_action( 'elementor/widgets/register', 'tew_register_new_widgets');

// Add an action to register the CSS styles when scripts are loaded.
add_action("wp_enqueue_scripts", "tew_register_styles");
