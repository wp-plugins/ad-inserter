=== Ad Inserter ===
Contributors: spacetime
Donate link: http://igorfuna.com/software/web/ad-inserter-wordpress-plugin
Tags: adsense, amazon, ad, ads, html, javascript, html code
Requires at least: 2.0
Tested up to: 3.0.1
Stable tag: 1.0.1

Integrate any HTML code into Wordpress. Just paste the code and select the location and display mode. Perfect for AdSense or Amazon ads.

== Description ==

An elegant solution to put any ad into Wordpress. Simply enter any HTML code and select where and how you want to display it (including widgets). You can also use {category}, {short_category}, {title}, {short_title} or {smart_tag} for actual article data.

1. Display Options:

*   Display ad Before Selected Paragraph (0 means random paragraph)
  *   No Float
  *   Left Float
  *   Right Float
*   Display ad Before Content
*   Display ad After Content
*   Ad as Widget

2. Display ad only for posts published after N days.

3. Do not display ads to users from certain website e.g technorati.com, facebook.com

4. Do not display ads in certain caregory e.g sport, news, science,...

Inspired by the <a href="http://wordpress.org/extend/plugins/adsense-daemon/">Adsense Daemon</a> plugin by <a href="http://www.mkyong.com/blog/adsense-daemon-wordpress-plugin">Yong Mook Kim</a>

== Installation ==

1. Download and extract ad-inserter folder and copy it to the "/wp-content/plugins/" directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Access Setting / Ad Inserter to configure it

== Frequently Asked Questions ==

= I have activated Ad Inserter. How can I use it? =

1. After activation, click "Settings / Ad Inserter" to access the setup page.
2. Put ad (or any HTML) code into the ad box.
3. Set the display options.
4. Save settings.


= How can I insert the post category into my ad code? =

1. Use {category} in the ad. This will be replaced with the post category.
2. You can also use

*   {title} - Title of the post
*   {short_title} - Short title (first 3 words) of the post title
*   {category} - Category of the post (or short title if there is no category)
*   {short_category} - First words before "," or "and" of the category of the post (or short title if there is no category)
*   {smart_tag} - Smart selection of post tag in the following order:
  *   If there is no tag then the category is used;
  *   If there is a two-word tag then it is used;
  *   If the first tag is a substring of the second (or vice versa) then the first tag is not taken into account
  *   If the first and second tags are single words then both words are used
  *   First three words of the first tag


== Screenshots ==

1. This screen shot shows settings for one ad block. Up to 8 ad blocks can be configured.


== Changelog ==

= 1.0.1 =
* Added support for different sidebar implementations

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.0.0 =
Initial release
