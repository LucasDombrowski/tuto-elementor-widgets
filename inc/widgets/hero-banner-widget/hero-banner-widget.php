<?php
class HeroBannerWidget extends \Elementor\Widget_Base
{
    // Propriété pour stocker le slug du widget.
    public $widget_slug = "hero-banner-widget";

    // Méthode pour retourner l'identifiant unique du widget.
    public function get_name(): string
    {
        return "hero_banner";
    }

    // Méthode pour retourner le titre affiché dans Elementor.
    public function get_title(): string
    {
        return __("Hero Banner", "tuto-elementor-widgets");
    }

    // Méthode pour retourner l'icône du widget dans Elementor.
    public function get_icon(): string
    {
        return "eicon-featured-image";
    }

    // Méthode pour enregistrer les sections et les contrôles du widget.
    protected function register_controls()
    {
        // Section pour configurer les contrôles liés à l'arrière-plan.
        $this->start_controls_section(
            'background',
            [
                'label' => esc_html__('Background', 'tuto-elementor-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->register_background_controls();
        $this->end_controls_section();

        // Section pour configurer les contrôles liés au texte.
        $this->start_controls_section(
            'text_section',
            [
                'label' => __('Text', 'tuto-elementor-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->register_text_controls('text');
        $this->end_controls_section();

        // Section pour configurer les contrôles liés au bouton.
        $this->start_controls_section(
            'button_section',
            [
                'label' => __('Button', 'tuto-elementor-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->register_text_controls('button');
        $this->register_button_additional_controls();
        $this->end_controls_section();
    }

    // Méthode pour enregistrer les contrôles liés à l'arrière-plan.
    private function register_background_controls()
    {
        // Contrôle pour sélectionner une image d'arrière-plan.
        $this->add_control(
            'background_image',
            [
                'label' => __('Background Image', 'tuto-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'selectors' => [
                    '{{WRAPPER}} .hero-banner-container' => 'background-image: url({{URL}});',
                ],
            ]
        );

        // Contrôle pour configurer les marges intérieures (padding) de l'arrière-plan.
        $this->add_control(
            'dimensions',
            [
                'label' => esc_html__('Dimensions', 'tuto-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .hero-banner-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Fin de la section pour les contrôles de l'arrière-plan.
        $this->end_controls_section();
    }

    // Méthode pour enregistrer les contrôles texte (réutilisée pour le texte principal et le bouton).
    private function register_text_controls(string $prefix)
    {
        // Contrôle pour définir le contenu texte.
        $this->add_control(
            $prefix . '_content',
            [
                'label' => esc_html__('Content', 'tuto-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Your content here', 'tuto-elementor-widgets'),
            ]
        );

        // Contrôle pour ajuster la taille de la police.
        $this->add_control(
            $prefix . '_font_size',
            [
                'label' => esc_html__('Font Size', 'tuto-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .hero-banner-' . $prefix => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Contrôle pour choisir la police d'écriture.
        $this->add_control(
            $prefix . '_font_family',
            [
                'label' => esc_html__('Font Family', 'tuto-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::FONT,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hero-banner-' . $prefix => 'font-family: {{VALUE}};',
                ],
            ]
        );

        // Contrôle pour définir la couleur du texte.
        $this->add_control(
            $prefix . '_color',
            [
                'label' => esc_html__('Color', 'tuto-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hero-banner-' . $prefix => 'color: {{VALUE}};',
                ],
            ]
        );
    }

    // Méthode pour enregistrer des contrôles spécifiques au bouton.
    private function register_button_additional_controls()
    {
        // Contrôle pour configurer les marges intérieures (padding) du bouton.
        $this->add_control(
            'padding',
            [
                'label' => esc_html__('Dimensions', 'tuto-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .hero-banner-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Contrôle pour définir le lien du bouton.
        $this->add_control(
            'button_link',
            [
                'label' => esc_html__('Button Link', 'tuto-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__('https://example.com', 'tuto-elementor-widgets'),
            ]
        );

        // Contrôle pour configurer la couleur d'arrière-plan du bouton.
        $this->add_control(
            'button_background_color',
            [
                'label' => esc_html__('Button Background Color', 'tuto-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hero-banner-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );
    }

    // Méthode pour définir les dépendances CSS du widget.
    public function get_style_depends(): array
    {
        return [tew_get_style($this->widget_slug)];
    }

    // Méthode pour rendre le HTML final affiché sur la page.
    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $button_link = !empty($settings['button_link']['url'])
            ? esc_url($settings['button_link']['url'])
            : '#';
        ?>

        <div class="hero-banner-container">
            <div class="hero-banner-content">
                <h1 class="hero-banner-text">
                    <?php echo esc_html($settings['text_content']); ?>
                </h1>
                <a href="<?php echo $button_link; ?>" class="hero-banner-button">
                    <?php echo esc_html($settings['button_content']); ?>
                </a>
            </div>
        </div>
        <?php
    }

    // Méthode pour le rendu en live dans Elementor.
    protected function content_template()
    {
        ?>
        <div class="hero-banner-container">
            <div class="hero-banner-content">
                <h1 class="hero-banner-text">{{{ settings.text_content }}}</h1>
                <a href="{{ settings.button_link.url }}" class="hero-banner-button">
                    {{{ settings.button_content }}}
                </a>
            </div>
        </div>
        <?php
    }
}
