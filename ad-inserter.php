<?php

/*
Plugin Name: Ad Inserter
Version: 1.3.5
Description: An elegant solution to put any ad into Wordpress. Simply enter any HTML, Javascript or PHP code and select where and how you want to display it (including Widgets). You can also use {category}, {short_category}, {title}, {short_title}, {tag}, {smart_tag} or {search_query} tags to get actual post data. To rotate different ad versions separate them with |rotate|. Manual insertion is also possible with {adinserter AD_NAME} or {adinserter AD_NUMBER} tag.
Author: Spacetime
Author URI: http://igorfuna.com/
Plugin URI: http://igorfuna.com/software/web/ad-inserter-wordpress-plugin
*/

/*
TO DO
- Below title
*/

/*
Change Log

Ad Inserter 1.3.5 - 18 March 2014
* Fixed bug: missing echo for PHP function call example

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

//hook
add_action ('admin_menu',       'ai_admin_menu');
add_filter ('the_content',      'ai_content_hook', 99999);
add_filter ('the_excerpt',      'ai_excerpt_hook', 99999);
add_action ('loop_start',       'ai_loop_start_hook');
add_action ('init',             'AdInserter_Init');
add_action ('admin_notices',    'ai_admin_notice');

function AdInserter_Init() {

  $ad1  = new Ad1();
  $ad2  = new Ad2();
  $ad3  = new Ad3();
  $ad4  = new Ad4();
  $ad5  = new Ad5();
  $ad6  = new Ad6();
  $ad7  = new Ad7();
  $ad8  = new Ad8();
  $ad9  = new Ad9();
  $ad10 = new Ad10();
  $ad11 = new Ad11();
  $ad12 = new Ad12();
  $ad13 = new Ad13();
  $ad14 = new Ad14();
  $ad15 = new Ad15();
  $ad16 = new Ad16();

  $ad_all_data = array ($ad1,$ad2,$ad3,$ad4,$ad5,$ad6,$ad7,$ad8,$ad9,$ad10,$ad11,$ad12,$ad13,$ad14,$ad15,$ad16);

  // Load options from db
  foreach($ad_all_data as $key => $obj){
    $obj->load_options ("AdInserter".($key + 1)."Options");
  }

  foreach ($ad_all_data as $key => $obj){
    if($obj->get_append_type() == AD_SELECT_WIDGET){
      $ad_counter = $key + 1;
      // register widget
      $widget_options = array ('classname' => 'ai_widget', 'description' => "Put any ad or HTML/PHP/Javascript code into the Sidebar." );
      wp_register_sidebar_widget ('ai_widget'.$ad_counter, $obj->get_ad_name(), 'ai_widget'.$ad_counter, $widget_options);

    }
  }
}

function ai_admin_menu () {
  global $ai_settings_page;

  $ai_settings_page = add_submenu_page ('options-general.php', 'Ad Inserter Options', 'Ad Inserter', 8, basename(__FILE__), 'ai_menu');
  add_action ('admin_enqueue_scripts', 'ai_admin_enqueue_scripts');
}


function ai_admin_enqueue_scripts ($hook_suffix) {
  global $ai_settings_page;

  if ($hook_suffix == $ai_settings_page) {
    wp_enqueue_script ('ad-inserter-js',        plugins_url ('js/ad-inserter.js', __FILE__), array ('jquery', 'jquery-ui-tabs', 'jquery-ui-button', 'jquery-ui-tooltip'));
    wp_enqueue_style  ('ad-inserter-jquery-ui', plugins_url ('css/jquery-ui-1.10.3.custom.min.css', __FILE__), false, null);
    wp_enqueue_style  ('ad-inserter-css',       plugins_url ('css/ad-inserter.css', __FILE__), false, null);
  }
}

function ai_admin_notice () {
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

function filter_characters ($str){

  $str = str_replace (array ("\\\""), array ("\""), $str);
  return $str;
}

function ai_plugin_options (){
  $plugin_options = array ();

  $version_array = explode (".", AD_INSERTER_VERSION);
  $version_string = "";
  foreach ($version_array as $number) {
    $version_string .= sprintf ("%02d", $number);
  }

  $plugin_options ['VERSION'] = $version_string;

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

function ai_menu () {

  $ad1  = new Ad1();
  $ad2  = new Ad2();
  $ad3  = new Ad3();
  $ad4  = new Ad4();
  $ad5  = new Ad5();
  $ad6  = new Ad6();
  $ad7  = new Ad7();
  $ad8  = new Ad8();
  $ad9  = new Ad9();
  $ad10 = new Ad10();
  $ad11 = new Ad11();
  $ad12 = new Ad12();
  $ad13 = new Ad13();
  $ad14 = new Ad14();
  $ad15 = new Ad15();
  $ad16 = new Ad16();

  if (isset ($_POST [AD_FORM_SAVE])) {

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

      update_option (AD_OPTIONS, ai_plugin_options ());

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

    echo "<div id='message' class='updated' style='margin: 5px 15px 2px 0px; padding: 10px;'><strong>Settings saved.</strong></div>";


  } elseif (isset ($_POST [AD_FORM_CLEAR])) {
      $ad1  = new Ad1();
      $ad2  = new Ad2();
      $ad3  = new Ad3();
      $ad4  = new Ad4();
      $ad5  = new Ad5();
      $ad6  = new Ad6();
      $ad7  = new Ad7();
      $ad8  = new Ad8();
      $ad9  = new Ad9();
      $ad10 = new Ad10();
      $ad11 = new Ad11();
      $ad12 = new Ad12();
      $ad13 = new Ad13();
      $ad14 = new Ad14();
      $ad15 = new Ad15();
      $ad16 = new Ad16();

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

      echo "<div id='message' class='error' style='margin: 5px 15px 2px 0px; padding: 10px;'>Settings cleared.</div>";
  }

  $ad_all_data = array($ad1,$ad2,$ad3,$ad4,$ad5,$ad6,$ad7,$ad8,$ad9,$ad10,$ad11,$ad12,$ad13,$ad14,$ad15,$ad16);

  // Load options from db
  foreach ($ad_all_data as $key => $obj){
    $obj->load_options ("AdInserter".($key + 1)."Options");
  }

  print_settings_form ($ad_all_data);
}


function adinserter ($ad_number = ""){
  if ($ad_number == "") return "";

  $ad1  = new Ad1();
  $ad2  = new Ad2();
  $ad3  = new Ad3();
  $ad4  = new Ad4();
  $ad5  = new Ad5();
  $ad6  = new Ad6();
  $ad7  = new Ad7();
  $ad8  = new Ad8();
  $ad9  = new Ad9();
  $ad10 = new Ad10();
  $ad11 = new Ad11();
  $ad12 = new Ad12();
  $ad13 = new Ad13();
  $ad14 = new Ad14();
  $ad15 = new Ad15();
  $ad16 = new Ad16();

  $ad_all_data = array ($ad1,$ad2,$ad3,$ad4,$ad5,$ad6,$ad7,$ad8,$ad9,$ad10,$ad11,$ad12,$ad13,$ad14,$ad15,$ad16);

  if (!is_numeric ($ad_number)) return "";

  $ad_number = (int) $ad_number;

  if ($ad_number < 1 || $ad_number > 16) return "";

  // Load options from db
  $obj = $ad_all_data [$ad_number - 1];
  $obj->load_options ("AdInserter".$ad_number."Options");

  if (!$obj->get_enable_php_call ()) return "";

  if (is_home()){
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
    $ad_code = "<div style='" . $obj->get_alignmet_style() . "'>" . ai_getAdCode ($obj) . "</div>";

  return $ad_code;
}

function ai_content_hook ($content = ''){

  $ad1  = new Ad1();
  $ad2  = new Ad2();
  $ad3  = new Ad3();
  $ad4  = new Ad4();
  $ad5  = new Ad5();
  $ad6  = new Ad6();
  $ad7  = new Ad7();
  $ad8  = new Ad8();
  $ad9  = new Ad9();
  $ad10 = new Ad10();
  $ad11 = new Ad11();
  $ad12 = new Ad12();
  $ad13 = new Ad13();
  $ad14 = new Ad14();
  $ad15 = new Ad15();
  $ad16 = new Ad16();

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

  $ad1  = new Ad1();
  $ad2  = new Ad2();
  $ad3  = new Ad3();
  $ad4  = new Ad4();
  $ad5  = new Ad5();
  $ad6  = new Ad6();
  $ad7  = new Ad7();
  $ad8  = new Ad8();
  $ad9  = new Ad9();
  $ad10 = new Ad10();
  $ad11 = new Ad11();
  $ad12 = new Ad12();
  $ad13 = new Ad13();
  $ad14 = new Ad14();
  $ad15 = new Ad15();
  $ad16 = new Ad16();

  $ad_all_data = array ($ad1,$ad2,$ad3,$ad4,$ad5,$ad6,$ad7,$ad8,$ad9,$ad10,$ad11,$ad12,$ad13,$ad14,$ad15,$ad16);

  // Load options from db
  foreach ($ad_all_data as $key => $obj){
    $obj->load_options ("AdInserter".($key + 1)."Options");
  }

  //get post published date
//  $publish_date = the_date ('U','','',false);

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

    if ($obj->get_append_type () != AD_SELECT_BEFORE_EXCERPT && $obj->get_append_type () != AD_SELECT_AFTER_EXCERPT) continue;

    if ($obj->get_excerpt_number() != 0 && $obj->get_excerpt_number() != $excerpt_counter) continue;

    if (is_home()){
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

//    if (ai_isDisplayDateAllowed ($obj, $publish_date)==false){
//      continue;
//    }

    if (ai_isRefererAllowed ($obj, $http_referer, $obj->get_ad_domain_list_type()) == false){
      continue;
    }

    if ($obj->get_alignment_type() == AD_ALIGNMENT_NO_WRAPPING) $ad_code .= ai_getAdCode ($obj); else
      $ad_code .= "<div style='" . $obj->get_alignmet_style() . "'>" . ai_getAdCode ($obj) . "</div>";

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

  $ad1  = new Ad1();
  $ad2  = new Ad2();
  $ad3  = new Ad3();
  $ad4  = new Ad4();
  $ad5  = new Ad5();
  $ad6  = new Ad6();
  $ad7  = new Ad7();
  $ad8  = new Ad8();
  $ad9  = new Ad9();
  $ad10 = new Ad10();
  $ad11 = new Ad11();
  $ad12 = new Ad12();
  $ad13 = new Ad13();
  $ad14 = new Ad14();
  $ad15 = new Ad15();
  $ad16 = new Ad16();

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

  foreach ($ad_all_data as $obj){

    if ($obj->get_append_type () != AD_SELECT_BEFORE_TITLE) continue;

    if (is_home()){
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
      $ad_code .= "<div style='" . $obj->get_alignmet_style() . "'>" . ai_getAdCode ($obj) . "</div>";
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
      $content = ai_generateBeforeParagraph ($content, $obj);
    } elseif ($obj->get_append_type () == AD_SELECT_BEFORE_CONTENT) {
      $content = ai_generateDivBefore ($content, $obj);
    } elseif ($obj->get_append_type() == AD_SELECT_AFTER_CONTENT) {
      $content = ai_generateDivAfter ($content, $obj);
    }

    if ($obj->get_enable_manual ()) $content = ai_generateDivManual ($content, $obj, $index + 1);
  }

   // Clean remaining tags
   $content = preg_replace ("/{adinserter (.*)}/", "", $content);

//   $content .= AD_AUTHOR_SITE;

  return $content;
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


function ai_generateBeforeParagraph ($content, $obj){

  $paragraph_positions = array ();
  $poseslast = array ();
  $last_position = - 1;

  $paragraph_start = "<p";

  while (strpos ($content, $paragraph_start, $last_position + 1) !== false){
    $last_position = strpos ($content, $paragraph_start, $last_position + 1);
    if ($content [$last_position + 2] == ">" || $content [$last_position + 2] == " ")
      $paragraph_positions [] = $last_position;
  }

  $para = $obj->get_paragraph_number();
  if ($para <= 0) {
    $para = rand (0, sizeof ($paragraph_positions) - 1);
  } elseif ($obj->get_direction_type() == AD_DIRECTION_FROM_BOTTOM) {
    $paragraph_positions = array_reverse ($paragraph_positions);
    $para = $para - 1;
  } else $para --;


  if(sizeof($paragraph_positions) > $para && sizeof ($paragraph_positions) >= $obj->get_paragraph_number_minimum()) {
     $pickme = $paragraph_positions [$para];

     if ($obj->get_alignment_type() == AD_ALIGNMENT_NO_WRAPPING) $content = substr_replace ($content, ai_getAdCode ($obj), $pickme, 0); else
       $content = substr_replace ($content, "<div style='" . $obj->get_alignmet_style() . "'>" . ai_getAdCode ($obj) . "</div>", $pickme, 0);

     //reset it
     $last_position = -1;
  }

  return $content;
}

function ai_generateDivBefore ($content, $obj){
  if ($obj->get_alignment_type() == AD_ALIGNMENT_NO_WRAPPING) return ai_getAdCode ($obj) . $content; else
    return "<div style='" . $obj->get_alignmet_style() . "'>" . ai_getAdCode ($obj) . "</div>" . $content;
}

function ai_generateDivAfter ($content, $obj){
  if ($obj->get_alignment_type() == AD_ALIGNMENT_NO_WRAPPING) return $content . ai_getAdCode ($obj); else
    return $content . "<div style='" . $obj->get_alignmet_style() . "'>" . ai_getAdCode ($obj) . "</div>";
}

function ai_generateDivManual ($content, $obj, $ad_number){

   if (preg_match_all("/{adinserter (.+?)}/", $content, $tags)){
     foreach ($tags [1] as $tag) {
        $ad_tag = strtolower (trim ($tag));
        $ad_name = strtolower (trim ($obj->get_ad_name()));
        if ($ad_tag == $ad_name || $ad_tag == $ad_number) {
         if ($obj->get_alignment_type() == AD_ALIGNMENT_NO_WRAPPING) $ad_code = ai_getAdCode ($obj); else
           $ad_code = "<div style='" . $obj->get_alignmet_style() . "'>" . ai_getAdCode ($obj) . "</div>";
         $content = preg_replace ("/{adinserter " . $tag . "}/", $ad_code, $content);
        }
     }
   }

   return $content;
}

function ai_widget1($args) {

  $ad = new Ad1();

  // Load options from db
  $ad->load_options (AD_AD1_OPTIONS);

  //get post published date
  $publish_date = the_date('U','','',false);

  $http_referer = '';
  //get referer
  if(isset($_SERVER['HTTP_REFERER'])) {
      $http_referer = $_SERVER['HTTP_REFERER'];
  }

  ai_widget_draw ($ad, $publish_date, $http_referer, $args);
}

function ai_widget2($args) {

  $ad = new Ad2();

  // Load options from db
  $ad->load_options (AD_AD2_OPTIONS);

  //get post published date
  $publish_date = the_date('U','','',false);

  $http_referer = '';
  //get referer
  if(isset($_SERVER['HTTP_REFERER'])) {
      $http_referer = $_SERVER['HTTP_REFERER'];
  }

  ai_widget_draw ($ad, $publish_date, $http_referer, $args);
}

function ai_widget3($args) {

  $ad = new Ad3();

  // Load options from db
  $ad->load_options (AD_AD3_OPTIONS);

  //get post published date
  $publish_date = the_date('U','','',false);

  $http_referer = '';
  //get referer
  if(isset($_SERVER['HTTP_REFERER'])) {
      $http_referer = $_SERVER['HTTP_REFERER'];
  }

  ai_widget_draw ($ad, $publish_date, $http_referer, $args);
}

function ai_widget4($args) {

  $ad = new Ad4();

  // Load options from db
  $ad->load_options (AD_AD4_OPTIONS);

  //get post published date
  $publish_date = the_date('U','','',false);

  $http_referer = '';
  //get referer
  if(isset($_SERVER['HTTP_REFERER'])) {
      $http_referer = $_SERVER['HTTP_REFERER'];
  }

  ai_widget_draw ($ad, $publish_date, $http_referer, $args);
}

function ai_widget5($args) {

  $ad = new Ad5();

  // Load options from db
  $ad->load_options (AD_AD5_OPTIONS);

  //get post published date
  $publish_date = the_date('U','','',false);

  $http_referer = '';
  //get referer
  if(isset($_SERVER['HTTP_REFERER'])) {
      $http_referer = $_SERVER['HTTP_REFERER'];
  }

  ai_widget_draw ($ad, $publish_date, $http_referer, $args);
}

function ai_widget6($args) {

  $ad = new Ad6($args);

  // Load options from db
  $ad->load_options (AD_AD6_OPTIONS);

  //get post published date
  $publish_date = the_date('U','','',false);

  $http_referer = '';
  //get referer
  if(isset($_SERVER['HTTP_REFERER'])) {
      $http_referer = $_SERVER['HTTP_REFERER'];
  }

  ai_widget_draw ($ad, $publish_date, $http_referer, $args);
}

function ai_widget7($args) {

  $ad = new Ad7();

  // Load options from db
  $ad->load_options (AD_AD7_OPTIONS);

  //get post published date
  $publish_date = the_date('U','','',false);

  $http_referer = '';
  //get referer
  if(isset($_SERVER['HTTP_REFERER'])) {
      $http_referer = $_SERVER['HTTP_REFERER'];
  }

  ai_widget_draw ($ad, $publish_date, $http_referer, $args);
}

function ai_widget8($args) {

  $ad = new Ad8();

  // Load options from db
  $ad->load_options (AD_AD8_OPTIONS);

  //get post published date
  $publish_date = the_date('U','','',false);

  $http_referer = '';
  //get referer
  if(isset($_SERVER['HTTP_REFERER'])) {
      $http_referer = $_SERVER['HTTP_REFERER'];
  }

  ai_widget_draw ($ad, $publish_date, $http_referer, $args);
}

function ai_widget9($args) {

  $ad = new Ad9();

  // Load options from db
  $ad->load_options (AD_AD9_OPTIONS);

  //get post published date
  $publish_date = the_date('U','','',false);

  $http_referer = '';
  //get referer
  if(isset($_SERVER['HTTP_REFERER'])) {
      $http_referer = $_SERVER['HTTP_REFERER'];
  }

  ai_widget_draw ($ad, $publish_date, $http_referer, $args);
}

function ai_widget10($args) {

  $ad = new Ad10();

  // Load options from db
  $ad->load_options (AD_AD10_OPTIONS);

  //get post published date
  $publish_date = the_date('U','','',false);

  $http_referer = '';
  //get referer
  if(isset($_SERVER['HTTP_REFERER'])) {
      $http_referer = $_SERVER['HTTP_REFERER'];
  }

  ai_widget_draw ($ad, $publish_date, $http_referer, $args);
}

function ai_widget11($args) {

  $ad = new Ad11();

  // Load options from db
  $ad->load_options (AD_AD11_OPTIONS);

  //get post published date
  $publish_date = the_date('U','','',false);

  $http_referer = '';
  //get referer
  if(isset($_SERVER['HTTP_REFERER'])) {
      $http_referer = $_SERVER['HTTP_REFERER'];
  }

  ai_widget_draw ($ad, $publish_date, $http_referer, $args);
}

function ai_widget12($args) {

  $ad = new Ad12();

  // Load options from db
  $ad->load_options (AD_AD12_OPTIONS);

  //get post published date
  $publish_date = the_date('U','','',false);

  $http_referer = '';
  //get referer
  if(isset($_SERVER['HTTP_REFERER'])) {
      $http_referer = $_SERVER['HTTP_REFERER'];
  }

  ai_widget_draw ($ad, $publish_date, $http_referer, $args);
}

function ai_widget13($args) {

  $ad = new Ad13();

  // Load options from db
  $ad->load_options (AD_AD13_OPTIONS);

  //get post published date
  $publish_date = the_date('U','','',false);

  $http_referer = '';
  //get referer
  if(isset($_SERVER['HTTP_REFERER'])) {
      $http_referer = $_SERVER['HTTP_REFERER'];
  }

  ai_widget_draw ($ad, $publish_date, $http_referer, $args);
}

function ai_widget14($args) {

  $ad = new Ad14();

  // Load options from db
  $ad->load_options (AD_AD14_OPTIONS);

  //get post published date
  $publish_date = the_date('U','','',false);

  $http_referer = '';
  //get referer
  if(isset($_SERVER['HTTP_REFERER'])) {
      $http_referer = $_SERVER['HTTP_REFERER'];
  }

  ai_widget_draw ($ad, $publish_date, $http_referer, $args);
}

function ai_widget15($args) {

  $ad = new Ad15();

  // Load options from db
  $ad->load_options (AD_AD15_OPTIONS);

  //get post published date
  $publish_date = the_date('U','','',false);

  $http_referer = '';
  //get referer
  if(isset($_SERVER['HTTP_REFERER'])) {
      $http_referer = $_SERVER['HTTP_REFERER'];
  }

  ai_widget_draw ($ad, $publish_date, $http_referer, $args);
}

function ai_widget16($args) {

  $ad = new Ad16();

  // Load options from db
  $ad->load_options (AD_AD16_OPTIONS);

  //get post published date
  $publish_date = the_date('U','','',false);

  $http_referer = '';
  //get referer
  if(isset($_SERVER['HTTP_REFERER'])) {
      $http_referer = $_SERVER['HTTP_REFERER'];
  }

  ai_widget_draw ($ad, $publish_date, $http_referer, $args);
}

function ai_widget_draw ($obj, $publish_date, $http_referer, $args) {

  if ($obj->get_append_type () != AD_SELECT_WIDGET) return;

  //if empty data, continue next
  if($obj->get_ad_data()==AD_EMPTY_DATA){
     return;
  }

  if(is_home()){
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
    echo $args['before_widget'] . "<div style='" . $obj->get_alignmet_style(false) . "'>" . ai_getAdCode ($obj) . "</div>" . $args['after_widget'];
}
