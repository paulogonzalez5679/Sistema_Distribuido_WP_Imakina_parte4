=== SM - SQL logs ===
Contributors: mi7osz
Donate link: https://www.paypal.me/mi7osz
Tags: mysql, sql, logs, report, query, browse, database, optymize, query, table, clean, clean-up, cleanup, wordpress database
Requires at least: 3.5
Tested up to: 4.8.2
Stable tag: 1.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Record and view all SQL queries that your WordPress is requesting. Browse formated and highlighted syntax queries for debug and speedup your site.

== Description ==

Thanks to this plugin you will see list of queries that are requested from your database. It’s extremely helpful, if you need to investigate what is causing your website to perform slowly. Thanks to full list of formatted and highlighted  SQL queries, finding plugin or template file which is killing your website is easy.

== Installation ==

1. Extract all files from the ZIP file, and then upload the plugin's folder to /wp-content/plugins/.
1. Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

= Page with Logs or detail of the Log don't want to load. =

The issue is in "SqlFormatter". If too long and quirky queries are passed to "SqlFormatter" it can kill requested page. 
Don't worry, if it happen it's afecting only admin area and only with that specyfic query.

**How to fix it?**
Well... For now, the only thing you can do, is turning "SqlFormatter" Off in options.

== Screenshots ==

1. Latest logs
2. One log details
3. Options page

== Changelog ==

= 1.0.1 =
Minor changes

= 1.0.0 =
Initial plugin version
