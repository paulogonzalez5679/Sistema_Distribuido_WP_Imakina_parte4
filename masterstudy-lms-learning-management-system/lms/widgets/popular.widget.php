<?php

if (!defined('ABSPATH')) exit; //Exit if accessed directly

class Stm_Lms_Popular_Courses extends WP_Widget
{

    /**
     * Register widget with WordPress.
     */
    function __construct()
    {
        parent::__construct(
            'stm_lms_popular_courses', // Base ID
            __('STM LMS Popular Courses', 'masterstudy-lms-learning-management-system'), // Name
            array('description' => __('Display your popular courses', 'masterstudy-lms-learning-management-system'),) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args Widget arguments.
     * @param array $instance Saved values from database.
     * @see WP_Widget::widget()
     *
     */
    public function widget($args, $instance)
    {
        $title = (isset($instance['title'])) ? apply_filters('widget_title', $instance['title']) : esc_html__('Courses', 'masterstudy-lms-learning-management-system');
        $output = (!empty($instance['output'])) ? apply_filters('widget_output', $instance['output']) : 3;

        echo stm_lms_filtered_output($args['before_widget']);
        if (!empty($title)) {
            echo "<div class='widget_title'><h3>{$title}</h3></div>";
        }

        $query_args = array(
            'posts_per_page' => $output,
            'post_status' => 'publish',
            'post_type' => 'stm-courses',
        );

        if (is_singular('stm-courses')) {
            $query_args['post__not_in'] = array(get_the_ID());
        }

        $r = new WP_Query($query_args);

        if ($r->have_posts()): ?>

            <ul class="stm_product_list_widget widget_woo_stm_style_2">

                <?php while ($r->have_posts()): $r->the_post();
                    $post_id = get_the_ID();
                    $meta = STM_LMS_Helpers::parse_meta_field($post_id);
                    $price = (!empty($meta['price'])) ? $meta['price'] : 0;
                    $price = (!empty($meta['sale_price'])) ? $meta['sale_price'] : $price;
                    $rates = array();
                    if (!empty($meta['course_marks'])) $rates = STM_LMS_Course::course_average_rate($meta['course_marks']);

                    ?>
                    <li>
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('img-75-75'); ?>
                            <div class="meta">
                                <div class="title h5"><?php echo stm_lms_minimize_word(get_the_title(), 37); ?></div>
                                <div class="stm_featured_product_price">
                                    <div class="price <?php echo (!empty($price)) ? esc_attr('price-pay') : esc_attr('price-free'); ?>">
                                        <?php echo (!empty($price)) ? STM_LMS_Helpers::display_price($price) : esc_html__('Free', 'masterstudy-lms-learning-management-system'); ?>
                                    </div>
                                </div>
                                <?php if (!empty($rates)): ?>
                                    <div class="rating">
                                        <div class="star-rating"><span
                                                    style="width:<?php echo (!empty($rates)) ? floatval($rates['percent']) . '%' : ''; ?>">&nbsp;</span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="expert"><?php printf(__('By %s', 'masterstudy-lms-learning-management-system'), get_the_author()); ?></div>
                            </div>
                        </a>
                    </li>
                <?php endwhile; ?>

            </ul>

        <?php endif;

        echo stm_lms_filtered_output($args['after_widget']);

        wp_reset_postdata();
    }

    /**
     * Back-end widget form.
     *
     * @param array $instance Previously saved values from database.
     * @see WP_Widget::form()
     *
     */
    public function form($instance)
    {

        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('Popular Courses', 'masterstudy-lms-learning-management-system');
        }

        if (isset($instance['output'])) {
            $output = $instance['output'];
        } else {
            $output = __('3', 'masterstudy-lms-learning-management-system');
        }

        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', 'masterstudy-lms-learning-management-system'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('output')); ?>"><?php _e('Output number:', 'masterstudy-lms-learning-management-system'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('output')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('output')); ?>" type="number"
                   value="<?php echo esc_attr($output); ?>">
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     * @see WP_Widget::update()
     *
     */
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? esc_attr($new_instance['title']) : '';
        $instance['output'] = (!empty($new_instance['output'])) ? esc_attr($new_instance['output']) : 3;

        return $instance;
    }

}

function stm_lms_register_popular_courses()
{
    register_widget('Stm_Lms_Popular_Courses');
}

add_action('widgets_init', 'stm_lms_register_popular_courses');