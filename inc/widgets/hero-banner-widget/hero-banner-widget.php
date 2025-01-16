<?php
class HeroBannerWidget extends \Elementor\Widget_Base
{
    // Property to store the widget slug.
    public $widget_slug = "hero-banner-widget";

    // Method to return the unique identifier of the widget.
    public function get_name(): string
    {
        return "hero_banner";
    }

    // Method to return the title displayed in Elementor.
    public function get_title(): string
    {
        return __("Hero Banner", "tuto-elementor-widgets");
    }

    // Method to return the widget icon in Elementor.
    public function get_icon(): string
    {
        return "eicon-featured-image";
    }

    // Method to register the widget sections and controls.
    protected function register_controls()
    {
        /**
         * Section: Main Content (text and button)
         */
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'tuto-elementor-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->register_text_controls('text',"Title"); // Main text controls
        $this->register_text_controls('button',"Button"); // Button controls

        $this->add_control(
            'button_link',
            [
                'label' => esc_html__('Button Link', 'tuto-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__('https://example.com', 'tuto-elementor-widgets'),
            ]
        );

        $this->end_controls_section();

        /**
         * Section: Background Style
         */
        $this->start_controls_section(
            'background_style',
            [
                'label' => esc_html__('Background Style', 'tuto-elementor-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->register_background_controls(); // Background related controls

        $this->end_controls_section();

        /**
         * Section: Text Style
         */
        $this->start_controls_section(
            'text_style',
            [
                'label' => esc_html__('Text Style', 'tuto-elementor-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->register_style_controls('text'); // Style controls for main text

        $this->end_controls_section();

        /**
         * Section: Button Style
         */
        $this->start_controls_section(
            'button_style',
            [
                'label' => esc_html__('Button Style', 'tuto-elementor-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->register_style_controls('button'); // Style controls for the button

        $this->end_controls_section();
    }

    // Method to register background related controls.
    private function register_background_controls()
    {
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
    }

    // Method to register text controls (reused for main text and button).
    private function register_text_controls(string $prefix, string $label)
    {
        $this->add_control(
            $prefix . '_content',
            [
                'label' => esc_html__($label.' Content', 'tuto-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Your content here', 'tuto-elementor-widgets'),
            ]
        );
    }

    // Method to register style controls (text and button).
    private function register_style_controls(string $prefix)
    {
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

        if ($prefix === 'button') {
            $this->add_control(
                'button_background_color',
                [
                    'label' => esc_html__('Background Color', 'tuto-elementor-widgets'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .hero-banner-button' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'button_padding',
                [
                    'label' => esc_html__('Padding', 'tuto-elementor-widgets'),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em'],
                    'selectors' => [
                        '{{WRAPPER}} .hero-banner-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
        }
    }

    // Method to define the widget's CSS dependencies.
    public function get_style_depends(): array
    {
        return [tew_get_style($this->widget_slug)];
    }

    // Method to render the final HTML displayed on the page.
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

    // Method for live rendering in Elementor.
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
