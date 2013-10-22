<?php
class CartWidget extends WP_Widget
{
    function __construct()
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
        require(XPERT_PATH.'/items.php');
        echo $after_widget;

    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }

    function form($instance)
    {


        $defaults = array('title' => __('Example', 'example'));
        $instance = wp_parse_args((array)$instance, $defaults); ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title'); ?>:</label>
            <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>"
                   value="<?php echo $instance['title']; ?>" style="width:100%;"/>
        </p>
    <?php
    }
}

class ConversionRateWidget extends WP_Widget
{
    function __construct()
    {
        $widget_ops = array('classname' => 'example', 'description' => __('Курс валют', 'example'));
        $control_ops = array('id_base' => 'conversion-rate-widget');
        $this->WP_Widget('conversion-rate-widget', __('Курс валют', 'example'), $widget_ops, $control_ops);
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


        $rate = get_option('xpert_conversion_rate');
        require ('conversion_rate.php');


        echo $after_widget;

    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }

    function form($instance)
    {
        $defaults = array('title' => 'Курс валют');
        $instance = wp_parse_args((array)$instance, $defaults); ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title'); ?>:</label>
            <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>"
                   value="<?php echo $instance['title']; ?>" style="width:100%;"/>
        </p>
    <?php
    }
}

