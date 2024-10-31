<?php
/**
* Plugin Name: Select Delivery Dates Woo
* Description: This plugin allows create Select Delivery Dates plugin.
* Version: 1.0.1
* Copyright: 2020
* Text Domain: select-delivery-dates-woo
* Domain Path: /languages 
*/

if (!defined('ABSPATH')) {
  die('-1');
}
if (!defined('OCWDD_PLUGIN_NAME')) {
  define('OCWDD_PLUGIN_NAME', 'Select Delivery Dates Woo');
}
if (!defined('OCWDD_PLUGIN_VERSION')) {
  define('OCWDD_PLUGIN_VERSION', '1.0.0');
}
if (!defined('OCWDD_PLUGIN_FILE')) {
  define('OCWDD_PLUGIN_FILE', __FILE__);
}
if (!defined('OCWDD_BASE_NAME')) {
    define('OCWDD_BASE_NAME', plugin_basename(OCWDD_PLUGIN_FILE));
}
if (!defined('OCWDD_PLUGIN_DIR')) {
  define('OCWDD_PLUGIN_DIR',plugins_url('', __FILE__));
}
if (!defined('OCWDD_DOMAIN')) {
  define('OCWDD_DOMAIN', 'select-delivery-dates-woo');
}
//Main class
//Load required js,css and other files

if (!class_exists('OCWDD_main')) {

  class OCWDD_main {

    protected static $OCWDD_instance;

           /**
       * Constructor.
       *
       * @version 3.2.3
       */
    function __construct() {
      include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
      //check plugin activted or not
      add_action('admin_init', array($this, 'OCWDD_check_plugin_state'));
    }
    //Add JS and CSS on Frontend
    function OCWDD_load_script_style(){
      wp_enqueue_script('jquery', false, array(), false, false);
      wp_enqueue_script('jquery-ui-core');//enables UI
      wp_enqueue_script('jquery-ui-datepicker');
      wp_enqueue_style('ocwdd_front_css', OCWDD_PLUGIN_DIR . '/assets/css/ocwdd-front-css.css', false, '1.0.0' );
      wp_enqueue_style('ocwdd_jquery_ui', OCWDD_PLUGIN_DIR . '/assets/css/jquery-ui.css', false, '1.0.0' );
      wp_enqueue_style('ocwdd_jquery_ui_js', OCWDD_PLUGIN_DIR . '/assets/css/jquery.timepicker.css', false, '1.0.0' );
    }

    //Add JS and CSS on Backend
    function OCWDD_load_admin_script_style() {
      wp_enqueue_style( 'ocwdd_admin_css', OCWDD_PLUGIN_DIR . '/assets/css/ocwdd-admin-css.css', false, '1.0.0' );
      wp_enqueue_script( 'ocwdd_admin_js', OCWDD_PLUGIN_DIR . '/assets/js/ocwdd-admin-js.js', array( 'jquery', 'select2') , true );
      wp_localize_script( 'ajaxloadpost', 'ajax_postajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
      wp_enqueue_style( 'woocommerce_admin_styles-css', WP_PLUGIN_URL. '/woocommerce/assets/css/admin.css',false,'1.0',"all");
      wp_enqueue_script('jquery', false, array(), false, false);
      wp_enqueue_script('jquery-ui-core');//enables UI
      wp_enqueue_script('jquery-ui-datepicker');    
       $ocwdd_array_img = OCWDD_PLUGIN_DIR;
            wp_localize_script( 'ocwdd_admin_js', 'ocwddDATA', array(
                  'ocwdd_array_img' => $ocwdd_array_img
            ) );
     
    }

    function OCWDD_show_notice() {

        if ( get_transient( get_current_user_id() . 'ocwdderror' ) ) {

          deactivate_plugins( plugin_basename( __FILE__ ) );

          delete_transient( get_current_user_id() . 'ocwdderror' );

          echo '<div class="error"><p> This plugin is deactivated because it require <a href="plugin-install.php?tab=search&s=woocommerce">WooCommerce</a> plugin installed and activated.</p></div>';

        }

    }

    function OCWDD_check_plugin_state(){
      if ( ! ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) ) {
        set_transient( get_current_user_id() . 'ocwdderror', 'message' );
      }
    }


    function OCWDD_plugin_row_meta( $links, $file ) {
            if ( OCWDD_BASE_NAME === $file ) {
                $row_meta = array(
                    'rating'    =>  '<a href="https://oceanwebguru.com/select-delivery-dates-woo/" target="_blank">Documentation</a> | <a href="https://oceanwebguru.com/contact-us/" target="_blank">Support</a> | <a href="https://wordpress.org/support/plugin/select-delivery-dates-woo/reviews/?filter=5" target="_blank"><img src="'.OCWDD_PLUGIN_DIR.'/assets/images/star.png" class="ocwdd_rating_div"></a>',
                );
                return array_merge( $links, $row_meta );
            }
            return (array) $links;
        }

    function init() {
      add_filter( 'plugin_row_meta', array( $this, 'OCWDD_plugin_row_meta' ), 10, 2 );
      add_action( 'admin_notices', array($this, 'OCWDD_show_notice'));
      add_action('admin_enqueue_scripts', array($this, 'OCWDD_load_admin_script_style'));
      add_action( 'wp_enqueue_scripts',  array($this, 'OCWDD_load_script_style'));
    }

    //Load all includes files
    function includes() {
        //Plugin Settings
        include_once('admin/ocwdd-all-settings.php');
        //For Front
        include_once('front/ocwdd-front-action.php');
    }

    //Plugin Rating
    public static function OCWDD_do_activation() {
      set_transient('ocwdd-first-rating', true, MONTH_IN_SECONDS);
    }

    public static function OCWDD_instance() {
      if (!isset(self::$OCWDD_instance)) {
        self::$OCWDD_instance = new self();
        self::$OCWDD_instance->init();
        self::$OCWDD_instance->includes();
      }
      return self::$OCWDD_instance;
    }

  }

  add_action('plugins_loaded', array('OCWDD_main', 'OCWDD_instance'));

  register_activation_hook( OCWDD_PLUGIN_FILE , array('OCWDD_main', 'OCWDD_do_activation'));
}


add_action( 'plugins_loaded', 'OCWDD_load_textdomain' );
 
function OCWDD_load_textdomain() {
  load_plugin_textdomain( 'select-delivery-dates-woo', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}

function OCWDD_load_my_own_textdomain( $mofile, $domain ) {
  if ( 'select-delivery-dates-woo' === $domain && false !== strpos( $mofile, WP_LANG_DIR . '/plugins/' ) ) {
    $locale = apply_filters( 'plugin_locale', determine_locale(), $domain );
    $mofile = WP_PLUGIN_DIR . '/' . dirname( plugin_basename( __FILE__ ) ) . '/languages/' . $domain . '-' . $locale . '.mo';
  }
  return $mofile;
}
add_filter( 'load_textdomain_mofile', 'OCWDD_load_my_own_textdomain', 10, 2 );
