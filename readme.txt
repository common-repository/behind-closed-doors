=== Behind Closed Doors ===
Contributors: spencersokol
Donate link: http://spencersokol.com/donations/
Tags: login, security, privacy, maintenance, maintenance-mode
Requires at least: 3.5
Tested up to: 3.9.2
Stable tag: 1.1
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Keep your site behind closed doors, by redirecting visitors to a single page, optionally giving them a login form to view the remainder of your site.

== Description ==

Keep your site behind closed doors with this plugin, by redirecting visitors to a single page, and optionally giving users a login form to view the remainder of your site.

Basic uses:
1. Keeping your site hidden while in initial development, while still allowing test users and clients to login
2. Putting your site in a "maintenance mode" quickly

== Installation ==

1. Upload the contents of the plugin to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Will this work with my theme? =

Yes. The default template is extremely simple and should fit in with most any theme. You can also add a custom template to your theme.

= How do I customize the front door? =

You can copy the `login.php` file from the `<plugin-directory>/templates/` to your theme directory and customize it to your needs. Do not edit the `<plugin-directory>/templates/login.php` file directly, as it could be overwritten by later plugin updates.

= What happens if I go directly to an admin URL? =

Admin pages bypass the front door. For example, if you go to http://yoursite.com/wp-admin/ and you are not logged in, you will be redirected to http://yoursite.com/wp-login.php by the default Wordpress behavior. This is to prevent the site admin from accidentally locking themselves out of their site by specifying a custom front door page with no login form.

== Screenshots ==

== Upgrade Notices ==

== Changelog ==

= 1.1 =
* Added option to redirect to front door on user logout

= 1.0 =
* Initial release.

== Future Releases ==
