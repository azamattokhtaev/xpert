<?php
class XPertSettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('admin_init', array($this, 'page_init'));
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
// This page will be under "Settings"
        add_options_page(
            'XPert Settings Admin',
            'XPert Settings',
            'manage_options',
            'xpert-setting-admin',
            array($this, 'create_admin_page')
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
// Set class property
        $this->options = get_option('xpert_conversion_rate');
        ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2>My Settings</h2>

            <form method="post" action="options.php">
                <?php
                // This prints out all hidden setting fields
                settings_fields('xpert_option_group');
                do_settings_sections('my-setting-admin');
                submit_button();
                ?>
            </form>
        </div>
    <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        register_setting(
            'xpert_option_group', // Option group
            'xpert_conversion_rate', // Option name
            array($this, 'sanitize') // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'My Custom Settings', // Title
            array($this, 'print_section_info'), // Callback
            'my-setting-admin' // Page
        );

        add_settings_field(
            'id_conversion_rate', // ID
            'Курс доллара', // Title
            array($this, 'conversion_rate_callback'), // Callback
            'my-setting-admin', // Page
            'setting_section_id' // Section
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize($input)
    {
        $new_input = array();
        if (isset($input['conversion_rate']))
            $new_input['conversion_rate'] = floatval($input['conversion_rate']);


        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function conversion_rate_callback()
    {

        printf(
            '<input type="text" id="id_conversion_rate" name="xpert_conversion_rate[conversion_rate]" value="%s" />',
            isset($this->options['conversion_rate']) ? esc_attr($this->options['conversion_rate']) : ''
        );
    }

}

if (is_admin())
    $my_settings_page = new XPertSettingsPage();