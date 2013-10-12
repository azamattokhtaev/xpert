<?php
/**
 * @package XPert
 * @version 1.6
 */
/*
Plugin Name: XPert
Plugin URI: http://wordpress.org/plugins/hello-dolly/
Description: Custom plugin for Alex Davayan
Author: Azamat Tokhtaev
Version: 0.1
Author URI: http://azamatpsw.blogspot.com
*/
define('XPERT_PATH', dirname(__FILE__));
require_once('cart.php');

class XPert
{
    public function __construct()
    {
        session_start();
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
//        add_action( 'generate_rewrite_rules', array($this, 'add_rewrite_rules') );
        add_action('init', array($this, 'registerXPertPostType'));
        add_action("admin_init", array($this, "initAdmin"));
        add_action('save_post', array($this, 'saveMetaInformation'));
        add_filter('manage_xpert_product_posts_columns', array($this, 'getProductColumnsHead'));
        add_action('manage_xpert_product_posts_custom_column', array($this, 'getProductColumnsContent'), 1, 2);
        add_action('parse_request', array($this, 'custom_requests'));
        add_filter('query_vars', array($this, 'custom_query_vars'));
    }

    public function activate()
    {
        global $wp_rewrite;
        $wp_rewrite->flush_rules(); // force call to generate_rewrite_rules()

    }

    public function deactivate()
    {
        remove_action('generate_rewrite_rules', array($this, 'add_rewrite_rules'));
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
    }


    public function custom_query_vars($vars)
    {
        $vars[] = 'add_to_cart';
        $vars[] = 'next';
        $vars[] = 'clear_cart';
        $vars[] = 'cart_remove';
        $vars[] = 'print';
        return $vars;
    }

    function custom_requests($wp)
    {

        if (!empty($wp->query_vars['add_to_cart'])) {
            $item_id = $wp->query_vars['add_to_cart'];
            $post = get_post($item_id);
            $cart = new XPertCart();
            $cart->addItem($post);
            wp_redirect($wp->query_vars['next']);
            exit;
        } else if (!empty($wp->query_vars['clear_cart'])) {
            $cart = new XPertCart();
            $cart->clear();
            wp_redirect($wp->query_vars['next']);
            exit;
        } else if (!empty($wp->query_vars['cart_remove'])) {
            $cart = new XPertCart();
            $cart->remove($wp->query_vars['cart_remove']);
            wp_redirect($wp->query_vars['next']);
            exit;
        }else if (!empty($wp->query_vars['print'])) {


            require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');

            $html2pdf = new HTML2PDF('P','A4','ru');

            $cart = new XPertCart();
            $cart_items = $cart->getItemsForDisplay();
            ob_start();
            require ('checkout_pdf.php');
            $content = ob_get_clean();

            $html2pdf->pdf->SetDisplayMode('real');
            $html2pdf->WriteHTML($content);
            $html2pdf->Output('example.pdf');

            readfile('example.pdf');
//            $cart->remove($wp->query_vars['cart_remove']);
//            wp_redirect($wp->query_vars['next']);
            exit;
        }

    }

    public function registerXPertPostType()
    {
        $labels = array(
            'name' => _x('Products', 'post type general name'),
            'singular_name' => _x('Product', 'post type singular name'),
            'add_new' => _x('Add New', 'book'),
            'add_new_item' => __('Add New Product'),
            'edit_item' => __('Edit Product'),
            'new_item' => __('New Product'),
            'all_items' => __('All Products'),
            'view_item' => __('View Product'),
            'search_items' => __('Search Products'),
            'not_found' => __('No products found'),
            'not_found_in_trash' => __('No products found in the Trash'),
            'parent_item_colon' => '',
            'menu_name' => __('Products')
        );
        $args = array(
            'labels' => $labels,
            'description' => 'Holds our products and product specific data',
            'public' => true,
            'menu_position' => 5,
            'capability_type' => 'post',
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'comments', 'tag', 'post-formats'),
            'has_archive' => true,
            'rewrite' => true,
            'taxonomies' => array('post_tag'),

        );
        register_post_type('xpert_product', $args);

        register_taxonomy("catalog",
            array("xpert_product"),
            array("hierarchical" => true,
                "label" => "Catalogs",
                "singular_label" =>
                __("Catalog"),
                "rewrite" => true,
                'show_ui' => true,
                'show_admin_column' => true,
                'query_var' => true));

    }

    public function initAdmin()
    {
        add_meta_box("prod-info-meta", "Product Information", array($this, "getMetaInfoOptions"), "xpert_product", "normal", "high");
    }

    public function saveMetaInformation()
    {
        global $post;
        update_post_meta($post->ID, "price", $_POST["price"]);
    }


    public function getProductColumnsHead($defaults)
    {
        $defaults['price'] = __('Price');
        return $defaults;
    }

    public function getProductColumnsContent($column_name, $post_ID)
    {
        if ($column_name == 'price') {
            $meta = get_post_custom($post_ID);
            echo $meta['price'][0];
        }
    }

    public function getMetaInfoOptions()
    {
        global $post;
        $custom = get_post_custom($post->ID);
        $price = $custom['price'][0];
        ?>

        <label><?php echo __('Price', 'xpert'); ?>:</label><input name="price" type="text"
                                                                  value="<?php echo $price ?>"/>
    <?php
    }


}

// Now we set that function up to execute when the admin_notices action is called


function xpert_display_price($price)
{
    return "$" . $price;
}

$application = new XPert();


add_action('widgets_init', 'register_cart_widget'); // function to load my widget

function register_cart_widget()
{
    register_widget('CartWidget');

} // function to register my widget

class CartWidget extends WP_Widget
{
    function CartWidget()
    {
        $widget_ops = array('classname' => 'example', 'description' => __('User cart widget', 'example'));
        $control_ops = array('id_base' => 'cart-widget');
        $this->WP_Widget('cart-widget', __('Cart', 'example'), $widget_ops, $control_ops);
    }

    function widget($args, $instance)
    {
        $before_widget = "";
        $before_title = "";
        $after_title = "";
        $after_widget = "";
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        echo $before_widget;
        if ($title)
            echo $before_title . $title . $after_title;

        $cart = new XPertCart();
        $cart_items = $cart->getItemsForDisplay();
        require('items.php');

        echo $after_widget;

    } // display the widget
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        //Strip tags from title and name to remove HTML
        $instance['title'] = strip_tags( $new_instance['title'] );
        return $instance;
    }  // update the widget

    function form($instance)
    {

        //Set up some default widget settings.
        $defaults = array( 'title' => __('Example', 'example'));
        $instance = wp_parse_args( (array) $instance, $defaults ); ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title'); ?>:</label>
            <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
        </p>
    <?php
    } // and of course the form for the widget options
} // The example widget class

add_filter('the_content', 'my_content_filter');
function my_content_filter($content)
{
    if (is_page('checkout')){
        $cart = new XPertCart();
        $cart_items = $cart->getItemsForDisplay();
        ob_start();
        require ('checkout.php');
        $content = ob_get_clean();
    }
    return $content;
}

function xpert_convert_price($price)
{
    return $price*49.5;
}