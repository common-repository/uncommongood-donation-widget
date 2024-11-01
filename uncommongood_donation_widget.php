<?php
/*
Plugin Name: UncommonGood Donation Widget
Plugin URI: https://uncommongood.io
Description: Accept donations and begin fundraising with the UncommonGood Donation Widget. The settings are very simple: one input box for the donation Widget Embed Code.
Author: UncommonGood
Tags: donation, donate, recurring donations, fundraising, nonprofit, nonprofits
Version: 1.3
License: GPLv3 or later. 
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

/** 
 * UncommonGood class
 */
class UGDW_UncommonGood_donation_widget {
    public $options; // variable to hold the options values

    /** 
     * Class constructor
     */
    public function __construct() {
        $this->options = get_option('ugdw_uncommongood_widget_options'); // get the existing plugin options
        $this->ugdw_uncommongood_register_settings_and_fields(); // invoke to register the plugin settings and admin sections
    }

    /** 
     * Generate the admin settings page
     */
    public static function ugdw_uncommongood_display_options_page() { ?>
        <div class="wrap">
            <h2>UncommonGood Donation Widget Settings</h2>
            <form method="post" action="options.php">
                <?php 
                settings_fields('ugdw_uncommongood_widget_options');
                do_settings_sections(__FILE__);
                ?>
                <p class="submit">
                    <input name="submit" type="submit" class="button-primary" value="Save Changes" />
                </p>
            </form>
        </div><?php
    }

    /**
     * Get an SVG icon by name with width, height and viewbox options.
     *
     * @param string $name The name of the icon.
     * @param int    $width The width.
     * @param int    $height The height.
     * @param string $viewbox The viewbox, will be auto-built from width and height if not set.
     *
     * @return string
     */
    static function ugdw_get_uncommongood_plugin_icon() {
        $width = 20;
        $height = 20;
        $viewbox = '-10 -6 80 80';

        $svg = sprintf(
            '<svg class="ugdw-uncommongood-widget-icon ugdw-uncommongood-widget-icon-logo" width="%1$s" height="%2$s" viewBox="%3$s" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M57.5706 64H6.56732C2.89985 64 0 61.1064 0 57.4468V6.55319C0 2.89362 2.89985 0 6.56732 0H57.5706C61.2381 0 64.1379 2.89362 64.1379 6.55319V57.4468C64.1379 61.1064 61.2381 64 57.5706 64ZM15.863 52.0855C15.5219 52.0855 15.0954 52.0004 14.7543 51.9153C13.2191 51.3196 12.4515 49.6175 13.0485 48.0855L26.439 13.7877C27.036 12.2558 28.7418 11.4898 30.277 12.0855C31.8122 12.6813 32.5798 14.3834 31.9828 15.9153L18.6776 50.2132C18.2512 51.4047 17.0571 52.0855 15.863 52.0855ZM35.0534 47.7445C35.6504 48.3403 36.418 48.5956 37.1856 48.5956C37.9532 48.5956 38.7208 48.3403 39.3179 47.7445L49.8085 37.3616C51.6849 35.4892 51.6849 32.3403 49.8085 30.468L39.3179 19.9999C38.2091 18.8084 36.3327 18.8084 35.1386 19.9999C33.9446 21.1063 33.9446 22.9786 35.1386 24.1701L44.7764 33.8722L35.0534 43.5743C33.8593 44.6807 33.8593 46.5531 35.0534 47.7445Z" fill="white"/></svg>',
            $width,
            $height,
            $viewbox,
        );

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    /** 
     * Add menu page
     */
    public static function ugdw_uncommongood_add_menu_page() {
        $wpcode_icon = self::ugdw_get_uncommongood_plugin_icon();

        add_menu_page('UncommonGood', 'UncommonGood Donation Widget', 'administrator', __FILE__, array('UGDW_UncommonGood_donation_widget','ugdw_uncommongood_display_options_page'), $wpcode_icon);
    }

    /** 
     * Register fields and sections
     */
    public function ugdw_uncommongood_register_settings_and_fields() {
        register_setting('ugdw_uncommongood_widget_options', 'ugdw_uncommongood_widget_options');
        add_settings_section('ugdw_uncommongood_campaign_settings_section', 'Donation Widget Embed Code', array($this, 'ugdw_uncommongood_embed_campaign_callback'), __FILE__);
        add_settings_field('ugdw_uncommongood_embed_campaign_instructions', 'Instructions', array($this, 'ugdw_uncommongood_embed_campaign_instructions_text'), __FILE__, 'ugdw_uncommongood_campaign_settings_section');
        add_settings_field('ugdw_uncommongood_widget_embed_code', 'Embed Code', array($this, 'ugdw_uncommongood_widget_embed_code_settings'), __FILE__, 'ugdw_uncommongood_campaign_settings_section');
    }

    /** 
     * Callback - can be used for extending features
     */
    public function ugdw_uncommongood_embed_campaign_callback() {}

    /** 
     * Campaign instructions
     */
    public function ugdw_uncommongood_embed_campaign_instructions_text() { ?>
        <p class="description">1. After signing up on <a href="https://uncommongood.io/teams" target="_blank">UncommonGood.io</a></p>
        <p class="description">2. Create a donation widget from the dashboard.</p>
        <p class="description">3. Paste the Widget Embed Code.</p>
        <p class="description">4. Use the shortcode <strong>[ug_donate]</strong> to embed the donation widget.</p>
        <?php
    }

    /** 
     * Campaign details input
     */
    public function ugdw_uncommongood_widget_embed_code_settings() { ?>
        <textarea name="ugdw_uncommongood_widget_options[ugdw_uncommongood_widget_embed_code]" class="large-text" rows="12" style="max-width: 500px"><?php echo esc_textarea(trim($this->options['ugdw_uncommongood_widget_embed_code'])); ?></textarea>
        <?php
    }
}

/** 
 * Add to admin menu
 */
function ugdw_uncommongood_add_options_page_function() {
    UGDW_UncommonGood_donation_widget::ugdw_uncommongood_add_menu_page();
}

/** 
 * Class object creation
 */
function ugdw_uncommongood_initiate_class() {
    new UGDW_UncommonGood_donation_widget();
}

/** 
 * Get the meta options file and set settings accordingly
 */
function ugdw_uncommongood_embed_code_set_plugin_meta($links, $file) {
    $plugin = plugin_basename(__FILE__);
    if ($file == $plugin) {
        return array_merge(
            $links,
            array( sprintf( '<a href="admin.php?page=%s">%s</a>', $plugin, __('Settings') ) )
        );
    }
    return $links;
}

/** 
 * Replace the shortcode with the donate button
 */
function ugdw_uncommongood_display_donate_button($atts) {
    list($token, $organizationId, $assetsUrl, $buttonUrl) = ugdw_uncommongood_extract_widge_info();

    return '<div class="wp-block-button">
    <a class="wp-block-button__link btn" href="#'.esc_url($buttonUrl, array('https', 'http')).'/widget">Donate</a>
    </div>';
}

function ugdw_uncommongood_extract_widge_info() {

    // get the existing options entries for the plugin
    $options = get_option('ugdw_uncommongood_widget_options');
    $code = $options['ugdw_uncommongood_widget_embed_code'];

    $buttonUrl = 'uncommongood.io';
    $assetsUrl = 'widget.uncommongood.io';

    // extract url for assets and for donate button
    $count = preg_match('/src=(["\'])(.*?)\1/', $code, $match);
    if ($count !== false && $count  > 0) {
        $script = $match[2] ?? $match[0];
        $script = str_replace(array("scr='", "'"), '', $script);
        $host = parse_url($script, PHP_URL_HOST);
        $host = strtolower(trim($host));

        // assets url must be the subdomain
        $assetsUrl = 'https://' . $host;

        // button url must be the root domain
        $count = substr_count($host, '.');
        if ($count === 2) {
            if(strlen(explode('.', $host)[1]) > 3) $host = explode('.', $host, 2)[1];
        } else if($count > 2) {
            $host = get_domain(explode('.', $host, 2)[1]);
        }
        $buttonUrl = 'https://' . $host;
    }

    $widgetCode = strip_tags($code, ['organization-widget']);
    if (empty(trim($widgetCode))) {
        return array(
            '',
            '',
            '',
            '',
        );
    }
    
    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $dom->loadHTML($widgetCode);

    $token = $organizationId = '';

    foreach ($dom->getElementsByTagName("organization-widget") as $widget) {
        // $extract widget token
        $token = $widget->getAttribute("data-organization-token");

        // $extract widget token
        $organizationId = $widget->getAttribute("data-id");
    }

    return array(
        $token,
        $organizationId,
        $assetsUrl,
        $buttonUrl,
    );
}

/** 
 * Load the application on header
 */
function ugdw_uncommongood_display_widget() {
    list($token, $organizationId, $assetsUrl, $buttonUrl) = ugdw_uncommongood_extract_widge_info();
    
    if (!empty($token)) {
        ?>
        <div style="display: none;">
            <organization-widget
                style="display: none;"
                id="organization-widget-<?php echo esc_attr($token); ?>"
                data-id="<?php echo esc_attr($organizationId); ?>"
                data-organization-token="<?php echo esc_attr($token); ?>"
            ></organization-widget>
        </div>
        <?php
    }
}

/*
* Load widget scripts and styles
*/
function ugdw_uncommongood_enqueue_scripts_and_styles() {
    list($token, $organizationId, $assetsUrl, $buttonUrl) = ugdw_uncommongood_extract_widge_info();
    
    if (!empty($assetsUrl)) {
        // rand version to prevent cache
        $rand = substr(md5(mt_rand()), 0, 10);

        $styleUrl = esc_url($assetsUrl . '/css/app.css', array('https', 'http'));
        wp_enqueue_style('ugdw_uncommongood_widget_style', $styleUrl, array(), $rand);

        $scriptUrl = esc_url($assetsUrl . '/js/app.js', array('https', 'http'));
        wp_enqueue_script('ugdw_uncommongood_widget_script', $scriptUrl, array(), $rand, true);
    }
}

add_shortcode('ug_donate', 'ugdw_uncommongood_display_donate_button'); // add [ug_donate] shortcode
add_action('admin_menu', 'ugdw_uncommongood_add_options_page_function'); // add item to admin menu
add_action('admin_init', 'ugdw_uncommongood_initiate_class'); // plugin initialization action
add_action('wp_head', 'ugdw_uncommongood_display_widget');
add_filter('plugin_row_meta', 'ugdw_uncommongood_embed_code_set_plugin_meta', 10, 2 ); // plugin meta options
add_filter('wp_enqueue_scripts', 'ugdw_uncommongood_enqueue_scripts_and_styles'); // plugin meta options

?>
