<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://normand.pl
 * @since      1.0.0
 *
 * @package    Nearplace
 * @subpackage Nearplace/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Nearplace
 * @subpackage Nearplace/admin
 * @author     Normand <tool@nearplace.com>
 */
class Nearplace_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    private $option_name = 'nearplace';

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of this plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    public function add_options_page()
    {
        $this->plugin_screen_hook_suffix = add_options_page(
            __('Nearplace settings', 'nearplace'),
            __('Nearplace', 'nearplace'),
            'edit_plugins',
            $this->plugin_name,
            [$this, 'display_options_page']
        );
    }

    public function display_options_page()
    {
        include_once 'partials/nearplace-admin-display.php';
    }

    public function register_settings()
    {
        add_settings_section(
            $this->option_name . '_general',
            __('General', 'nearplace'),
            [$this, $this->option_name . '_general_cb'],
            $this->plugin_name
        );

        add_settings_field(
            $this->option_name . '_oranization_uuid',
            __('Organization UUID', 'nearplace'),
            [$this, $this->option_name . '_organization_cb'],
            $this->plugin_name,
            $this->option_name . '_general',
            ['label_for' => $this->option_name . '_organization_uuid']
        );

        add_settings_field(
            $this->option_name . '_locator_uuid',
            __('Locator UUID', 'nearplace'),
            [$this, $this->option_name . '_locator_cb'],
            $this->plugin_name,
            $this->option_name . '_general',
            ['label_for' => $this->option_name . '_locator_uuid']
        );

        register_setting($this->plugin_name, $this->option_name . '_organization_uuid');
        register_setting($this->plugin_name, $this->option_name . '_locator_uuid');

        add_option($this->option_name . '_script');
    }

    public function nearplace_general_cb()
    {
        echo '<p>' . __('Please change the settings accordingly.', 'nearplace') . '</p>';
    }

    public function nearplace_organization_cb()
    {
        ?>
      <fieldset>
        <label>
          <input class="input-text regular-text" type="text"
                 name="<?php echo $this->option_name . '_organization_uuid' ?>"
                 id="<?php echo $this->option_name . '_organization_uuid' ?>"
                 value="<?php echo esc_attr(get_option($this->option_name . '_organization_uuid')) ?>"/>
          <p class="description"><?php _e('Enter your organization UUID', 'nearplace') ?></p>
        </label>
      </fieldset>
        <?php
    }

    public function nearplace_locator_cb()
    {
        ?>
      <fieldset>
        <label>
          <input class="input-text regular-text" type="text" name="<?php echo $this->option_name . '_locator_uuid' ?>"
                 id="<?php echo $this->option_name . '_locator_uuid' ?>"
                 value="<?php echo esc_attr(get_option($this->option_name . '_locator_uuid')) ?>"/>
          <p class="description"><?php _e('Enter your locator UUID', 'nearplace') ?></p>
        </label>
      </fieldset>
        <?php
    }

    public function update_script($new)
    {
        $organizationUuid = get_option($this->option_name . '_organization_uuid');
        $locatorUuid = $new;
        $script = $this->get_script($organizationUuid, $locatorUuid);

        update_option($this->option_name . '_script', $script);

        return $new;
    }

    public function get_script($organizationUuid, $locatorUuid)
    {
        $query = http_build_query([
            'organizationUuid' => $organizationUuid,
            'locatorUuid' => $locatorUuid,
            'includes' => ['script'],
        ]);
        $url = 'https://api.nearplace.com/v1/locators/' . $locatorUuid . '?' . $query;
        $response = json_decode(file_get_contents($url));
        $script = $response->data->script->data->content;

        return $script;
    }

    public function admin_notice()
    {
        ?>
      <div class="notice notice-warning is-dismissible">
        <p><?php _e('Nearplace plugin is not working properly! Please fix your config.', 'nearplace') ?></p>
      </div>
        <?php
    }

    public function show_script()
    {
        echo get_option($this->option_name . '_script');
    }
}
