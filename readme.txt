=== Ad Inserter ===
Contributors: spacetime
Donate link: http://igorfuna.com/software/web/ad-inserter-wordpress-plugin
Tags: adsense, amazon, ad, ads, html, javascript, html code, widget, sidebar, rotating ads, rotating banners
Requires at least: 2.0
Tested up to: 3.1.3
Stable tag: 1.1.0

Integrate any HTML code into Wordpress. Just paste the code and select the location and display mode. Perfect for AdSense or Amazon ads.

== Description ==

An elegant solution to put any ad into Wordpress. Simply enter any HTML code and select where and how you want to display it (including widgets). You can also use {category}, {short_category}, {title}, {short_title}, {tag} or {smart_tag} for actual article data. To rotate different ad versions separate them with ||.

1. Display Options:

*   Display ad Before Selected Paragraph (0 means random paragraph). Ad alignment:
  *   None
  *   Align Left
  *   Align Right
  *   Center
  *   Float Left
  *   Float Right
*   Display ad Before Content
*   Display ad After Content
*   Ad as Widget
*   Manual - Insert {adinserter Ad Name} into post to display ad with Ad Name at this position


2. Display ad only for posts published after N days.

3. Do not display ads to users from certain domain e.g technorati.com, facebook.com

4. Do not display ads in certain caregories e.g sport, news, science,... (black list) or display ads only in certain categories (white list)

Ad Inserter is perfect to display AdSense or Amazon ads. It can also be used to display various versions of <a href="https://www.google.com/adsense/support/bin/answer.py?answer=32614">AdSense ads using channels</a> to test which format or color combination performs best.

Inspired by the <a href="http://wordpress.org/extend/plugins/adsense-daemon/">Adsense Daemon</a> plugin by Yong Mook Kim.

== Installation ==

Automatic installation: Go to Wordpress Plugins menu, click Add New button, search for "Ad Inserter" and click Install Now.

Manual installation:

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
*   {tag} - The first tag or general tag if the post has no tags
*   {smart_tag} - Smart selection of post tag in the following order:
  *   If there is no tag then the category is used;
  *   If there is a two-word tag then it is used;
  *   If the first tag is a substring of the second (or vice versa) then the first tag is not taken into account
  *   If the first and second tags are single words then both words are used
  *   First three words of the first tag
  *   General tag
*   {search_query} - Search engine query that brought visitor to our website (supports Google, Yahoo, Bing and Ask search engines)


= How can I rotate few versions of the same ad? =

Enter them into the ad box and separate them with || (double vertical bar). Ad Inserter will display them randomly.
Example:

`ad_code_1`
`||`
`ad_code_2`
`||`
`ad_code_3`


= Center alignment does not work for some ads! =

Some iframe ads can not be centered using standard approach so some additional code is needed to put them in the middle.
Simply wrap ad code in a div with some style e.g. left padding. Example:

`<div style="padding-left: 200px;">`
`ad_code`
`</div>`


== Screenshots ==

1. This screen shot shows settings for one ad block. Up to 8 ad blocks can be configured.


== Changelog ==

= 1.1.0 =
* Added option to manually display individual ads
* Added new ad alignments: left, center, right
* Added {search_query} tag
* Added support for category black list and white list

= 1.0.4 =
* HTML entities for {title} and {short_title} are now decoded
* Added {tag} to display the first tag

= 1.0.3 =
* Fixed bug with rotating ads

= 1.0.2 =
* Added support for rotating ads

= 1.0.1 =
* Added support for different sidebar implementations

= 1.0.0 =
* Initial release


== Upgrade Notice ==

= 1.1.0 =
Added new features

= 1.0.4 =
Added few minor features

= 1.0.3 =
Fixed bug with rotating ads

= 1.0.2 =
Support for rotating ads

= 1.0.1 =
Support for different sidebar implementations in various themes

= 1.0.0 =
Initial release
