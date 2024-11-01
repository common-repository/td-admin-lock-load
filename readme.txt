=== Plugin Name ===
Contributors: transcendev
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=PBM7V2TGX9AM6
Tags: security, wordpress security, bot-net, botnet, login attack, login security, admin secure,
admin lock, admin locking, wordpress admin security, brute force
Requires at least: 3.0
Tested up to: 3.8.1
Stable tag: 1.0.1
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Prevent unauthorized access to your admin login.

== Description ==

Don't leave your barn door open! Lock down your admin login form and load up another site instead for unwanted guests.

On servers with limited memory, recursive calls to your Wordpress admin login panel alone can lead to serious memory and load
issues that can potentially crash your server. This plugin prevents access to 
the login screen without the correct access code appended to the url.

This plugin will kill the process of loading the admin panel prior to the loading of
the template, and other such resource intensive processes that occur even when a user is not yet logged in. 

How to Use
1. Create your access code.
2. Click the Activate Admin Lock checkbox.
3. Optionally divert traffic to another url.
4. Future admin login should use the following URL:
http://www.yoursite.com/wp-admin/?a=CODE
Where "CODE" is the access code you enter.

Visit <a href="http://www.transcendevelopment.com">www.TranscenDevelopment.com</a> for more information.

== Installation ==

1. Upload the "td-admin-lock" folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Log into Wordpres and go to Settings->TD Lock Admin
4. Configure and activate as desired

== Frequently Asked Questions ==

None yet...feel free to ask :)

== Screenshots ==
1. Divert unwanted traffic to your admin login off to any url you desire. I'm sending people off to Google. So long suckers!
2. Access the admin login with the proper access code and you'll have the VIP treatment of being shown your WP login.
3. I've successfully logged in with both my access code and my normal username and password. 
4. TD Admin Lock, nicely tucked in to your normal WP Settings menu. 
5. This is where the magic happens. 

== Changelog ==
= 1.0.1 =
* Various bugs fixed

= 1.0.0 =
* A new plugin is born.

== Upgrade Notice ==
= 1.0.1 =
A bug fix update

= 1.0.0 =
Please consider upgrading from nothing.
