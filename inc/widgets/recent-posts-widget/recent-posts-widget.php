<?php
// Classe définissant un widget Elementor pour afficher des articles récents
class RecentPostsWidget extends \Elementor\Widget_Base
{
    // Slug unique pour le widget (utilisé pour les styles ou autres dépendances)
    public $widget_slug = "recent-posts-widget";

    // Retourne le nom unique du widget (identifiant interne)
    public function get_name(): string
    {
        return "recent_posts";
    }

    // Retourne le titre affiché dans l'interface Elementor
    public function get_title(): string
    {
        return __("Recent Posts", "tuto-elementor-widgets");
    }

    // Retourne l'icône affichée dans l'interface Elementor
    public function get_icon(): string
    {
        return "eicon-posts-grid";
    }

    protected function register_controls()
    {
        // Section pour les réglages des articles
        $this->start_controls_section(
            'posts_settings',
            [
                'label' => __('Posts Settings', 'tuto-elementor-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT, // Section pour le contenu
            ]
        );

        // Contrôles pour le contenu (nombre d'articles, colonnes, etc.)
        $this->add_control(
            'number_of_posts',
            [
                'label' => __('Number of Posts', 'tuto-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 6,
            ]
        );

        $this->add_control(
            'columns',
            [
                'label' => __('Number of Columns', 'tuto-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 3,
                'min' => 1,
                'max' => 6,
                'selectors' => [
                    '{{WRAPPER}} .recent-posts-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => __('Order By Date', 'tuto-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => [
                    'DESC' => __('Newest First', 'tuto-elementor-widgets'),
                    'ASC' => __('Oldest First', 'tuto-elementor-widgets'),
                ],
            ]
        );

        $this->add_control(
            'show_featured_image',
            [
                'label' => __('Show Featured Image', 'tuto-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'tuto-elementor-widgets'),
                'label_off' => __('No', 'tuto-elementor-widgets'),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_read_more',
            [
                'label' => __('Show "Lire Plus" Button', 'tuto-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'tuto-elementor-widgets'),
                'label_off' => __('No', 'tuto-elementor-widgets'),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_excerpt',
            [
                'label' => __('Show Excerpt', 'tuto-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'tuto-elementor-widgets'),
                'label_off' => __('No', 'tuto-elementor-widgets'),
                'default' => 'yes',
            ]
        );

        $this->end_controls_section(); // Fin de la section "Content"

        // Section pour le style
        $this->start_controls_section(
            'style_settings', // Nom unique de la section
            [
                'label' => __('Style Settings', 'tuto-elementor-widgets'), // Titre de la section
                'tab' => \Elementor\Controls_Manager::TAB_STYLE, // Utilisation de TAB_STYLE pour les styles
            ]
        );

        // Contrôle pour la couleur du titre
        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'tuto-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000', // Couleur par défaut (noir)
                'selectors' => [
                    '{{WRAPPER}} .recent-post-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Contrôle pour la couleur de l'extrait
        $this->add_control(
            'excerpt_color',
            [
                'label' => __('Excerpt Color', 'tuto-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#555', // Couleur par défaut (gris)
                'selectors' => [
                    '{{WRAPPER}} .recent-post-excerpt' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Contrôle pour la couleur du bouton "Lire Plus"
        $this->add_control(
            'read_more_button_color',
            [
                'label' => __('Read More Button Color', 'tuto-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#0073e6', // Couleur par défaut
                'selectors' => [
                    '{{WRAPPER}} .recent-post-read-more' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section(); // Fin de la section "Style"
    }

    // Charge les styles CSS associés à ce widget
    public function get_style_depends(): array
    {
        return [tew_get_style($this->widget_slug)];
    }

    // Fonction principale pour afficher le contenu du widget
    protected function render()
    {
        // Récupère les paramètres définis par l'utilisateur
        $settings = $this->get_settings_for_display();

        $number_of_posts = $settings['number_of_posts']; // Nombre total d'articles à afficher
        $columns = $settings['columns'];               // Nombre de colonnes dans la grille
        $rows = isset($settings['rows']) ? $settings['rows'] : 2; // Nombre de lignes dans la grille (défaut à 2)
        $order = $settings['order'];                   // Ordre des articles (ASC/DESC)
        $show_featured_image = $settings['show_featured_image']; // Afficher l'image mise en avant
        $show_read_more = $settings['show_read_more'];           // Afficher le bouton "Lire Plus"
        $show_excerpt = $settings['show_excerpt'];           // Afficher l'extrait

        // Préparation de la requête pour récupérer les articles
        $query_args = [
            'post_type' => 'post',
            'posts_per_page' => $number_of_posts,
            'orderby' => 'date',
            'order' => $order,
        ];
        $query = new \WP_Query($query_args); // Instancie la requête WP_Query

        // Calcul du nombre total de cartes à afficher (lignes x colonnes)
        $total_posts = $columns * $rows;

        // Vérifie si des articles existent dans la requête
        if ($query->have_posts()) : ?>
            <div class="recent-posts-grid">
                <?php
                $post_count = 0; // Compteur pour limiter le nombre de posts affichés
                while ($query->have_posts() && $post_count < $total_posts) : $query->the_post();
                    $post_count++; ?>
                    <div class="recent-post-card">
                        <?php if ('yes' === $show_featured_image && has_post_thumbnail()) : ?>
                            <!-- Affiche l'image mise en avant si activée -->
                            <div class="recent-post-thumbnail">
                                <?php the_post_thumbnail('medium'); ?>
                            </div>
                        <?php endif; ?>
                        <div class="recent-post-content">
                            <!-- Affiche le titre de l'article -->
                            <h3 class="recent-post-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            <?php if ('yes' === $show_excerpt) : ?>
                                <!-- Affiche l'extrait si activé -->
                                <p class="recent-post-excerpt">
                                    <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                                </p>
                            <?php endif; ?>
                            <?php if ('yes' === $show_read_more) : ?>
                                <!-- Affiche le bouton "Lire Plus" si activé -->
                                <div class="recent-post-read-more-wrapper">
                                    <a href="<?php the_permalink(); ?>" class="recent-post-read-more">
                                        <?php _e('Lire Plus', 'tuto-elementor-widgets'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif;
        wp_reset_postdata(); // Réinitialise les données de la requête WP
    }
}
