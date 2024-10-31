<?php

class OrielSettingsPage
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
            'Settings Admin',
            'Oriel',
            'manage_options',
            'oriel-lite-settings-admin',
            array($this, 'create_admin_page')
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        global $oriel_cache;
        $cache = $oriel_cache;
        // Set class property
        $this->options = get_option('oriel_lite_options');
        ?>
        <style>
            .wrap {
                width:800px;
            }
            .wrap h1 {
                margin-bottom: 20px !important;
            }
            .oriel-notice {
                padding: 20px;
                background-color: #6BC2B9;
                border: 0;
                color: #FFF;
                font-size: 16px;
                border-radius: 5px;
                overflow: hidden;
            }
            .oriel-notice.oriel-notice h2 {
                margin: 5px 0 10px 0;
                color: #FFF;
            }
            .oriel-action:visited, .oriel-action:link{
                display: inline-block;
                padding: 15px 25px;
                color: #333;
                background-color: #FED47A;
                border: 3px solid #FFF;
                text-decoration: none;
                font-size: 16px;
                font-weight: bold;
                border-radius: 5px;
                float:right;
            }
            .oriel-action:hover {
                background-color: #feb963;
                border-color: #EEE;
            }
            .oriel-container {
                background: #FFF;
                padding: 20px 20px 5px 20px;
                border-radius: 5px;
            }
        </style>
        <div class="wrap">
            <h1>Oriel Configuration</h1>
            <?php
            if(!$cache->get('head_key') && !OrielIO\API::get('/domain/')) {
            ?>
            <div class="notice oriel-notice">
                <div style="float:left">
                    <h2>Oriel Not Active</h2>
                    You must first login or signup to Oriel in order to enable it on your website.
                </div>
                <a href="<?php echo OrielIO\Crypto::get_signup_url();?>" class="oriel-action" target="_blank">Get an API Key</a>
            </div>
            <?php
        } else { ?>
                <div class="notice oriel-notice">
                    <h2>Oriel is active!</h2>
                </div>
            <?php } ?>
            <div class="oriel-container">
                <div style="font-size:12pt;">Please provide your API Key below:</div>
                <form method="post" action="options.php">
                    <?php
                    // This prints out all hidden setting fields
                    settings_fields('oriel_lite_option_group');
                    do_settings_sections('oriel-lite-settings-admin');
                    submit_button('Save');
                    ?>
                </form>
            </div>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        register_setting(
            'oriel_lite_option_group', // Option group
            'oriel_lite_options', // Option name
            array($this, 'sanitize') // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            '', // Title
            array($this, 'print_section_info'), // Callback
            'oriel-lite-settings-admin' // Page
        );


        add_settings_field(
            'api_key',
            'Website API Key',
            array($this, 'api_key_callback'),
            'oriel-lite-settings-admin',
            'setting_section_id'
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     * @return array Sanitized text fields as array keys
     */
    public function sanitize($input)
    {
        global $oriel_cache;
        $cache = $oriel_cache;
        // clean cache
        $cache->delete('head_key', $commit=false);
        $cache->delete('head_script', $commit=true);

        $new_input = array();
        if (isset($input['api_key']))
            $new_input['api_key'] = sanitize_text_field($input['api_key']);

        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function print_section_info()
    {
        print '';
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function api_key_callback()
    {
        printf(
            '<input type="text" id="api_key" name="oriel_lite_options[api_key]" value="%s" style="width:550px;"/>',
            isset($this->options['api_key']) ? esc_attr($this->options['api_key']) : ''
        );
    }
}

if (is_admin()) {
    $oriel_settings_page = new OrielSettingsPage();
}
