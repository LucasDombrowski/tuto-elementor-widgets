<?php
class RecentPostsWidget extends \Elementor\Widget_Base
{
    public $widget_slug = "recent-posts-widget";

    // Returns the unique name of the widget
    public function get_name(): string
    {
        return "recent_posts";
    }

    // Returns the title displayed in the Elementor interface
    public function get_title(): string
    {
        return __("Recent Posts", "tuto-elementor-widgets");
    }

    // Returns the icon used in the Elementor interface
    public function get_icon(): string
    {
        return "eicon-posts-grid";
    }

    protected function register_controls()
    {
        // Section for post settings
        $this->start_controls_section(
            'posts_settings',
            [
                'label' => __('Posts Settings', 'tuto-elementor-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Control for the number of posts
        $this->add_control(
            'number_of_posts',
            [
                'label' => __('Number of Posts', 'tuto-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 6,
            ]
        );

        // Control for the number of columns
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

        // Control for the order of posts
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

        // Control for showing the featured image
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

        // Control for showing the "Read More" button
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

        // Control for showing the excerpt
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

        $this->end_controls_section();

        // Section for style settings
        $this->start_controls_section(
            'style_settings',
            [
                'label' => __('Style Settings', 'tuto-elementor-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Control for title color
        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'tuto-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000',
                'selectors' => [
                    '{{WRAPPER}} .recent-post-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Control for excerpt color
        $this->add_control(
            'excerpt_color',
            [
                'label' => __('Excerpt Color', 'tuto-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#555',
                'selectors' => [
                    '{{WRAPPER}} .recent-post-excerpt' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Control for "Read More" button color
        $this->add_control(
            'read_more_button_color',
            [
                'label' => __('Read More Button Color', 'tuto-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#0073e6',
                'selectors' => [
                    '{{WRAPPER}} .recent-post-read-more' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    // Returns the styles associated with this widget
    public function get_style_depends(): array
    {
        return [tew_get_style($this->widget_slug)];
    }

    // Main function to render the widget content
    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $number_of_posts = $settings['number_of_posts'];
        $order = $settings['order'];
        $show_featured_image = $settings['show_featured_image'];
        $show_read_more = $settings['show_read_more'];
        $show_excerpt = $settings['show_excerpt'];

        $query_args = [
            'post_type' => 'post',
            'posts_per_page' => $number_of_posts,
            'orderby' => 'date',
            'order' => $order,
        ];
        $query = new \WP_Query($query_args);

        if ($query->have_posts()) : ?>
            <div class="recent-posts-grid">
                <?php
                while ($query->have_posts()): $query->the_post();
                ?>
                    <div class="recent-post-card">
                        <?php if ('yes' === $show_featured_image && has_post_thumbnail()) : ?>
                            <div class="recent-post-thumbnail">
                                <?php the_post_thumbnail('medium'); ?>
                            </div>
                        <?php endif; ?>
                        <div class="recent-post-content">
                            <h3 class="recent-post-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            <?php if ('yes' === $show_excerpt) : ?>
                                <p class="recent-post-excerpt">
                                    <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                                </p>
                            <?php endif; ?>
                            <?php if ('yes' === $show_read_more) : ?>
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
        wp_reset_postdata();
    }
}
