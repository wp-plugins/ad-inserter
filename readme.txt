=== Ad Inserter ===
Contributors: spacetime
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=LHGZEMRTR7WB4
Tags: adsense, amazon, ad, ads, html, code, widget, sidebar, rotating, banners
Requires at least: 3.0
Tested up to: 3.3.1
Stable tag: 1.1.3

Integrate any HTML code into Wordpress. Just paste the code and select the location and display mode. Perfect for AdSense or contextual Amazon ads.

== Description ==

An elegant solution to put any ad into Wordpress. **Perfect for AdSense or contextual Amazon ads.** Simply enter any HTML code and select where and how you want to display it (including widgets). You can also use {category}, {short_category}, {title}, {short_title}, {tag}, {smart_tag} or {search_query} for actual article data. To rotate different ad versions separate them with ||.

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
*   Manual - Insert {adinserter AD_NAME} into post to display ad with AD_NAME name at this position


2. Do not display ad if the number of paragraphs is below limit.

3. Display ad only for posts published after N days.

4. Do not display ads to users from certain domain e.g technorati.com, facebook.com

5. Do not display ads in certain caregories e.g sport, news, science,... (black list) or display ads only in certain categories (white list). **Leave category list empty and set to Black list to show ads in all categories.**

Ad Inserter is perfect to display AdSense or Amazon ads. It can also be used to display various versions of <a href="https://www.google.com/adsense/support/bin/answer.py?answer=32614">AdSense ads using channels</a> to test which format or color combination performs best.

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
*   {search_query} - Search engine query that brought visitor to your website (supports Google, Yahoo, Bing and Ask search engines), {smart_tag} is used when there is no search query. You need to disable caching to use this tag.


= How can I rotate few versions of the same ad? =

Enter them into the ad box and separate them with || (double vertical bar). Ad Inserter will display them randomly.
Example:

`ad_code_1
||
ad_code_2
||
ad_code_3`


= How can I create contextual Amazon ad (to show items related to the post)? =

Sign in to Amazon Associates, go to Widgets/Widget Source, choose ad type and set parameters.
For titles and search terms use tags. For example, the code below would display 5 amazon items related to the post tag - check above for all possible tags.

`<div style="height: 531px;">
<script type="text/javascript">
var amzn_wdgt={widget:"Search"};
amzn_wdgt.tag="adinserter-20";
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
amzn_wdgt.tag='adinserter-20';
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

1. This screen shot shows settings for one ad block. Up to 8 ad blocks can be configured.


== Changelog ==

= 1.1.4 =
* Added support for minimum nuber of paragraphs
* Added support for widget display options

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

= 1.1.4 =
Added support for minimum nuber of paragraphs
Added support for widget display options

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
