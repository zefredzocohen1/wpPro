=== WP No Base Permalink ===
Contributors: 23r9i0
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=X7SFE48Y4FEQL
Tags: permalink, base, category, tag, parents, categories, parents categories
Requires at least: 3.5
Tested up to: 4.3.1
Stable tag: 0.3.1
License: GPL/MIT

Removes category base or parents categories or tag base from your permalinks.

Compatible with WPML Plugin and WordPress Multisite.

== Description ==

Removes category base from your category permalinks (optional). By default is enabled.
Removes parents categories from your category permalinks (optional).
Removes tag base from your tag permalinks (optional).

The three options above are optional and generate their own rewrite rules.

Compatible with WPML Plugin and WordPress Multisite.

Read the [FAQ](https://wordpress.org/plugins/wp-no-base-permalink/faq/) before use.

== Installation ==

1. Upload the 'wp-no-base-permalink' folder to the '/wp-content/plugins/' directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. You sould now be able to access your categories without category base.
4. You can optionally configure other options from the permanent links page.

== Frequently Asked Questions ==

= Won't this conflict with posts/pages? =

Simply don't have a post/page and category or tag ( if you you use this option ) with the same slug. Warning!!!, if they have the same slug will prioritize the category or tag slug.


= The plugin has been uninstalled, but the slug /category/ or /tag/ did not reappear why? =

A particular installation does not allow the rewrite feature in disabling the plugin. Try after disabling the plugin, save permanent links again.

== Changelog ==
= 0.3.1 =
* Unknown bug
= 0.3 =
* Updated certain parts to fix issues
* Change Remove Category Base to optional. By default is enabled.
* Removes texts and scripts
* Restore support for PHP 5.3, change plugin class to static
= 0.2.3 =
* Update Tested Version
* Add Disabled plugin update on PHP 5.3, last updated Require 5.4 or later
= 0.2.2 =
* fix constant developer
= 0.2.1 =
 * Update code for personal options (developer)
= 0.2 =
 * Fix 404 in not latin letters ( tested locally)
 * Fix 404 for not admin users
= 0.1 =
 * init version

== Upgrade Notice ==
= 0.3.1 =
Bug: on activation, please force saved settings, investigating the motive.
= 0.3 =
Resolve issues
= 0.2.3 =
Add Disabled plugin update on PHP 5.3, last updated Require 5.4 or later