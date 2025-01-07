<?php 
/**
 * Plugin Name:     Tuto Elementor Widgets
 * Description:     Ma superbe description !
 * Text Domain:     tuto-elementor-widgets
 * Version:         1.0.0
 */

// Tableau contenant les slugs des widgets à enregistrer, les slugs correspondent aux noms de dossiers et fichiers.
$tew_widgets_slugs = ["hero-banner-widget"]; 

// Chemin vers le répertoire où sont situés les dossiers des widgets.
$tew_widgets_path = __DIR__."/inc/widgets";

/**
 * Fonction pour enregistrer de nouveaux widgets dans Elementor.
 *
 * @param \Elementor\Widgets_Manager $widgets_manager L'instance du gestionnaire de widgets d'Elementor.
 */
function tew_register_new_widgets($widgets_manager) {
    global $tew_widgets_slugs, $tew_widgets_path;

    // Inclut chaque fichier PHP contenant la définition d'un widget à partir de son slug.
    foreach($tew_widgets_slugs as $widget_slug){
        require_once($tew_widgets_path."/".$widget_slug."/".$widget_slug.".php");
    }

    // Initialise une liste d'instances de classes de widgets. 
    // Ces classes doivent être définies dans les fichiers inclus ci-dessus.
    $widgets_classes = [
        new \HeroBannerWidget(),
    ];

    // Enregistre chaque widget dans le gestionnaire de widgets d'Elementor.
    foreach($widgets_classes as $widget_class){
        $widgets_manager->register($widget_class);
    }
}

/**
 * Fonction pour enregistrer les styles CSS des widgets.
 */
function tew_register_styles(){
    global $tew_widgets_slugs, $tew_widgets_path;

    // URI vers le dossier contenant les widgets du plugin.
    $widgets_uri = plugin_dir_url(__FILE__)."inc/widgets";

    // Parcourt chaque slug de widget pour enregistrer un style associé.
    foreach($tew_widgets_slugs as $tew_widgets_slug){
        wp_register_style(tew_get_style($tew_widgets_slug), $widgets_uri."/".$tew_widgets_slug."/style.css");
    }
}

/**
 * Fonction pour générer un identifiant de style CSS unique pour chaque widget.
 *
 * @param string $widget_slug Le slug du widget.
 * @return string L'identifiant unique du style CSS.
 */
function tew_get_style(string $widget_slug): string {
    return "tew-".$widget_slug."-style";
}

// Ajoute une action pour enregistrer les widgets lorsque Elementor charge les widgets.
add_action( 'elementor/widgets/register', 'tew_register_new_widgets');

// Ajoute une action pour enregistrer les styles CSS lors du chargement des scripts.
add_action("wp_enqueue_scripts", "tew_register_styles");
