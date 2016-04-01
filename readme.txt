=== Add Shortcodes Actions And Filters ===
Contributors: msimpson
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=SSABNHHPSVWT6
Tags: add shortcodes actions and filters,add shortcodes,add action,add filter,shortcodes,actions,filters
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Requires at least: 4.4
Tested up to: 4.5
Stable tag: 2.0.4

Add PHP Code to create your own Shortcodes, Actions and Filters

== Description ==

Add PHP Code to create your own Shortcodes, Actions and Filters.

Can import your shortcodes from Shortcode Exec PHP plugin (which is no longer supported).

Provides a place to add your code that is more convenient than putting it in your theme's functions.php file.

Add your code in the Dashboard -> <strong>Tools</strong> -> <strong>Shortcodes, Actions and Filters</strong>
but this is only available to users with Administrator role.

Why this plugin?
Existing WordPress documentation suggests adding your own shortcodes, action and filters in the theme's functions.php file.
This is not a good idea because:

* If you upgrade your theme, this file can be overwritten and
* if you change your theme then you need to add the same code to that theme as well.

Your code additions should not have to be artificially tied to your theme. This plugin frees you from that constraint.

== Installation ==

1. From the Dashboard:
1. Plugins
1. Add New
1. Search "Add Shortcodes, Actions And Filters"
1. Install Now
1. Activate
1. Go to Tools -> Shortcodes, Actions And Filters

== Frequently Asked Questions ==

= What happens when there is a syntax or other fatal error in one of my code items? =
Typically you will see an error message on the page that is trying to execute the code.
The error will contain a link to edit the problem code item. It will look like this:

Shortcodes, Actions and Filters Plugin: Error in user-provided code item named "My Action". <u>Fix the code here</u>

There are two cases where this plugin will not execute code.

1. On this plugin's own dashboard pages for displaying and editing code. This is so that you can always get back to the
page to edit code despite any error in your code.
1. On login/logout page (which is the same page) except when the "Allow Execution of Actions and Filters on Login/Logout pages"
option is set to "true".

You will not see any errors on this plugin's dashboard pages for editing and listing the code. Therefore, you should
open a different page on your site where you can see the results (or errors) of your code execution.

By default, no code that you put in this plugin will run on login/logout pages.
This is to prevent the situation where your code causes a fatal error
that prevents you from being able to login to fix the problem. Consequently, any action or filters that you want run on
login/logout pages will not be run. However you can override this setting on the plugin's Options page by setting
"Allow Execution of Actions and Filters on Login/Logout pages" to "true". Do so at your own risk! Any code that you want
executed on login/logout pages should have "Execute also on Dashboard Pages" checked.

In certain cases, an error may cause you to be unable to access any pages your site, including dashboard pages.
However, you will be able to access this plugin's dashboard pages so that you can edit or delete the problem code.
Enter the URL to the plugin's dashboard page directly into your browser:

http://YOUR-SITE/wp-admin/admin.php?page=ShortcodesActionsFilters

Only administrator users (manage_options role) can access that page and edit code.

Worst-case scenario: if somehow your site is completely inaccessible, you can disable your code in via the database.
This can happen if you enabled the setting to run code on the login page and that code creates a fatal error. To recover,
find the wp_addactionsandfilters_plugin_usercode table. If you know the code item that is causing the problem, then set
its "enabled" value to 0.

Alternately, disable all code using the following query:
<code>UPDATE wp_addactionsandfilters_plugin_usercode SET enabled = 0</code>

= What order are the code elements executed in? =
They are executed in order by ID number, lowest to highest. Shortcode are registered but not executed until the
shortcode appears on a page.

Action and filter code can depend on code from a code item with a lower ID number. For example, if code item with
ID=1 defines a function, then all code items with ID>1 have the function defined. But if you deactivate
the code with ID=1, then active code items that depend on it will fail.

= Why doesn't my action code execution on login/logout pages? =
The plugin will not execute code on these pages because an error in your code could cause you to be unable to log into
your site to fix the error. However, as stated above, you can override this setting (do so at your own risk!)

== Screenshots ==

1. Dashboard page for code listings
2. Dashboard page for editing a shortcode
3. Dashboard page for editing a filter
4. Dashboard page for editing an action
5. Dashboard page for import/export to/from file and import from Shortcode Exec PHP

== Changelog ==

= 2.0.5 =
* Minor performance improvement to options handling

= 2.0.4 =
* Improved error handling/reporting for PHP 7+

= 2.0.3 =

* Introduced option to permit code execution on login/logout pages
* Minor improvement to error messages
* Fix for minor issue on edit page when using PHP 7.0.

= 2.0.1 =

* Fix for scenario where bad code is executing on admin pages that could cause the user to be unable to login to fix it.

= 2.0 =

* Significant upgrade!
* Re-designed administration panel allows you to separate out your code, and activate/deactivate them individually
* Now can create shortcodes similar to (the no longer supported) PHP Shortcode Exec plugin
* Can import your shortcodes from PHP Shortcode Exec plugin
* Supports multisite

= 1.3 =

* More graceful handling of PHP FATAL Errors introduced by user's code

= 1.2 =

* Fixed debug error message on admin page

= 1.1 =

* Limiting access to only Administrators to avoid possible security exploit

= 1.0 =

* Initial Revision

== Upgrade Notice ==

= 2.0 =

Significant upgrade: now can create shortcodes and import shortcodes from Shortcode Exec PHP.
Can manage different pieces of code, having them individually activated or not.

