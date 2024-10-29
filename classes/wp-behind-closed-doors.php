<?php

/**
 * Behind Closed Doors, a Wordpress Plugin
 * 
 * @license GPL v3
 * @version 1.0
 * 
 * @package WPBehindClosedDoors
 */
 
final class WPBehindClosedDoors {

  /**
   * Deny public instantiation
   *
   * @since 1.0
   */
  private function __construct() { }

  /**
   * Deny public cloning
   *
   * @since 1.0
   */
  private function __clone() { }

  /**
   * Basic plugin initialization
   * 
   * @param string $file Used for Wordpress plugin activation hooks
   * @since 1.0
   */
  public static function Init( $file ) {

    register_uninstall_hook( $file, array( __CLASS__, 'Uninstall' ) );
    
    add_action( 'admin_init', array( __CLASS__, 'ActionAdminInit' ) );
    add_action( 'admin_menu', array( __CLASS__, 'ActionAdminMenu' ) );
    add_action( 'init', array( __CLASS__, 'ActionInit' ) );
    
  }
  
  /**
   * Wordpress admin_init action hook
   * Basic plugin initialization
   * 
   * @since 1.0
   */
  public static function ActionAdminInit() {

    // Setup everything through the Wordpress Settings API
    
    register_setting(
      WP_BCD,
      WP_BCD_SETTING
    );
    
    add_settings_section(
      WP_BCD_SETTING_SECTION,
      __( 'General Settings', WP_BCD ),
      array( __CLASS__, 'SettingsSectionMain' ),
      WP_BCD
    );
    
    add_settings_section(
      WP_BCD_SETTING_SECTION_CUSTOM_DOOR,
      __( 'Custom Door', WP_BCD ),
      array( __CLASS__, 'SettingsSectionCustomDoor' ),
      WP_BCD
    );
    
    add_settings_field(
      WP_BCD_SETTING_FIELD_FORCE,
      __( 'Enable Behind Closed Doors?', WP_BCD ),
      array( __CLASS__, 'SettingsFieldEnableBehindClosedDoors' ),
      WP_BCD,
      WP_BCD_SETTING_SECTION
    );
    
    add_settings_field(
      WP_BCD_SETTING_FIELD_PAGE_ID,
      __( 'Select a front door page:', WP_BCD ),
      array( __CLASS__, 'SettingsFieldDoorPageID' ),
      WP_BCD,
      WP_BCD_SETTING_SECTION_CUSTOM_DOOR
    );
    
    add_settings_field(
      WP_BCD_SETTING_FIELD_SHOW_LOGIN_FORM,
      __( 'Show login form on your front door page?', WP_BCD ),
      array( __CLASS__, 'SettingsFieldShowLoginForm' ),
      WP_BCD,
      WP_BCD_SETTING_SECTION_CUSTOM_DOOR
    );
    
    add_settings_field(
      WP_BCD_SETTING_FIELD_FORCE_FRONT_DOOR_ON_LOGOUT,
      __( 'Redirect to front door on logout?', WP_BCD ),
      array( __CLASS__, 'SettingsFieldRedirectToFrontDoor' ),
      WP_BCD,
      WP_BCD_SETTING_SECTION
    );
    
  }
  
  /**
   * Wordpress admin_menu action hook
   * 
   * @since 1.0
   */
  public static function ActionAdminMenu() {

    // Add a page to the Settings menu
    add_options_page(
      WP_BCD_TITLE,
      WP_BCD_SHORT_TITLE,
      'manage_options',
      WP_BCD,
      array( __CLASS__, 'OptionsPage' )
    );
    
  }
  
  /**
   * Wordpress get_header action hook
   * Here's where the actual magic is done...
   * 
   * @since 1.0
   */
  public static function ActionGetHeader() {

    // We've already confirmed in ActionInit no user is logged in
    // and the admin is forcing the login redirect
    
    global $pagenow;
    
    if ( 'wp-login.php' === $pagenow ) // This shouldn't happen, but CYA
      return;
      
    $options = get_option( WP_BCD_SETTING );
    $pid = $options[ WP_BCD_SETTING_FIELD_PAGE_ID ];
    
    if ( !empty( $pid ) && !is_page( $pid ) ) {
      auth_redirect();
    } else if ( empty( $pid ) ) {
      auth_redirect();
    }
    
    // else we're on the page the user specified for login
    
  }
  
  /**
   * Wordpress init action hook
   * 
   * @since 1.0
   */
  public static function ActionInit() {

    global $pagenow;

    $options = get_option( WP_BCD_SETTING );
    
    // Admin has forced user login
    $has_forced_login = $options[ WP_BCD_SETTING_FIELD_FORCE ];
    
    // Admin has specified a custom login page
    $has_custom_login_page = !empty( $options[ WP_BCD_SETTING_FIELD_PAGE_ID ] );
    
    // We're also going to ignore access to admin pages,
    // thus not giving the user the ability to lock themselves out
    // of their own site accidentally by specifying a custom
    // front door and not putting a login form on it.
    // Admin access already automagically goes to wp-login.php.


    if ( 'wp-login.php' === $pagenow ) {
      // in the process of logging in
    } else if ( !is_user_logged_in() && !is_admin() ) {

      if ( $has_forced_login ) {
      
        add_action( 'get_header', array( __CLASS__, 'ActionGetHeader' ) );
        
        if ( $has_custom_login_page ) {
          add_filter( 'login_url', array( __CLASS__, 'FilterLoginURL' ), 10, 2 );
          add_filter( 'template_include', array( __CLASS__, 'FilterTemplateInclude' ) );
        }
        
      }
      
    } else if ( is_user_logged_in() ) {

      if ( $has_forced_login && $has_custom_login_page ) {
        add_filter( 'logout_url', array( __CLASS__, 'FilterLogoutURL' ), 10, 2 );
      }
      
    }
    
  }
  
  /**
   * Wordpress login_url filter hook
   * 
   * @since 1.0
   */
  public static function FilterLoginURL( $url, $redirect ) {

    // We've already confirmed in ActionInit the admin has specified a login page
    
    $options = get_option( WP_BCD_SETTING );
    
    $new_url = get_permalink( $options[ WP_BCD_SETTING_FIELD_PAGE_ID ] );
    
    if ( !empty( $redirect ) )
      $new_url .= '?redirect_to=' . $redirect;
      
    return $new_url;
  }
  
  /**
   * Wordpress logout_url filter hook
   * 
   * @since 1.1
   */
  public static function FilterLogoutURL( $url, $redirect ) {

    // We've already confirmed in ActionInit the admin has specified a login page
    
    $options = get_option( WP_BCD_SETTING );
    
    $login_url = get_permalink( $options[ WP_BCD_SETTING_FIELD_PAGE_ID ] );
    
    $new_url = $url . '&redirect_to=' . $login_url;
    
    return $new_url;
  }
  
  /**
   * Wordpress template_include filter hook
   * 
   * @since 1.0
   */
  public static function FilterTemplateInclude( $template ) {
  
    // Use the theme's login.php template, if it exists
    // otherwise use the local plugin's template
    
    if ( file_exists( get_stylesheet_directory() . '/login.php' ) ) {
      $template = get_stylesheet_directory() . '/login.php';
    } else if ( file_exists( WP_BCD_PATH . '/templates/login.php' ) ) {
      $template = WP_BCD_PATH . '/templates/login.php';
    }
    
    return $template;
  }
  
  /**
   * View for add_option_page call
   * 
   * @since 1.0
   */
  public static function OptionsPage() {
    include( WP_BCD_PATH . '/views/options.php' );
  }
  
  /**
   * Optionally renders the login form with the proper redirect
   * 
   * @since 1.0
   */
  public static function RenderLoginForm( $wp_login_form_args ) {
  
    $args = wp_parse_args( $wp_login_form_args, array(
      'redirect' => empty( $_REQUEST[ 'redirect_to' ] ) ? home_url() : $_REQUEST[ 'redirect_to' ]
    ) );
    
    // Use the plugin options to conditionally show the login form
    $options = get_option( WP_BCD_SETTING );
        
    if ( $options[ WP_BCD_SETTING_FIELD_SHOW_LOGIN_FORM ] ) {
      wp_login_form( $args );
    }

  }

  /**
   * Wordpress Settings API for our Main Section
   * 
   * @since 1.0
   */
  public static function SettingsSectionMain() {
    _e( 'Behind Closed Doors is a Wordpress plugin...', WP_BCD );
  }
  
  /**
   * Wordpress Settings API for our Custom Door Section
   * 
   * @since 1.0
   */
  public static function SettingsSectionCustomDoor() {
    _e( 'Here you can customize your front door...', WP_BCD );
  }
  
  /**
   * Wordpress Settings API for our Enable Plugin field
   * 
   * @since 1.0
   */
  public static function SettingsFieldEnableBehindClosedDoors() {
  
  	$options = get_option( WP_BCD_SETTING );
  	
  	?>
  	
  	<input type="checkbox" name="<?php echo WP_BCD_SETTING . '[' . WP_BCD_SETTING_FIELD_FORCE . ']'; ?>" <?php checked( $options[ WP_BCD_SETTING_FIELD_FORCE ], 1 ); ?> value="1">
	
  	<?php
  }
  
  /**
   * Wordpress Settings API for our Door Page ID field
   * 
   * @since 1.0
   */
  public static function SettingsFieldDoorPageID() {
  
  	$options = get_option( WP_BCD_SETTING );
  	
  	?>
  	
  	<select name="<?php echo WP_BCD_SETTING . '[' . WP_BCD_SETTING_FIELD_PAGE_ID . ']'; ?>">
  		<option value="" <?php selected( $options[ WP_BCD_SETTING_FIELD_PAGE_ID ], '' ); ?>><?php _e( 'DEFAULT: wp-login.php', WP_BCD ); ?></option>
  		<?php
  		
  		// get all published pages
  		// sort them by title
  		
  		$query = new WP_Query( array(
        'orderby' => 'title',
        'order' => 'ASC',
  		  'post_type' => 'page',
        'posts_per_page' => -1,
        'post_status' => 'publish'
  		) );
  		
  		if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
  		
  		?>
  		<option value="<?php echo get_the_ID(); ?>" <?php selected( $options[ WP_BCD_SETTING_FIELD_PAGE_ID ], get_the_ID() ); ?>><?php the_title(); ?></option>
  		<?php
  		
  		endwhile; endif;
  		
  		wp_reset_postdata();
  		
  		?>
  	</select>
  	
  	<?php
  }
  
  /**
   * Wordpress Settings API for our Show Login Form field
   * 
   * @since 1.0
   */
  public static function SettingsFieldShowLoginForm() {
  
  	$options = get_option( WP_BCD_SETTING );
  	
  	?>
  	
  	<input type="checkbox" name="<?php echo WP_BCD_SETTING . '[' . WP_BCD_SETTING_FIELD_SHOW_LOGIN_FORM . ']'; ?>" <?php checked( $options[ WP_BCD_SETTING_FIELD_SHOW_LOGIN_FORM ], 1 ); ?> value="1">
	
  	<?php
  }
  
  /**
   * Wordpress Settings API for redirecting the logout action to the front door
   *
   * @since 1.1
   */
  public static function SettingsFieldRedirectToFrontDoor() {
  
  	$options = get_option( WP_BCD_SETTING );
  	
  	?>
  	
  	<input type="checkbox" name="<?php echo WP_BCD_SETTING . '[' . WP_BCD_SETTING_FIELD_FORCE_FRONT_DOOR_ON_LOGOUT . ']'; ?>" <?php checked( $options[ WP_BCD_SETTING_FIELD_FORCE_FRONT_DOOR_ON_LOGOUT ], 1 ); ?> value="1">
	
  	<?php
  }
  
  /**
   * Wordpress plugin register uninstall hook
   * 
   * @since 1.0
   */
  public static function Uninstall() {
    unregister_setting(
      WP_BCD,
      WP_BCD_SETTING
    );
  }
  
}