<?php
new STM_LMS_Guest_Checkout;

class STM_LMS_Guest_Checkout
{
    public function __construct()
    {

        add_filter('stm_lms_buy_button_auth', array($this, 'guest_checkout'), 10, 2);

        add_action('wp_ajax_nopriv_stm_lms_add_to_cart_guest', array($this, 'guest_checkout_process'));

        add_action('wp_ajax_nopriv_stm_lms_fast_login', array($this, 'fast_login'));
        add_action('wp_ajax_nopriv_stm_lms_fast_register', array($this, 'fast_register'));
    }

    static function guest_enabled()
    {
        return STM_LMS_Options::get_option('guest_checkout', false);
    }

    function guest_checkout($atts, $course_id)
    {
        if (!self::guest_enabled()) return $atts;
        if (is_user_logged_in()) return $atts;
        return array(
            'data-guest="' . $course_id . '"'
        );
    }

    function guest_checkout_process()
    {

        check_ajax_referer('stm_lms_add_to_cart_guest', 'nonce');

        $is_woocommerce = STM_LMS_Cart::woocommerce_checkout_enabled();

        $r = array();

        if (!$is_woocommerce) {
            $r['text'] = esc_html__('Go to Cart', 'masterstudy-lms-learning-management-system');
            $r['cart_url'] = esc_url(STM_LMS_Cart::checkout_url());
        } else {

            $item_id = intval($_GET['item_id']);
            $price = STM_LMS_Course::get_course_price($item_id);

            update_post_meta($item_id, '_price', $price);
            update_post_meta($item_id, '_product_id', $item_id);
            update_post_meta($item_id, 'product_id', $item_id);

            $r['added'] = WC()->cart->add_to_cart($item_id);

            $r['text'] = esc_html__('Go to Cart', 'masterstudy-lms-learning-management-system');
            $r['cart_url'] = esc_url(wc_get_cart_url());
        }

        $r['redirect'] = STM_LMS_Options::get_option('redirect_after_purchase', false);

        wp_send_json($r);
    }

    static function get_cart_items()
    {
        $items = array();
        if (isset($_COOKIE['stm_lms_notauth_cart'])) {
            $items = self::check_cart_items($_COOKIE['stm_lms_notauth_cart']);
        }

        return $items;
    }

    static function check_cart_items($items)
    {

        $cart_items = array();

        if (empty($items)) return $cart_items;

        $items = json_decode($items, true);

        foreach ($items as $item_id) {
            if (!is_int($item_id) && get_post_type($item_id) !== 'stm-courses') continue;
            $cart_items[] = array(
                'item_id' => $item_id,
                'price' => STM_LMS_Course::get_course_price($item_id)
            );
        }

        return $cart_items;
    }

    function fast_register()
    {

        check_ajax_referer('stm_lms_fast_register', 'nonce');

        $r = array(
            'status' => 'error'
        );

        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true);

        if (!is_email($data['email'])) {
            $r['message'] = esc_html__('Enter valid email', 'masterstudy-lms-learning-management-system');
            wp_send_json($r);
        }

        $user = wp_create_user(sanitize_title($data['email']), $data['password'], $data['email']);

        if (is_wp_error($user)) {

            $r['message'] = $user->get_error_message();

        } else {

            wp_signon(array(
                'user_login' => $data['email'],
                'user_password' => $data['password'],
            ), is_ssl());

            $r['items'] = self::add_cart($user);

            $r['status'] = 'success';
            $r['message'] = esc_html__('Registration completed successfully.', 'masterstudy-lms-learning-management-system');

        }

        wp_send_json($r);
    }

    function fast_login()
    {

        check_ajax_referer('stm_lms_fast_login', 'nonce');

        $r = array(
            'status' => 'error'
        );

        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true);

        $user = wp_signon($data, is_ssl());

        if (is_wp_error($user)) {
            $r['message'] = esc_html__('Wrong Username or Password', 'masterstudy-lms-learning-management-system');
        } else {

            $r['items'] = self::add_cart($user->ID);
            $r['message'] = esc_html__('Successfully logged in. Redirecting...', 'masterstudy-lms-learning-management-system');
            $r['status'] = 'success';
        }

        wp_send_json($r);
    }

    static function add_cart($user_id)
    {
        $r = array();
        $items = self::get_cart_items();

        foreach ($items as $item) {
            $r[] = STM_LMS_Cart::_add_to_cart($item['item_id'], $user_id);
        }

        return $r;

    }

}