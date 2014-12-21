<?php
/*
Plugin Name: Ad Inserter
Version: 1.4.0
Description: A simple solution to insert any ad code into Wordpress. Simply enter any HTML, Javascript or PHP code and select where and how you want to display it.
Author: Spacetime
Author URI: http://igorfuna.com/
Plugin URI: http://igorfuna.com/software/web/ad-inserter-wordpress-plugin
*/

/*
TO DO
- minimum number of words
*/

/*
Change Log

Ad Inserter 1.4.0 - 21 December 2014
- Added support to skip paragraphs with specified text
- Added position After paragraph
- Added support for header and footer scripts
- Added support for custom CSS styles
- Added support to display blocks to all, logged in or not logged in users
- Added support for syntax highlighting
- Added support for shortcodes
- Added classes to block wrapping divs
- Few bugs fixed

Ad Inserter 1.3.5 - 18 March 2014
- Fixed bug: missing echo for PHP function call example

Ad Inserter 1.3.4 - 15 March 2014
- Added option for no code wrapping with div
- Added option to insert block codes from PHP code
- Changed HTML codes to disable display on specific pages
- Selected code block position is preserved after settings are saved
- Manual insertion can be enabled or disabled regardless of primary display setting
- Fixed bug: in some cases Before Title display setting inserted code into RSS feed

Ad Inserter 1.3.3 - 8 January 2014
- Added option to insert ads also before or after the excerpt
- Fixed bug: in some cases many errors reported after activating the plugin
- Few minor bugs fixed
- Few minor cosmetic changes

Ad Inserter 1.3.2 - 4 December 2013
- Fixed blank settings page caused by incompatibility with some themes or plugins

Ad Inserter 1.3.1 - 3 December 2013
- Added option to insert ads also on pages
- Added option to process PHP code
- Few bugs fixed

Ad Inserter 1.3.0 - 27 November 2013
- Number of ad slots increased to 16
- New tabbed admin interface
- Ads can be manually inserted also with {adinserter AD_NUMBER} tag
- Fixed bug: only the last ad block set to Before Title was displayed
- Few other minor bugs fixed
- Few cosmetic changes

Ad Inserter 1.2.1 - 19 November 2013
- Fixed problem: || in ad code (e.g. asynchronous code for AdSense) causes only part of the code to be inserted (|| to rotate ads is replaced with |rotate|)

Ad Inserter 1.2.0 - 15/05/2012
- Fixed bug: manual tags in posts lists were not removed
- Added position Before title
- Added support for minimum nuber of paragraphs
- Added support for page display options for Widget and Before title positions
- Alignment now works for all display positions

Ad Inserter 1.1.3 - 07/04/2012
- Fixed bug for {search_query}: When the tag is empty {smart_tag} is used in all cases
- Few changes in the settings page

Ad Inserter 1.1.2 - 16/07/2011
- Fixed error with multisite/network installations

Ad Inserter 1.1.1 - 16/07/2011
- Fixed bug in Float Right setting display

Ad Inserter 1.1.0 - 05/06/2011
- Added option to manually display individual ads
- Added new ad alignments: left, center, right
- Added {search_query} tag
- Added support for category black list and white list

Ad Inserter 1.0.4 - 19/12/2010
- HTML entities for {title} and {short_title} are now decoded
- Added {tag} to display the first tag

Ad Inserter 1.0.3 - 05/12/2010
- Fixed bug for rotating ads

Ad Inserter 1.0.2 - 04/12/2010
- Added support for rotating ads

Ad Inserter 1.0.1 - 17/11/2010
- Added support for different sidebar implementations

Ad Inserter 1.0.0 - 14/11/2010
- Initial release

*/


//ini_set('display_errors',1);
//error_reporting (E_ALL);


/* Version check */
global $wp_version;
$exit_msg = 'Ad Inserter requires WordPress 3.0 or newer. <a href="http://codex.wordpress.org/Upgrading_WordPress">Please update!</a>';

if (version_compare ($wp_version, "3.0", "<")) {
  exit ($exit_msg);
}

//include required files
require_once 'class.php';
require_once 'constants.php';
require_once 'settings_form.php';

//hooks
add_action ('admin_menu',       'ai_admin_menu_hook');
add_filter ('the_content',      'ai_content_hook', 99999);
add_filter ('the_excerpt',      'ai_excerpt_hook', 99999);
add_action ('loop_start',       'ai_loop_start_hook');
add_action ('init',             'ai_init_hook');
add_action ('admin_notices',    'ai_admin_notice_hook');
add_action ('wp_head',          'ai_wp_head_hook');
add_action ('wp_footer',        'ai_wp_footer_hook');

function ai_init_hook() {

  $ad1  = new Block (1);
  $ad2  = new Block (2);
  $ad3  = new Block (3);
  $ad4  = new Block (4);
  $ad5  = new Block (5);
  $ad6  = new Block (6);
  $ad7  = new Block (7);
  $ad8  = new Block (8);
  $ad9  = new Block (9);
  $ad10 = new Block (10);
  $ad11 = new Block (11);
  $ad12 = new Block (12);
  $ad13 = new Block (13);
  $ad14 = new Block (14);
  $ad15 = new Block (15);
  $ad16 = new Block (16);

  $ad_all_data = array ($ad1,$ad2,$ad3,$ad4,$ad5,$ad6,$ad7,$ad8,$ad9,$ad10,$ad11,$ad12,$ad13,$ad14,$ad15,$ad16);

  // Load options from db
  foreach($ad_all_data as $key => $obj){
    $obj->load_options ("AdInserter".($key + 1)."Options");
  }

  foreach ($ad_all_data as $key => $obj){
    if($obj->get_append_type() == AD_SELECT_WIDGET){
      $ad_counter = $key + 1;
      // register widget
      $widget_options = array ('classname' => 'ai_widget', 'description' => "Ad Inserter code block ".$ad_counter.".");
      $widget_parameters = array ('block' => $ad_counter);
      wp_register_sidebar_widget ('ai_widget'.$ad_counter, $obj->get_ad_name(), 'ai_widget', $widget_options, $widget_parameters);
    }
  }

  add_shortcode ('adinserter', 'process_shortcodes');
}

function ai_admin_menu_hook () {
  global $ai_settings_page;

  $ai_settings_page = add_submenu_page ('options-general.php', 'Ad Inserter Options', 'Ad Inserter', 8, basename(__FILE__), 'ai_menu');
  add_action ('admin_enqueue_scripts', 'ai_admin_enqueue_scripts');
}


function ai_admin_enqueue_scripts ($hook_suffix) {
  global $ai_settings_page;

  if ($hook_suffix == $ai_settings_page) {
    wp_enqueue_script ('ad-inserter-js',        plugins_url ('js/ad-inserter.js', __FILE__), array ('jquery', 'jquery-ui-tabs', 'jquery-ui-button', 'jquery-ui-tooltip'), AD_INSERTER_VERSION);
    wp_enqueue_style  ('ad-inserter-jquery-ui', plugins_url ('css/jquery-ui-1.10.3.custom.min.css', __FILE__), false, null);
    wp_enqueue_style  ('ad-inserter-css',       plugins_url ('css/ad-inserter.css', __FILE__), false, AD_INSERTER_VERSION);

    wp_enqueue_script ('ad-ace',                plugins_url ('includes/ace/src-min-noconflict/ace.js', __FILE__ ), array (), AD_INSERTER_VERSION);
    wp_enqueue_script ('ad-ace-ext-modelist',   plugins_url ('includes/ace/src-min-noconflict/ext-modelist.js', __FILE__ ), array (), AD_INSERTER_VERSION);
  }
}

function ai_admin_notice_hook () {
  global $current_screen;

  $plugin_options = ai_plugin_options ();
  $plugin_db_options = get_option (AD_OPTIONS);

  $ad_inserter_installed = get_option ('ad1_name') != '';

  if ($ad_inserter_installed && !isset ($plugin_db_options ['VERSION']) && ($current_screen->id != "settings_page_ad-inserter" || (!isset ($_POST [AD_FORM_SAVE]) && !isset ($_POST [AD_FORM_CLEAR])))) {
    echo "<div id='message' class='updated below-h2' style='margin: 5px 15px 2px 0px; padding: 10px;'><strong>
      Notice: ".AD_INSERTER_TITLE." plugin was updated. New version can insert ads also on static pages.
      Please <a href=\"/wp-admin/options-general.php?page=ad-inserter.php\">check</a> if page display options for all ad slots are set properly.
      Make required changes and save ".AD_INSERTER_TITLE." settings to remove this notice.</strong></div>";
  }
}

function ai_wp_head_hook () {
  $obj = new AdH();
  $obj->load_options (AD_HEADER_OPTIONS);

  if ($obj->get_enable_manual ()) {
    echo ai_getCode ($obj);
  }
}

function ai_wp_footer_hook () {
  $obj = new AdF();
  $obj->load_options (AD_FOOTER_OPTIONS);

  if ($obj->get_enable_manual ()) {
    echo ai_getCode ($obj);
  }
}

function filter_characters ($str){

  $str = str_replace (array ("\\\""), array ("\""), $str);
  return $str;
}

function ai_plugin_options ($syntax_highlighter_theme = 'chrome'){
  $plugin_options = array ();

  $version_array = explode (".", AD_INSERTER_VERSION);
  $version_string = "";
  foreach ($version_array as $number) {
    $version_string .= sprintf ("%02d", $number);
  }

  $plugin_options ['VERSION'] = $version_string;

  $plugin_options ['SYNTAX_HIGHLIGHTER_THEME'] = $syntax_highlighter_theme;

  return ($plugin_options);
}

function ai_get_option ($option_name) {
  $options = get_option ($option_name);

  if (is_array ($options)) {
    foreach ($options as $key => $option) {
      $options [$key] = stripslashes ($option);
    }
  } else $options = stripslashes ($options);

  return ($options);
}

function get_syntax_highlighter_theme () {
  $plugin_db_options = get_option (AD_OPTIONS);

  if (!isset ($plugin_db_options ['SYNTAX_HIGHLIGHTER_THEME']) || $plugin_db_options ['SYNTAX_HIGHLIGHTER_THEME'] == '') {
    $plugin_db_options ['SYNTAX_HIGHLIGHTER_THEME'] = 'chrome';
  }

  return ($plugin_db_options ['SYNTAX_HIGHLIGHTER_THEME']);
}

function ai_menu () {

  if (isset ($_POST [AD_FORM_SAVE])) {

    $ad1  = new Block (1);
    $ad2  = new Block (2);
    $ad3  = new Block (3);
    $ad4  = new Block (4);
    $ad5  = new Block (5);
    $ad6  = new Block (6);
    $ad7  = new Block (7);
    $ad8  = new Block (8);
    $ad9  = new Block (9);
    $ad10 = new Block (10);
    $ad11 = new Block (11);
    $ad12 = new Block (12);
    $ad13 = new Block (13);
    $ad14 = new Block (14);
    $ad15 = new Block (15);
    $ad16 = new Block (16);

    $adH  = new AdH();
    $adF  = new AdF();

     foreach(array_keys($ad1->wp_options) as $key){
        if(isset($_POST[$key])){
            $ad1->wp_options[$key] = filter_characters ($_POST[$key]);
        }
      }

      foreach(array_keys($ad2->wp_options) as $key){
        if(isset($_POST[$key])){
              $ad2->wp_options[$key] = filter_characters ($_POST[$key]);
        }
      }

      foreach(array_keys($ad3->wp_options) as $key){
        if(isset($_POST[$key])){
              $ad3->wp_options[$key] = filter_characters ($_POST[$key]);
        }
      }

      foreach(array_keys($ad4->wp_options) as $key){
        if(isset($_POST[$key])){
              $ad4->wp_options[$key] = filter_characters ($_POST[$key]);
        }
      }

     foreach(array_keys($ad5->wp_options) as $key){
        if(isset($_POST[$key])){
            $ad5->wp_options[$key] = filter_characters ($_POST[$key]);
        }
      }

      foreach(array_keys($ad6->wp_options) as $key){
        if(isset($_POST[$key])){
              $ad6->wp_options[$key] = filter_characters ($_POST[$key]);
        }
      }

      foreach(array_keys($ad7->wp_options) as $key){
        if(isset($_POST[$key])){
              $ad7->wp_options[$key] = filter_characters ($_POST[$key]);
        }
      }

      foreach(array_keys($ad8->wp_options) as $key){
        if(isset($_POST[$key])){
              $ad8->wp_options[$key] = filter_characters ($_POST[$key]);
        }
      }

     foreach(array_keys($ad9->wp_options) as $key){
        if(isset($_POST[$key])){
            $ad9->wp_options[$key] = filter_characters ($_POST[$key]);
        }
      }

      foreach(array_keys($ad10->wp_options) as $key){
        if(isset($_POST[$key])){
              $ad10->wp_options[$key] = filter_characters ($_POST[$key]);
        }
      }

      foreach(array_keys($ad11->wp_options) as $key){
        if(isset($_POST[$key])){
              $ad11->wp_options[$key] = filter_characters ($_POST[$key]);
        }
      }

      foreach(array_keys($ad12->wp_options) as $key){
        if(isset($_POST[$key])){
              $ad12->wp_options[$key] = filter_characters ($_POST[$key]);
        }
      }

     foreach(array_keys($ad13->wp_options) as $key){
        if(isset($_POST[$key])){
            $ad13->wp_options[$key] = filter_characters ($_POST[$key]);
        }
      }

      foreach(array_keys($ad14->wp_options) as $key){
        if(isset($_POST[$key])){
              $ad14->wp_options[$key] = filter_characters ($_POST[$key]);
        }
      }

      foreach(array_keys($ad15->wp_options) as $key){
        if(isset($_POST[$key])){
              $ad15->wp_options[$key] = filter_characters ($_POST[$key]);
        }
      }

      foreach(array_keys($ad16->wp_options) as $key){
        if(isset($_POST[$key])){
              $ad16->wp_options[$key] = filter_characters ($_POST[$key]);
        }
      }

      foreach(array_keys($adH->wp_options) as $key){
        if(isset($_POST[$key])){
              $adH->wp_options[$key] = filter_characters ($_POST[$key]);
        }
      }

      foreach(array_keys($adF->wp_options) as $key){
        if(isset($_POST[$key])){
              $adF->wp_options[$key] = filter_characters ($_POST[$key]);
        }
      }

      update_option (AD_OPTIONS, ai_plugin_options ($_POST ['syntax-highlighter-theme']));

      update_option (AD_AD1_OPTIONS,  $ad1->wp_options);
      update_option (AD_AD2_OPTIONS,  $ad2->wp_options);
      update_option (AD_AD3_OPTIONS,  $ad3->wp_options);
      update_option (AD_AD4_OPTIONS,  $ad4->wp_options);
      update_option (AD_AD5_OPTIONS,  $ad5->wp_options);
      update_option (AD_AD6_OPTIONS,  $ad6->wp_options);
      update_option (AD_AD7_OPTIONS,  $ad7->wp_options);
      update_option (AD_AD8_OPTIONS,  $ad8->wp_options);
      update_option (AD_AD9_OPTIONS,  $ad9->wp_options);
      update_option (AD_AD10_OPTIONS, $ad10->wp_options);
      update_option (AD_AD11_OPTIONS, $ad11->wp_options);
      update_option (AD_AD12_OPTIONS, $ad12->wp_options);
      update_option (AD_AD13_OPTIONS, $ad13->wp_options);
      update_option (AD_AD14_OPTIONS, $ad14->wp_options);
      update_option (AD_AD15_OPTIONS, $ad15->wp_options);
      update_option (AD_AD16_OPTIONS, $ad16->wp_options);

      update_option (AD_HEADER_OPTIONS, $adH->wp_options);
      update_option (AD_FOOTER_OPTIONS, $adF->wp_options);

    echo "<div id='message' class='updated' style='margin: 5px 15px 2px 0px; padding: 10px;'><strong>Settings saved.</strong></div>";

  } elseif (isset ($_POST [AD_FORM_CLEAR])) {
      $ad1  = new Block (1);
      $ad2  = new Block (2);
      $ad3  = new Block (3);
      $ad4  = new Block (4);
      $ad5  = new Block (5);
      $ad6  = new Block (6);
      $ad7  = new Block (7);
      $ad8  = new Block (8);
      $ad9  = new Block (9);
      $ad10 = new Block (10);
      $ad11 = new Block (11);
      $ad12 = new Block (12);
      $ad13 = new Block (13);
      $ad14 = new Block (14);
      $ad15 = new Block (15);
      $ad16 = new Block (16);

      $adH  = new AdH();
      $adF  = new AdF();

      update_option (AD_OPTIONS, ai_plugin_options ());
      update_option (AD_AD1_OPTIONS, $ad1->wp_options);
      update_option (AD_AD2_OPTIONS, $ad2->wp_options);
      update_option (AD_AD3_OPTIONS, $ad3->wp_options);
      update_option (AD_AD4_OPTIONS, $ad4->wp_options);
      update_option (AD_AD5_OPTIONS, $ad5->wp_options);
      update_option (AD_AD6_OPTIONS, $ad6->wp_options);
      update_option (AD_AD7_OPTIONS, $ad7->wp_options);
      update_option (AD_AD8_OPTIONS, $ad8->wp_options);
      update_option (AD_AD9_OPTIONS, $ad9->wp_options);
      update_option (AD_AD10_OPTIONS, $ad10->wp_options);
      update_option (AD_AD11_OPTIONS, $ad11->wp_options);
      update_option (AD_AD12_OPTIONS, $ad12->wp_options);
      update_option (AD_AD13_OPTIONS, $ad13->wp_options);
      update_option (AD_AD14_OPTIONS, $ad14->wp_options);
      update_option (AD_AD15_OPTIONS, $ad15->wp_options);
      update_option (AD_AD16_OPTIONS, $ad16->wp_options);
      update_option (AD_HEADER_OPTIONS, $adH->wp_options);
      update_option (AD_FOOTER_OPTIONS, $adF->wp_options);

      echo "<div id='message' class='error' style='margin: 5px 15px 2px 0px; padding: 10px;'>Settings cleared.</div>";
  }

  print_settings_form ();
}


function adinserter ($ad_number = ""){
  if ($ad_number == "") return "";

  $ad1  = new Block (1);
  $ad2  = new Block (2);
  $ad3  = new Block (3);
  $ad4  = new Block (4);
  $ad5  = new Block (5);
  $ad6  = new Block (6);
  $ad7  = new Block (7);
  $ad8  = new Block (8);
  $ad9  = new Block (9);
  $ad10 = new Block (10);
  $ad11 = new Block (11);
  $ad12 = new Block (12);
  $ad13 = new Block (13);
  $ad14 = new Block (14);
  $ad15 = new Block (15);
  $ad16 = new Block (16);

  $ad_all_data = array ($ad1,$ad2,$ad3,$ad4,$ad5,$ad6,$ad7,$ad8,$ad9,$ad10,$ad11,$ad12,$ad13,$ad14,$ad15,$ad16);

  if (!is_numeric ($ad_number)) return "";

  $ad_number = (int) $ad_number;

  if ($ad_number < 1 || $ad_number > 16) return "";

  // Load options from db
  $obj = $ad_all_data [$ad_number - 1];
  $obj->load_options ("AdInserter".$ad_number."Options");

  if ($obj->get_display_for_users () == AD_DISPLAY_LOGGED_IN_USERS && !is_user_logged_in ()) return "";
  if ($obj->get_display_for_users () == AD_DISPLAY_NOT_LOGGED_IN_USERS && is_user_logged_in ()) return "";

  if (!$obj->get_enable_php_call ()) return "";

  if (is_front_page ()){
    if (!$obj->get_display_settings_home()) return "";
  } else  if (is_single ()) {
    if (!$obj->get_display_settings_post ()) return "";
  } elseif (is_page ()) {
    if (!$obj->get_display_settings_page ()) return "";
  } elseif (is_category()){
    if (!$obj->get_display_settings_category()) return "";
  } elseif (is_search()){
    if (!$obj->get_display_settings_search()) return "";
  } elseif (is_archive()){
    if (!$obj->get_display_settings_archive()) return "";
  }

  //get post published date
  $publish_date = the_date ('U','','',false);

  //get referer
  $http_referer = '';
  if (isset ($_SERVER['HTTP_REFERER'])) {
      $http_referer = $_SERVER['HTTP_REFERER'];
  }

  if ($obj->get_ad_data() == AD_EMPTY_DATA) return "";

  if (ai_isCategoryAllowed ($obj->get_ad_block_cat(), $obj->get_ad_block_cat_type()) == false) return "";

  if (ai_isDisplayDateAllowed ($obj, $publish_date) == false) return "";

  if (ai_isRefererAllowed ($obj, $http_referer, $obj->get_ad_domain_list_type()) == false) return "";


  if ($obj->get_alignment_type() == AD_ALIGNMENT_NO_WRAPPING) $ad_code = ai_getAdCode ($obj); else
    $ad_code = "<div class='ad-inserter ad-inserter-".$ad_number."' style='" . $obj->get_alignmet_style() . "'>" . ai_getAdCode ($obj) . "</div>";

  return $ad_code;
}


function ai_content_hook ($content = ''){

  $ad1  = new Block (1);
  $ad2  = new Block (2);
  $ad3  = new Block (3);
  $ad4  = new Block (4);
  $ad5  = new Block (5);
  $ad6  = new Block (6);
  $ad7  = new Block (7);
  $ad8  = new Block (8);
  $ad9  = new Block (9);
  $ad10 = new Block (10);
  $ad11 = new Block (11);
  $ad12 = new Block (12);
  $ad13 = new Block (13);
  $ad14 = new Block (14);
  $ad15 = new Block (15);
  $ad16 = new Block (16);

  $ad_all_data = array ($ad1,$ad2,$ad3,$ad4,$ad5,$ad6,$ad7,$ad8,$ad9,$ad10,$ad11,$ad12,$ad13,$ad14,$ad15,$ad16);

  // Load options from db
  foreach ($ad_all_data as $key => $obj){
    $obj->load_options ("AdInserter".($key + 1)."Options");
  }

  //get post published date
  $publish_date = the_date ('U','','',false);

  //get referer
  $http_referer = '';
  if(isset($_SERVER['HTTP_REFERER'])) {
      $http_referer = $_SERVER['HTTP_REFERER'];
  }

  $content = generateAdInserterCode ($content, $ad_all_data, $publish_date, $http_referer);

  return $content;
}

// Process Before/After Excerpt postion
function ai_excerpt_hook ($content = ''){

  $ad1  = new Block (1);
  $ad2  = new Block (2);
  $ad3  = new Block (3);
  $ad4  = new Block (4);
  $ad5  = new Block (5);
  $ad6  = new Block (6);
  $ad7  = new Block (7);
  $ad8  = new Block (8);
  $ad9  = new Block (9);
  $ad10 = new Block (10);
  $ad11 = new Block (11);
  $ad12 = new Block (12);
  $ad13 = new Block (13);
  $ad14 = new Block (14);
  $ad15 = new Block (15);
  $ad16 = new Block (16);

  $ad_all_data = array ($ad1,$ad2,$ad3,$ad4,$ad5,$ad6,$ad7,$ad8,$ad9,$ad10,$ad11,$ad12,$ad13,$ad14,$ad15,$ad16);

  // Load options from db
  foreach ($ad_all_data as $key => $obj){
    $obj->load_options ("AdInserter".($key + 1)."Options");
  }

  //get referer
  $http_referer = '';
  if(isset($_SERVER['HTTP_REFERER'])) {
      $http_referer = $_SERVER['HTTP_REFERER'];
  }


  if (!defined ('AD_INSERTER_EXCERPT_1')) {
    define ('AD_INSERTER_EXCERPT_1', true);
  }
  elseif (!defined ('AD_INSERTER_EXCERPT_2')) {
    define ('AD_INSERTER_EXCERPT_2', true);
  }
  elseif (!defined ('AD_INSERTER_EXCERPT_3')) {
    define ('AD_INSERTER_EXCERPT_3', true);
  }
  elseif (!defined ('AD_INSERTER_EXCERPT_4')) {
    define ('AD_INSERTER_EXCERPT_4', true);
  }
  elseif (!defined ('AD_INSERTER_EXCERPT_5')) {
    define ('AD_INSERTER_EXCERPT_5', true);
  }
  elseif (!defined ('AD_INSERTER_EXCERPT_6')) {
    define ('AD_INSERTER_EXCERPT_6', true);
  }
  elseif (!defined ('AD_INSERTER_EXCERPT_7')) {
    define ('AD_INSERTER_EXCERPT_7', true);
  }
  elseif (!defined ('AD_INSERTER_EXCERPT_8')) {
    define ('AD_INSERTER_EXCERPT_8', true);
  }
  elseif (!defined ('AD_INSERTER_EXCERPT_9')) {
    define ('AD_INSERTER_EXCERPT_9', true);
  }

  $excerpt_counter = 0;
  if (defined ('AD_INSERTER_EXCERPT_1')) $excerpt_counter ++;
  if (defined ('AD_INSERTER_EXCERPT_2')) $excerpt_counter ++;
  if (defined ('AD_INSERTER_EXCERPT_3')) $excerpt_counter ++;
  if (defined ('AD_INSERTER_EXCERPT_4')) $excerpt_counter ++;
  if (defined ('AD_INSERTER_EXCERPT_5')) $excerpt_counter ++;
  if (defined ('AD_INSERTER_EXCERPT_6')) $excerpt_counter ++;
  if (defined ('AD_INSERTER_EXCERPT_7')) $excerpt_counter ++;
  if (defined ('AD_INSERTER_EXCERPT_8')) $excerpt_counter ++;
  if (defined ('AD_INSERTER_EXCERPT_9')) $excerpt_counter ++;

  foreach ($ad_all_data as $block_index => $obj){

    if ($obj->get_display_for_users () == AD_DISPLAY_LOGGED_IN_USERS && !is_user_logged_in ()) continue;
    if ($obj->get_display_for_users () == AD_DISPLAY_NOT_LOGGED_IN_USERS && is_user_logged_in ()) continue;

    if ($obj->get_append_type () != AD_SELECT_BEFORE_EXCERPT && $obj->get_append_type () != AD_SELECT_AFTER_EXCERPT) continue;

    if ($obj->get_excerpt_number() != 0 && $obj->get_excerpt_number() != $excerpt_counter) continue;

    if (is_front_page ()){
      if (!$obj->get_display_settings_home()) continue;
    }
    elseif (is_category()){
      if (!$obj->get_display_settings_category()) continue;
    }
    elseif (is_search()){
      if (!$obj->get_display_settings_search()) continue;
    }
    elseif (is_archive()){
      if (!$obj->get_display_settings_archive()) continue;
    }

    //if empty data, continue with next
    if ($obj->get_ad_data() == AD_EMPTY_DATA){
      continue;
    }

    if (ai_isDisplayDisabled ($obj, $content)){
      continue;
    }

    if (ai_isCategoryAllowed ($obj->get_ad_block_cat(), $obj->get_ad_block_cat_type())==false){
      continue;
    }

    if (ai_isRefererAllowed ($obj, $http_referer, $obj->get_ad_domain_list_type()) == false){
      continue;
    }

    if ($obj->get_alignment_type() == AD_ALIGNMENT_NO_WRAPPING) $ad_code .= ai_getAdCode ($obj); else
      $ad_code .= "<div class='ad-inserter ad-inserter-".($block_index + 1)."' style='" . $obj->get_alignmet_style() . "'>" . ai_getAdCode ($obj) . "</div>";

    if ($obj->get_append_type () == AD_SELECT_BEFORE_EXCERPT)
        $content = $ad_code . $content; else
          $content = $content . $ad_code;
  }

  return $content;
}

// Process Before Title postion
function ai_loop_start_hook ($query){

  if (!$query->is_main_query()) return;
  if (is_feed()) return;

  $ad1  = new Block (1);
  $ad2  = new Block (2);
  $ad3  = new Block (3);
  $ad4  = new Block (4);
  $ad5  = new Block (5);
  $ad6  = new Block (6);
  $ad7  = new Block (7);
  $ad8  = new Block (8);
  $ad9  = new Block (9);
  $ad10 = new Block (10);
  $ad11 = new Block (11);
  $ad12 = new Block (12);
  $ad13 = new Block (13);
  $ad14 = new Block (14);
  $ad15 = new Block (15);
  $ad16 = new Block (16);

  $ad_all_data = array ($ad1,$ad2,$ad3,$ad4,$ad5,$ad6,$ad7,$ad8,$ad9,$ad10,$ad11,$ad12,$ad13,$ad14,$ad15,$ad16);

  // Load options from db
  foreach($ad_all_data as $key => $obj){
    $obj->load_options ("AdInserter".($key + 1)."Options");
  }

  //get post published date
  $publish_date = the_date ('U','','',false);

  //get referer
  $http_referer = '';
  if(isset($_SERVER['HTTP_REFERER'])) {
     $http_referer = $_SERVER['HTTP_REFERER'];
  }

  $ad_code = "";

  foreach ($ad_all_data as $block_index => $obj){

    if ($obj->get_display_for_users () == AD_DISPLAY_LOGGED_IN_USERS && !is_user_logged_in ()) continue;
    if ($obj->get_display_for_users () == AD_DISPLAY_NOT_LOGGED_IN_USERS && is_user_logged_in ()) continue;

    if ($obj->get_append_type () != AD_SELECT_BEFORE_TITLE) continue;

    if (is_front_page ()){
      if (!$obj->get_display_settings_home()) continue;
    }
    elseif (is_page()){
      if (!$obj->get_display_settings_page()) continue;
    }
    elseif (is_single()){
      if (!$obj->get_display_settings_post()) continue;
    }
    elseif (is_category()){
      if (!$obj->get_display_settings_category()) continue;
    }
    elseif (is_search()){
      if (!$obj->get_display_settings_search()) continue;
    }
    elseif (is_archive()){
      if (!$obj->get_display_settings_archive()) continue;
    }

    //if empty data, continue with next
    if ($obj->get_ad_data() == AD_EMPTY_DATA){
      continue;
    }

    if (ai_isCategoryAllowed ($obj->get_ad_block_cat(), $obj->get_ad_block_cat_type())==false){
      continue;
    }

    if (ai_isDisplayDateAllowed ($obj, $publish_date)==false){
      continue;
    }

    if (ai_isRefererAllowed ($obj, $http_referer, $obj->get_ad_domain_list_type()) == false){
      continue;
    }

    if ($obj->get_alignment_type() == AD_ALIGNMENT_NO_WRAPPING) $ad_code .= ai_getAdCode ($obj); else
      $ad_code .= "<div class='ad-inserter ad-inserter-".($block_index + 1)."' style='" . $obj->get_alignmet_style() . "'>" . ai_getAdCode ($obj) . "</div>";
  }

  echo $ad_code;
}

function ai_isCategoryAllowed ($categories, $cat_type){

  $categories = trim(strtolower($categories));

  //echo ' listed categories: ' . $categories;

  if ($cat_type == AD_BLACK_LIST) {

    if($categories == AD_EMPTY_DATA) {
      return true;
    }

    $cats_listed = explode(",", strtolower($categories));

    foreach((get_the_category()) as $post_category) {

      //echo '<br/> post category name : ' . $post_category->cat_name;

      foreach($cats_listed as $cat_disabled){

        $post_category_name = strtolower($post_category->cat_name);

        //echo '<br/>Category disabled loop : ' . $cat_disabled . '<br/> category name : ' . $post_category_name;

        if($post_category_name==trim($cat_disabled)){
          //echo ' match';
          return false;
        }else{
          //echo ' not match';
        }
      }
    }
    return true;

  } else {

      if($categories == AD_EMPTY_DATA){
        return false;
      }

      $cats_listed = explode(",", strtolower($categories));

      foreach((get_the_category()) as $post_category) {

        //echo '<br/> post category name : ' . $post_category->cat_name;

        foreach($cats_listed as $cat_enabled){

          $post_category_name = strtolower($post_category->cat_name);

          //echo '<br/>Category enabled loop : ' . $cat_enabled . '<br/> category name : ' . $post_category_name;

          if($post_category_name==trim($cat_enabled)){
            //echo ' match';
            return true;
          }else{
            //echo ' not match';
          }
        }
      }
      return false;
    }
}

function ai_isDisplayDisabled ($obj, $content){

  if (preg_match ("/<!-- +Ad +Inserter +Ad +".($ad_name = $obj->number)." +Disabled +-->/i", $content)) return true;

  if (preg_match ("/<!-- +disable +adinserter +".($obj->number)." +-->/i", $content)) return true;

  if (preg_match ("/<!-- +disable +adinserter +".(trim ($obj->get_ad_name()))." +-->/i", $content)) return true;

  return false;
}

function ai_isDisplayDateAllowed ($obj, $publish_date){

  $after_days = trim ($obj->get_ad_after_day());

  // If 0 display immediately
  if($after_days == AD_ZERO_DATA || $after_days == AD_EMPTY_DATA){
    return true;
  }

  return (date ('U', time ()) >= $publish_date + $after_days * 86400);
}


function ai_isRefererAllowed ($obj, $referer, $domain_list_type) {

  if ($domain_list_type == AD_BLACK_LIST) {

    if ($referer == "") return true;

    $domains = trim ($obj->get_ad_domain_list());
    if ($domains == "") return true;
    $domains = explode (",", $domains);

    foreach ($domains as $domain) {

      if ($domain == "") continue;

      if (preg_match ("/" . $domain . "/i", $referer)) return false;
    }
    return true;

  } else {

      if ($referer == "") return false;

      $domains = trim ($obj->get_ad_domain_list());
      if ($domains == "") return false;
      $domains = explode (",", $domains);

      foreach ($domains as $domain) {

        if ($domain == "") continue;

        if (preg_match ("/" . $domain . "/i", $referer)) return true;
      }
      return false;
    }
}

function generateAdInserterCode ($content, $ad_all_data, $publish_date, $http_referer){

  foreach($ad_all_data as $index => $obj) {

    if ($obj->get_display_for_users () == AD_DISPLAY_LOGGED_IN_USERS && !is_user_logged_in ()) continue;
    if ($obj->get_display_for_users () == AD_DISPLAY_NOT_LOGGED_IN_USERS && is_user_logged_in ()) continue;

    //if empty data, continue next
    if ($obj->get_ad_data() == AD_EMPTY_DATA) {
      continue;
    }

    if (is_single ()) {
      if (!$obj->get_display_settings_post ()) continue;
    } elseif (is_page ()) {
      if (!$obj->get_display_settings_page ()) continue;
    } else continue;

    if (ai_isDisplayDisabled ($obj, $content)) {
      continue;
    }

    if (ai_isCategoryAllowed ($obj->get_ad_block_cat(), $obj->get_ad_block_cat_type()) == false) {
      continue;
    }

    if (ai_isDisplayDateAllowed ($obj, $publish_date) == false) {
      continue;
    }

    if (ai_isRefererAllowed ($obj, $http_referer, $obj->get_ad_domain_list_type()) == false) {
      continue;
    }

    if ($obj->get_append_type() == AD_SELECT_BEFORE_PARAGRAPH) {
      $content = ai_generateBeforeParagraph ($index + 1, $content, $obj);
    } elseif ($obj->get_append_type() == AD_SELECT_AFTER_PARAGRAPH) {
      $content = ai_generateBeforeParagraph ($index + 1, $content, $obj, false);
    } elseif ($obj->get_append_type () == AD_SELECT_BEFORE_CONTENT) {
      $content = ai_generateDivBefore ($index + 1, $content, $obj);
    } elseif ($obj->get_append_type() == AD_SELECT_AFTER_CONTENT) {
      $content = ai_generateDivAfter ($index + 1, $content, $obj);
    }

    if ($obj->get_enable_manual ()) $content = ai_generateDivManual ($index + 1, $content, $obj, $index + 1);
  }

   // Clean remaining tags
   $content = preg_replace ("/{adinserter (.*)}/", "", $content);

//   $content .= AD_AUTHOR_SITE;

  return $content;
}


function ai_getCode ($obj){
  $code = $obj->get_ad_data();

  if ($obj->get_process_php ()) {
    ob_start ();
    eval ("?>". $code . "<?php ");
    $code = ob_get_clean ();
  }

  return $code;
}


function ai_getAdCode ($obj){
  $ad_code = $obj->get_ad_data_replaced();

  if ($obj->get_process_php ()) {
    ob_start ();
    eval ("?>". $ad_code . "<?php ");
    $ad_code = ob_get_clean ();
  }

  if (strpos ($ad_code, AD_SEPARATOR) !== false) {
    $ads = explode (AD_SEPARATOR, $ad_code);
    $ad_code = $ads [rand (0, sizeof ($ads) - 1)];
  }
  return $ad_code;
}


function ai_generateBeforeParagraph ($block, $content, $obj, $before = true){

  $paragraph_positions = array ();
  $poseslast = array ();
  $last_position = - 1;

  $paragraph_start = "<p";

  while (strpos ($content, $paragraph_start, $last_position + 1) !== false){
    $last_position = strpos ($content, $paragraph_start, $last_position + 1);
    if ($content [$last_position + 2] == ">" || $content [$last_position + 2] == " ")
      $paragraph_positions [] = $last_position;
  }

  $paragraph_texts = explode (",", $obj->get_paragraph_text());
  if ($obj->get_paragraph_text() != "" && count ($paragraph_texts != 0)) {
    $filtered_paragraph_positions = array ();
    foreach ($paragraph_positions as $index => $paragraph_position) {
      $paragraph_code = $index == count ($paragraph_positions) - 1 ? substr ($content, $paragraph_position) : substr ($content, $paragraph_position, $paragraph_positions [$index + 1] - $paragraph_position);
      $found = false;
      foreach ($paragraph_texts as $paragraph_text) {
        if (stripos ($paragraph_code, trim ($paragraph_text)) !== false) {
          $found = true;
          break;
        }
      }
      if (!$found) $filtered_paragraph_positions [] = $paragraph_position;
    }
    $paragraph_positions = $filtered_paragraph_positions;
  }

  $para = $obj->get_paragraph_number();

  if ($before) {
    if ($para <= 0) {
      $para = rand (0, sizeof ($paragraph_positions) - 1);
    } elseif ($obj->get_direction_type() == AD_DIRECTION_FROM_BOTTOM) {
      $paragraph_positions = array_reverse ($paragraph_positions);
      $para = $para - 1;
    } else $para --;
  } else {
      if ($para <= 0) {
        $para = rand (1, sizeof ($paragraph_positions));
      } elseif ($obj->get_direction_type() == AD_DIRECTION_FROM_BOTTOM) {
        $para = sizeof ($paragraph_positions) - $para + 1;
        if ($para <= 0) {
          return $content;
        }
      }
    }

  if (sizeof ($paragraph_positions) >= $obj->get_paragraph_number_minimum()) {
    if (!$before && $para == sizeof ($paragraph_positions)) {
      if ($obj->get_alignment_type() == AD_ALIGNMENT_NO_WRAPPING) $content .= ai_getAdCode ($obj); else
        $content .= "<div class='ad-inserter ad-inserter-".$block."' style='" . $obj->get_alignmet_style() . "'>" . ai_getAdCode ($obj) . "</div>";
    } elseif (sizeof ($paragraph_positions) > $para) {
        $pickme = $paragraph_positions [$para];

        if ($obj->get_alignment_type() == AD_ALIGNMENT_NO_WRAPPING) $content = substr_replace ($content, ai_getAdCode ($obj), $pickme, 0); else
          $content = substr_replace ($content, "<div class='ad-inserter ad-inserter-".$block."' style='" . $obj->get_alignmet_style() . "'>" . ai_getAdCode ($obj) . "</div>", $pickme, 0);
      }
  }

  return $content;
}

function ai_generateDivBefore ($block, $content, $obj){
  if ($obj->get_alignment_type() == AD_ALIGNMENT_NO_WRAPPING) return ai_getAdCode ($obj) . $content; else
    return "<div class='ad-inserter ad-inserter-".$block."' style='" . $obj->get_alignmet_style() . "'>" . ai_getAdCode ($obj) . "</div>" . $content;
}

function ai_generateDivAfter ($block, $content, $obj){
  if ($obj->get_alignment_type() == AD_ALIGNMENT_NO_WRAPPING) return $content . ai_getAdCode ($obj); else
    return $content . "<div class='ad-inserter ad-inserter-".$block."' style='" . $obj->get_alignmet_style() . "'>" . ai_getAdCode ($obj) . "</div>";
}

function ai_generateDivManual ($block, $content, $obj, $ad_number){

   if (preg_match_all("/{adinserter (.+?)}/", $content, $tags)){
     foreach ($tags [1] as $tag) {
        $ad_tag = strtolower (trim ($tag));
        $ad_name = strtolower (trim ($obj->get_ad_name()));
        if ($ad_tag == $ad_name || $ad_tag == $ad_number) {
         if ($obj->get_alignment_type() == AD_ALIGNMENT_NO_WRAPPING) $ad_code = ai_getAdCode ($obj); else
           $ad_code = "<div class='ad-inserter ad-inserter-".$block."' style='" . $obj->get_alignmet_style() . "'>" . ai_getAdCode ($obj) . "</div>";
         $content = preg_replace ("/{adinserter " . $tag . "}/", $ad_code, $content);
        }
     }
   }

   return $content;
}

function process_shortcodes ($atts) {
  $ad1  = new Block (1);
  $ad2  = new Block (2);
  $ad3  = new Block (3);
  $ad4  = new Block (4);
  $ad5  = new Block (5);
  $ad6  = new Block (6);
  $ad7  = new Block (7);
  $ad8  = new Block (8);
  $ad9  = new Block (9);
  $ad10 = new Block (10);
  $ad11 = new Block (11);
  $ad12 = new Block (12);
  $ad13 = new Block (13);
  $ad14 = new Block (14);
  $ad15 = new Block (15);
  $ad16 = new Block (16);

  $ad_all_data = array ($ad1,$ad2,$ad3,$ad4,$ad5,$ad6,$ad7,$ad8,$ad9,$ad10,$ad11,$ad12,$ad13,$ad14,$ad15,$ad16);

  // Load options from db
  foreach($ad_all_data as $key => $obj){
    $obj->load_options ("AdInserter".($key + 1)."Options");
  }

  $parameters = shortcode_atts ( array(
    "block" => "",
    "name" => "",
  ), $atts);
  if (is_numeric ($parameters ['block'])) $block = intval ($parameters ['block']); else $block = 0;
  if ($block < 1 && $block > 16) {
    $block = 0;
  } elseif ($parameters ['name'] != '') {
      $shortcode_name = strtolower ($parameters ['name']);
      foreach ($ad_all_data as $index => $obj) {
        $ad_name = strtolower (trim ($obj->get_ad_name()));
        if ($shortcode_name == $ad_name) {
          $block = $index + 1;
          break;
        }
      }
    }

  if ($block != 0) {
    $obj = $ad_all_data [$block - 1];
    if ($obj->get_enable_manual ()) {
      if ($obj->get_display_for_users () == AD_DISPLAY_LOGGED_IN_USERS && !is_user_logged_in ()) return "";
      if ($obj->get_display_for_users () == AD_DISPLAY_NOT_LOGGED_IN_USERS && is_user_logged_in ()) return "";

      if ($obj->get_alignment_type() == AD_ALIGNMENT_NO_WRAPPING) $ad_code = ai_getAdCode ($obj); else
        $ad_code = "<div class='ad-inserter ad-inserter-".$block."' style='" . $obj->get_alignmet_style() . "'>" . ai_getAdCode ($obj) . "</div>";
      return ($ad_code);
    }
  }
  return "";
}

function ai_widget ($args, $parameters) {
  $block = $parameters ['block'];
  $ad = new Block ($block);
  $ad->load_options (str_replace ("#", $block, AD_ADx_OPTIONS));
  ai_widget_draw ($block, $ad, $args);
}

function ai_widget_draw ($block, $obj, $args) {

  if ($obj->get_display_for_users () == AD_DISPLAY_LOGGED_IN_USERS && !is_user_logged_in ()) return;
  if ($obj->get_display_for_users () == AD_DISPLAY_NOT_LOGGED_IN_USERS && is_user_logged_in ()) return;

  //get post published date
  $publish_date = the_date ('U','','',false);

  $http_referer = '';
  if(isset($_SERVER['HTTP_REFERER'])) {
      $http_referer = $_SERVER['HTTP_REFERER'];
  }

  if ($obj->get_append_type () != AD_SELECT_WIDGET) return;

  //if empty data, continue next
  if($obj->get_ad_data()==AD_EMPTY_DATA){
     return;
  }

  if(is_front_page ()){
     if (!$obj->get_display_settings_home()) return;
  }
  elseif(is_page()){
     if (!$obj->get_display_settings_page()) return;
  }
  elseif(is_single()){
     if (!$obj->get_display_settings_post()) return;
  }
  elseif(is_category()){
     if (!$obj->get_display_settings_category()) return;
  }
  elseif(is_search()){
     if (!$obj->get_display_settings_search()) return;
  }
  elseif(is_archive()){
     if (!$obj->get_display_settings_archive()) return;
  }

  if(ai_isCategoryAllowed($obj->get_ad_block_cat(), $obj->get_ad_block_cat_type())==false){
     return;
  }

  if(ai_isDisplayDateAllowed($obj, $publish_date)==false){
     return;
  }

  if(ai_isRefererAllowed($obj, $http_referer, $obj->get_ad_domain_list_type()) == false){
     return;
  }


  if ($obj->get_alignment_type() == AD_ALIGNMENT_NO_WRAPPING) echo $args['before_widget'] . ai_getAdCode ($obj) . $args['after_widget']; else
    echo $args['before_widget'] . "<div class='ad-inserter ad-inserter-".$block."' style='" . $obj->get_alignmet_style(false) . "'>" . ai_getAdCode ($obj) . "</div>" . $args['after_widget'];
}
