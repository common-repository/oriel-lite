<?php
/*
Plugin Name: Oriel Lite
Plugin URI: https://oriel.io/wordpress/
Description: Oriel enables your website to collect ad block analytics and to communicate with your ad blocking audience.
Version: 1.1
Author: Oriel Ventures Limited
Author URI: https://oriel.io/
License: GPLv2
*/

if (!defined('WPINC')) {
    die;
}

define('ORIEL__PLUGIN_DIR', plugin_dir_path(__FILE__));
require_once(ORIEL__PLUGIN_DIR . 'orielio/settings.php');
require_once(ORIEL__PLUGIN_DIR . 'orielio/util.php');
require_once(ORIEL__PLUGIN_DIR . 'orielio/api.php');
require_once(ORIEL__PLUGIN_DIR . 'orielio/crypto.php');
require_once(ORIEL__PLUGIN_DIR . 'options.php');


/**
 * Class OrielWP - WP Plugin entry point
 */
class OrielWP
{
    public $plugin_name;
    public $plugin_slug;
    public $version;

    /**
     * Oriel Constructor
     */
    public function __construct()
    {
        $this->plugin_name = 'Oriel Lite';
        $this->plugin_slug = 'oriel-wp-lite';
        $this->version = '1.1';
        $this->_init();
    }

    /**
     * Hooks initializer
     */
    private function _init()
    {
        if(!is_admin() && !in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'))) {

            // Head scripts hook, used to inject head script
            add_action('wp_print_styles', array($this, 'process_head'), 0);
            
        }
        elseif(is_admin()) {
            // Register plugin action links
            add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array($this, 'add_action_links'));

            // Register cleanup hook
            register_deactivation_hook( __FILE__, array($this, 'deactivate_plugin_hook'));

            // Add notices hook
            add_action('admin_notices', array($this, 'admin_notices'));
        }
    }

    public function process_head()
    {
        $script = OrielIO\API::get_head_script();
        if($script) {
            echo '<script type="text/javascript">'.$script.'</script>';
        }
    }

    /**
     * Notifies admin with current status
     */
    public function admin_notices()
    {
        global $hook_suffix, $oriel_cache;
        if ($hook_suffix == 'plugins.php' && !$oriel_cache->get('head_key') && !OrielIO\API::get('/domain/') ) {
            ?>
                <style>
                    .oriel-notice {
                        padding: 10px;
                        background-color: #6BC2B9;
                        border: 0;
                        color: #FFF;
                        font-size: 16px;
                        border-radius: 5px;
                    }
                    .oriel-action:visited, .oriel-action:link{
                        display: inline-block;
                        padding: 15px 25px;
                        margin: 0 20px 0 0;
                        color: #333;
                        background-color: #FED47A;
                        border: 3px solid #FFF;
                        text-decoration: none;
                        font-size: 16px;
                        font-weight: bold;
                        border-radius: 5px;
                    }
                    .oriel-action:hover {
                        background-color: #feb963;
                        border-color: #EEE;
                    }
                </style>
                <div class="notice oriel-notice">
                    <a href="<?php echo admin_url( 'options-general.php?page=oriel-lite-settings-admin' )?>" class="oriel-action"> Activate your Oriel account</a>
                    Just one more step to get Oriel going on your website!
                </div>
            <?php
        }

        if (!function_exists('curl_init')) {
            // Notify admin if curl is not installed
            $msg = __('PHP cURL extension not installed! Please make sure you have cURL installed in order for Oriel Plugin to work!', 'sample-text-domain');
            printf('<div class="notice notice-error"><p>%1$s</p></div>', $msg);
        }
    }

    /**
     * Adds settings shortcut to Oriel in plugins list
     *
     * @param string[] $links Oriel links already available
     * @return string[] The Oriel links with added settings shortcut
     */
    public function add_action_links ($links) {
        $settings_link = '<a href="' . admin_url( 'options-general.php?page=oriel-lite-settings-admin' ) . '">Settings</a>';
        array_unshift($links, $settings_link);
        return $links;
    }
    

    /**
     * Cleanup method ran at plugin deactivation
     */
    public function deactivate_plugin_hook()
    {
        global $oriel_cache;
        $oriel_cache->erase();
    }
}

$orielwp = new OrielWP();

?>
