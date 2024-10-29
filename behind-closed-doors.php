<?php

/**
 * Plugin Name: Behind Closed Doors
 * Plugin URI: http://spencersokol.com/projects/behind-closed-doors/
 * Description: Keeps your entire site behind a page, with or without a login form, should you desire it so.
 * License: GPL v3
 * Author: Spencer Sokol
 * Author URI: http://studio27indy.com/
 * Version: 1.1
 *
 * @license GPL3
 * @version 1.1
 *
 * @package WPBehindClosedDoors
 * 
 *
 * 
 * Copyright (C) 2014  Spencer Sokol
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */

define( 'WP_BCD', 'wp-behind-closed-doors' );
define( 'WP_BCD_TITLE', __( 'Behind Closed Doors, a Wordpress Plugin', WP_BCD ) );
define( 'WP_BCD_SHORT_TITLE', __( 'Behind Closed Doors', WP_BCD ) );

define( 'WP_BCD_PATH', dirname( __FILE__ ) );
define( 'WP_BCD_URL', plugin_dir_url( __FILE__ ) );
define( 'WP_BCD_VERSION', '1.0' );

define( 'WP_BCD_SETTING', WP_BCD . '-setting' );
define( 'WP_BCD_SETTING_SECTION', WP_BCD . '-setting-section-main' );
define( 'WP_BCD_SETTING_SECTION_CUSTOM_DOOR', WP_BCD . '-setting-section-custom-door' );
define( 'WP_BCD_SETTING_FIELD_FORCE', 'force-login' );
define( 'WP_BCD_SETTING_FIELD_PAGE_ID', 'login-page-id' );
define( 'WP_BCD_SETTING_FIELD_SHOW_LOGIN_FORM', 'show-login-form' );
define( 'WP_BCD_SETTING_FIELD_FORCE_FRONT_DOOR_ON_LOGOUT', 'force-front-door-on-logout' );

require( WP_BCD_PATH . '/classes/wp-behind-closed-doors.php' );

WPBehindClosedDoors::Init( __FILE__ );
