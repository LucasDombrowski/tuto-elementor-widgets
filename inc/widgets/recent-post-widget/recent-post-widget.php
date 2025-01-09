<?php
class RecentPostWidget extends \Elementor\Widget_Base
{
    public $widget_slug = "recent-post-widget";

    public function get_name(): string
    {
        return "recent_posts";
    }

    public function get_title(): string
    {
        return __("Recent Posts", "tuto-elementor-widgets");
    }

    public function get_icon(): string
    {
        return "eicon-posts-grid";
    }

    protected function register_controls()
    {
        // Section pour la configuration des posts
        $this->start_controls_section(
            'posts_settings',
            [
                'label' => __('Posts Settings', 'tuto-elementor-widgets'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

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
            ]
        );

        $this->add_control(
            'rows',
            [
                'label' => __('Number of Rows', 'tuto-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 2,
                'min' => 1,
                'max' => 10,
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

        $this->end_controls_section();
    }

    public function get_style_depends(): array
    {
        return [tew_get_style($this->widget_slug)];
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $number_of_posts = $settings['number_of_posts'];
        $columns = $settings['columns'];
        $rows = $settings['rows'];
        $order = $settings['order'];
        $show_featured_image = $settings['show_featured_image'];
        $show_read_more = $settings['show_read_more'];

        $query_args = [
            'post_type' => 'post',
            'posts_per_page' => $number_of_posts,
            'orderby' => 'date',
            'order' => $order,
        ];
        $query = new \WP_Query($query_args);

        $total_posts = $columns * $rows;

        if ($query->have_posts()) : ?>
            <div class="recent-posts-grid" style="grid-template-columns: repeat(<?php echo esc_attr($columns); ?>, 1fr);">
                <?php
                $post_count = 0;
                while ($query->have_posts() && $post_count < $total_posts) : $query->the_post();
                    $post_count++; ?>
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
                            <p class="recent-post-excerpt">
                                <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                            </p>
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
