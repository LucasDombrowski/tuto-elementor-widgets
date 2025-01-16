<?php
class ListWidget extends \Elementor\Widget_Base
{
    // Property to store the widget's slug.
    public $widget_slug = "list-widget";

    // Method to return the widget's unique identifier.
    public function get_name(): string
    {
        return "list";
    }

    // Method to return the title displayed in Elementor.
    public function get_title(): string
    {
        return __("List", "tuto-elementor-widgets");
    }

    // Method to return the widget's icon in Elementor.
    public function get_icon(): string
    {
        return "eicon-post-list";
    }

    // Method to register the widget's sections and controls.
    protected function register_controls()
    {
        // Section to configure controls for the list items.
        $this->start_controls_section(
            'list_section',
            [
                'label' => esc_html__('List', 'tuto-elementor-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->register_list_controls();
        $this->end_controls_section();

        // Section to configure the icon styles of the list.
        $this->start_controls_section(
            'section_icon_style',
            [
                'label' => esc_html__('Icon', 'tuto-elementor-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->register_icon_style();
        $this->end_controls_section();

        // Section to configure the title styles of the list.
        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__('Title', 'tuto-elementor-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->register_title_style();
        $this->end_controls_section();

        // Section to configure the content styles of the list.
        $this->start_controls_section(
            'section_content_style',
            [
                'label' => esc_html__('Content', 'tuto-elementor-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->register_content_style();
        $this->end_controls_section();
    }

    // Method to register the controls related to the list items.
    private function register_list_controls()
    {
        // Control to configure the list of list items.
        $this->add_control(
			'list',
			[
				'label' => __( 'Repeater List', 'tuto-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => [
					[
						'name' => 'list_title',
						'label' => esc_html__( 'Title', 'tuto-elementor-widgets' ),
						'type' => \Elementor\Controls_Manager::TEXT,
						'default' => esc_html__( 'List Title' , 'tuto-elementor-widgets' ),
					],
					[
						'name' => 'list_icon',
						'label' => esc_html__( 'Icon', 'tuto-elementor-widgets' ),
						'type' => \Elementor\Controls_Manager::ICONS,
                        'default' => [
                            'value' => 'fas fa-check',
                            'library' => 'fa-solid',
                        ],
					],
					[
                        'name' => 'list_content',
						'label' => esc_html__( 'Content', 'tuto-elementor-widgets' ),
						'type' => \Elementor\Controls_Manager::TEXTAREA,
                        'default' => esc_html__( 'List Content' , 'tuto-elementor-widgets' ),
                        'show_label' => false,
					]
				],
                'default' => [
					[
						'list_title' => esc_html__( 'Title', 'tuto-elementor-widgets' ),
						'list_icon' => [
							'value' => 'fas fa-dot-circle',
							'library' => 'fa-solid',
						],
						'list_content' => esc_html__( 'Content', 'tuto-elementor-widgets' ),
					]
				],
				'title_field' => '{{{ elementor.helpers.renderIcon( this, list_icon, {}, "i", "panel" ) || \'<i class="{{ list_icon }}" aria-hidden="true"></i>\' }}} {{{ list_title }}}',
			]
		);
    }

    private function register_icon_style()
    {
        // Control to configure the color of the list icons.
        $this->add_control(
			'icon_color_hover',
			[
				'label' => esc_html__( 'Color', 'tuto-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .list_elt_icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .list_elt_icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

        // Control to configure the size of the list icons.
        $this->add_control(
            "icon_size",
            [
                'label' => esc_html__( 'Size', 'tuto-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'size' => 20,
				],
                'range' => [
					'px' => [
						'min' => 10,
                        'max' => 100,
					],
				],
                'selectors' => [
					'{{WRAPPER}}' => '--custom-list-icon-size: {{SIZE}}{{UNIT}};',
				],
            ]
        );

        // Control to configure the spacing next to the list icons.
        $this->add_control(
            "icon_gap",
            [
                'label' => esc_html__( 'Gap', 'tuto-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'size' => 10,
				],
                'range' => [
					'px' => [
						'min' => 0,
                        'max' => 100,
					],
				],
                'selectors' => [
					'{{WRAPPER}} .list_elt_icon' => 'padding-right: {{SIZE}}{{UNIT}};',
				],
            ]
        );
    }

    // Method to register the controls related to the title styles of the list.
    private function register_title_style()
    {
        // Control to configure the size, font, spacing... of the list title.
        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .list_elt_title',
			]
		);

        // Control to configure the color of the list title.
        $this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .list_elt_title' => 'color: {{VALUE}};',
				],
			]
		);
    }

    // Method to register the controls related to the content styles of the list.
    private function register_content_style()
    {
        // Control to configure the size, font, spacing... of the list content.
        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .list_elt_content',
			]
		);

        // Control to configure the color of the list content.
        $this->add_control(
			'content_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .list_elt_content' => 'color: {{VALUE}};',
				],
			]
		);
    }

    // Method to define the CSS dependencies for the widget.
    public function get_style_depends(): array
    {
        return [tew_get_style($this->widget_slug)];
    }

    // Method to render the final HTML to be displayed on the page.
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>

        <div class="list-container">
            <?php foreach ($settings['list'] as $elt) { ?>
                <div class="list_elt">
                    <div class="list_elt_infos">
                        <div class="list_elt_icon">
                            <?php \Elementor\Icons_Manager::render_icon( $elt['list_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                        </div>
                        <h1 class="list_elt_title"><?= $elt["list_title"] ?></h1>
                        <p class="list_elt_content"><?= $elt["list_content"] ?></p>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php
    }

    // Method for live rendering in Elementor.
    protected function content_template()
    {
        ?>
        <div class="list-container">
            <# _.each(settings.list, function(elt) { #>
                <div class="list_elt">
                    <div class="list_elt_infos">
                        <div class="list_elt_icon">
                            <i class="{{ elt.list_icon.value }}" aria-hidden="true"></i>
                        </div>
                        <h1 class="list_elt_title">{{ elt.list_title }}</h1>
                        <p class="list_elt_content">{{ elt.list_content }}</p>
                    </div>
                </div>
            <# }); #>
        </div>
        <?php
    }
}