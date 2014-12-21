=== Ad Inserter ===
Contributors: spacetime
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=LHGZEMRTR7WB4
Tags: adsense, amazon, clickbank, ad, ads, html, javascript, php, code, widget, sidebar, rotating, banner, banner rotation, contextual, shortcodes, widgets, header, footer, users, logged in, not logged in, custom css
Requires at least: 3.0
Tested up to: 4.1
Stable tag: 1.4.0
License: GPLv3

Insert any HTML/Javascript/PHP code into Wordpress. Perfect for AdSense or contextual Amazon ads. 16 code blocks, many display options.

== Description ==

A simple solution to insert any code into Wordpress. **Perfect for AdSense or contextual Amazon ads.** Simply enter any HTML/Javascript/PHP code and select where and how you want to display it.

Automatic Display Options:

*   Display Before Content (before post or page text)
*   Display Before Selected Paragraph (0 means random paragraph):
*   Display After Selected Paragraph (0 means random paragraph):
*   Display After Content (after post or page text)
*   Display Before Title (does not work with all themes)
*   Display Before Excerpt
*   Display After Excerpt
*   As a Widget
*   Manual - Insert shortcode [adinserter block="BLOCK_NUMBER"] or [adinserter name="BLOCK_NAME"] into post or page HTML code to display block with BLOCK_NAME name or BLOCK_NUMBER number at this position
*   PHP function call `<?php echo adinserter (BLOCK_NUMBER); ?>` - Insert code block BLOCK_NUMBER at any position in template file

Additional Options:

*   Use {category}, {short_category}, {title}, {short_title}, {tag}, {smart_tag} or {search_query} tags to insert actual post data into code blocks
*   To rotate different ad versions separate them with |rotate|

Display Block to:

*   All users
*   Logged in users
*   Not logged in users

Alignment:

*   No Wrapping (leaves ad code as it is, otherwise it is wrapped by a div)
*   Custom CSS (Custom CSS code for wrapping div)
*   None
*   Align Left
*   Align Right
*   Center
*   Float Left
*   Float Right

PHP processing: Enabled or Disabled

Do not display ad if the number of paragraphs is below limit (used only for position Before or After selected paragraph).

Display ad only for posts published after N days.

Do not display ads to users from certain referrers (domains) e.g technorati.com, facebook.com,... (black list) or display ads only for certain referrers (white list). **Leave referrers list empty and set to Black list to show ads for all referrers.**

Do not display ads in certain caregories e.g sport, news, science,... (black list) or display ads only in certain categories (white list). **Leave category list empty and set to Black list to show ads in all categories.**

For all display positions you can also define Wordpress page types where the ads can be displayed:

*   Posts
*   Pages

For display positions Before Excerpt, After Excerpt, Before Title and Widget you can select additional pages where the ads can be displayed:

*   Homepage
*   Category pages
*   Search Pages
*   Archive pages

You can also disable ads in certain posts. For example, to disable ad block 1 with name Test Block in post put the following HTML code within post or page code:

`<!-- disable adinserter 1 -->`
or
`<!-- disable adinserter Test Block -->`

Ad Inserter is perfect for displaying AdSense or Amazon ads. It can also be used to display various versions of <a href="https://support.google.com/adsense/answer/65083?ctx=as2&rd=2&ref_topic=23389">AdSense ads using channels</a> to test which format or color combination performs best.

Support for Special Code Blocks:

*   Header scripts (tab H)
*   Footer scripts (tab F)

Wrapping divs for code blocks have 'ad-inserter' and 'ad-inserter-N' classes which can be used for custom styles.

To configure syntax highlighting go to Ad Inserter Settings (tab #) and choose theme. Try 'Ad Inserter' if you like dark themes.

== Installation ==

Automatic installation: Go to Wordpress Plugins menu, click Add New button, search for "Ad Inserter" and click Install Now.

Manual installation:

1. Download and extract ad-inserter folder and copy it to the "/wp-content/plugins/" directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Access Setting / Ad Inserter to configure it

== Frequently Asked Questions ==

= I have activated Ad Inserter. How can I use it? =

1. After activation, click "Settings / Ad Inserter" to access the setup page.
2. Put ad (or any other HTML/Javascript/PHP) code into the ad box.
3. Set the display options.
4. Save settings.


= How can I insert the post category name into my ad code? =

1. Use {category} in the ad. This will be replaced with the post category name.
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
*   {search_query} - Search engine query that brought visitor to your website (supports Google, Yahoo, Bing and Ask search engines), {smart_tag} is used when there is no search query. You need to disable caching to use this tag. Please note that most search queries are now encrypted.


= How can I rotate few versions of the same ad? =

Enter them into the ad box and separate them with |rotate| (vertical bars around text rotate). Ad Inserter will display them randomly.
Example:

`ad_code_1
|rotate|
ad_code_2
|rotate|
ad_code_3`


= How can I use PHP code for code block? =

Enter PHP code surrounded by PHP tags.
Example:

`<div style="width: 100%;">
Some HTML/Javascript code
</div>
<?php echo "PHP code by Ad Inserter"; ?>`


= How can I insert code block directly into template php file? =

Enable PHP function adinserter for code block and call adinserter function with code block number as parameter.
Example for block 3:

`<?php echo adinserter (3); ?>`

This would generate code as defined for the code block number AD_NUMBER.


= How can I create contextual Amazon ad (to show items related to the post)? =

Sign in to Amazon Associates, go to Widgets/Widget Source, choose ad type and set parameters.
For titles and search terms use tags. For example, the code below would display 5 amazon items related to the post tag - check above for all possible tags.

`<div style="height: 531px;">
<script type="text/javascript">
var amzn_wdgt={widget:"Search"};
amzn_wdgt.tag="ad-inserter-20";
amzn_wdgt.columns="1";
amzn_wdgt.rows="5";
amzn_wdgt.defaultSearchTerm="{smart_tag}";
amzn_wdgt.searchIndex="All";
amzn_wdgt.width="300";
amzn_wdgt.showImage="True";
amzn_wdgt.showPrice="True";
amzn_wdgt.showRating="True";
amzn_wdgt.design="2";
amzn_wdgt.colorTheme="Default";
amzn_wdgt.headerTextColor="#0000AA";
amzn_wdgt.outerBackgroundColor="#FFFFFF";
amzn_wdgt.borderColor="#FFFFFF";
amzn_wdgt.marketPlace="US";
</script>
<script type="text/javascript" src="http://wms.assoc-amazon.com/20070822/US/js/AmazonWidgets.js">
</script>
</div>`


Another example for nice contextual carousel below posts:

`<div style="overflow: auto; width: 100%;">
<script type='text/javascript'>
var amzn_wdgt={widget:'Carousel'};
amzn_wdgt.tag='ad-inserter-20';
amzn_wdgt.widgetType='SearchAndAdd';
amzn_wdgt.searchIndex='All';
amzn_wdgt.keywords='{smart_tag}';
amzn_wdgt.title='{title}';
amzn_wdgt.width='460';
amzn_wdgt.height='250';
amzn_wdgt.marketPlace='US';
</script>
<script type='text/javascript' src='http://wms.assoc-amazon.com/20070822/US/js/swfobject_1_5.js'>
</script>
</div>`


= Center alignment does not work for some ads! =

Some iframe ads can not be centered using standard approach so some additional code is needed to put them in the middle.
Simply wrap ad code in a div with some style e.g. left padding. Example:

`<div style="padding-left: 200px;">
ad_code
</div>`


== Screenshots ==

1. This screenshot shows settings for one ad block. Up to 16 ad blocks can be configured.


== Changelog ==

= 1.4.0 =
* Added support to skip paragraphs with specified text
* Added position After paragraph
* Added support for header and footer scripts
* Added support for custom CSS styles
* Added support to display blocks to all, logged in or not logged in users
* Added support for syntax highlighting
* Added support for shortcodes
* Added classes to block wrapping divs
* Few bugs fixed

= 1.3.5 =
* Fixed bug: missing echo for PHP function call example

= 1.3.4 =
* Added option for no code wrapping with div
* Added option to insert block codes from PHP code
* Changed HTML codes to disable display on specific pages
* Selected code block position is preserved after settings are saved
* Manual insertion can be enabled or disabled regardless of primary display setting
* Fixed bug: in some cases Before Title display setting inserted code into RSS feed

= 1.3.3 =
* Added option to insert ads also before or after the excerpt
* Fixed bug: in some cases many errors reported after activating the plugin
* Few minor bugs fixed
* Few minor cosmetic changes

= 1.3.2 =
* Fixed blank settings page caused by incompatibility with some themes or plugins

= 1.3.1 =
* Added option to insert ads also on pages
* Added option to process PHP code
* Few bugs fixed

= 1.3.0 =
* Number of ad slots increased to 16
* New tabbed admin interface
* Ads can be manually inserted also with {adinserter AD_NUMBER} tag
* Fixed bug: only the last ad block set to Before Title was displayed
* Few other minor bugs fixed
* Few cosmetic changes

= 1.2.1 =
* Fixed problem: || in ad code (e.g. asynchronous code for AdSense) causes only part of the code to be inserted (|| to rotate ads is replaced with |rotate|)

= 1.2.0 =
* Fixed bug: manual tags in posts lists were not removed
* Added position Before title
* Added support for minimum nuber of paragraphs
* Added support for page display options for Widget and Before title positions
* Alignment now works for all display positions

= 1.1.3 =
* Fixed bug for {search_query}: When the tag is empty {smart_tag} is used in all cases
* Few changes in the settings page

= 1.1.2 =
* Fixed error with multisite/network installations

= 1.1.1 =
* Fixed bug in Float Right setting display

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

= 1.4.0 =
Added support to skip paragraphs with specified text
Added position After paragraph
Added support for header and footer scripts
Added support for custom CSS styles
Added support to display blocks to all, logged in or not logged in users
Added support for syntax highlighting
Added support for shortcodes
Added classes to block wrapping divs
Few bugs fixed

= 1.3.5 =
Fixed bug: missing echo for PHP function call example

= 1.3.4 =
Fixed bug: in some cases Before Title display setting inserted code into RSS feed,
Selected code block position is preserved after settings are saved,
Added option for no code wrapping with div,
Added option to insert block codes from PHP code,
Changed HTML codes to disable display on specific pages,
Manual insertion can be enabled or disabled regardless of primary display setting

= 1.3.3 =
Fixed bug: in some cases many errors reported after activating the plugin,
Added option to insert ads also before or after the excerpt,
Few minor bugs fixed,
Few minor cosmetic changes

= 1.3.2 =
Fixed blank settings page caused by incompatibility with some themes or plugins

= 1.3.1 =
Added option to insert ads also on pages,
Added option to process PHP code,
Few bugs fixed

= 1.3.0 =
Number of ad slots increased to 16,
New tabbed admin interface,
Ads can be manually inserted also with {adinserter AD_NUMBER} tag,
Fixed bug: only the last ad block set to Before Title was displayed,
Few other minor bugs fixed,
Few cosmetic changes

= 1.2.1 =
Fixed problem: || in ad code (e.g. asynchronous code for AdSense) causes only part of the code to be inserted (|| to rotate ads is replaced with |rotate|)

= 1.2.0 =
Fixed bug: manual tags in posts lists were not removed,
Added position Before title,
Added support for minimum nuber of paragraphs,
Added support for page display options for Widget and Before title positions,
Alignment now works for all display positions,

= 1.1.3 =
Fixed bug for search query tag

= 1.1.2 =
Fixed error with multisite/network installations

= 1.1.1 =
Fixed bug in Float Right setting display

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
