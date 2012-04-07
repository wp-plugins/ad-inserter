<?php

/*
Plugin Name: Ad Inserter
Version: 1.1.3
Description: An elegant solution to put any ad into Wordpress. Simply enter any HTML code and select where and how you want to display it (including Widgets). You can also use {category}, {short_category}, {title}, {short_title}, {tag}, {smart_tag} or {search_query} for actual post data. To rotate different ad versions separate them with ||. Manual insertion is also possible with {adinserter AD_NAME} tag.
Author: Igor Funa
Author URI: http://igorfuna.com/
Plugin URI: http://igorfuna.com/software/web/ad-inserter-wordpress-plugin
*/

/*
Inspired by the Adsense Daemon plugin by Yong Mook Kim
http://www.mkyong.com/blog/adsense-daemon-wordpress-plugin
*/

/*
TO DO
- Above and below title
*/

/*
Change Log

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

/*
ini_set('display_errors',1);
error_reporting(E_ALL);
*/

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
add_action('admin_menu', 'ai_admin_menu');
add_filter('the_content', 'ai_content_hook', 99999);
add_action('init', 'AdInserter_Init');

function AdInserter_Init() {

  $ad1 = new Ad1();
  $ad2 = new Ad2();
  $ad3 = new Ad3();
  $ad4 = new Ad4();
  $ad5 = new Ad5();
  $ad6 = new Ad6();
  $ad7 = new Ad7();
  $ad8 = new Ad8();

  //load options from db
  $ad1->wp_options = ai_get_option(AD_AD1_OPTIONS);
  $ad2->wp_options = ai_get_option(AD_AD2_OPTIONS);
  $ad3->wp_options = ai_get_option(AD_AD3_OPTIONS);
  $ad4->wp_options = ai_get_option(AD_AD4_OPTIONS);
  $ad5->wp_options = ai_get_option(AD_AD5_OPTIONS);
  $ad6->wp_options = ai_get_option(AD_AD6_OPTIONS);
  $ad7->wp_options = ai_get_option(AD_AD7_OPTIONS);
  $ad8->wp_options = ai_get_option(AD_AD8_OPTIONS);

  $ad_all_data = array($ad1,$ad2,$ad3,$ad4,$ad5,$ad6,$ad7,$ad8);

  $ad_counter = 0;
  foreach($ad_all_data as $obj){
     $ad_counter ++;

     if($obj->get_append_type() == AD_SELECT_WIDGET){

       // register widget
       $widget_options = array ('classname' => 'ai_widget', 'description' => "Put any ad or HTML code into the Sidebar." );
       wp_register_sidebar_widget ('ai_widget'.$ad_counter, $obj->get_ad_name(), 'ai_widget'.$ad_counter, $widget_options);

     }
  }
}

function ai_DataInitializer($type=AD_FUNC_TYPE_RESET){

   $ad1 = new Ad1();
   $ad2 = new Ad2();
   $ad3 = new Ad3();
   $ad4 = new Ad4();
   $ad5 = new Ad5();
   $ad6 = new Ad6();
   $ad7 = new Ad7();
   $ad8 = new Ad8();

   if(get_option(AD_AD1_OPTIONS)==AD_EMPTY_VALUE || $type==AD_FUNC_TYPE_RESET)
      update_option(AD_AD1_OPTIONS, $ad1->wp_options);

   if(get_option(AD_AD2_OPTIONS)==AD_EMPTY_VALUE || $type==AD_FUNC_TYPE_RESET)
      update_option(AD_AD2_OPTIONS, $ad2->wp_options);

   if(get_option(AD_AD3_OPTIONS)==AD_EMPTY_VALUE || $type==AD_FUNC_TYPE_RESET)
      update_option(AD_AD3_OPTIONS, $ad3->wp_options);

   if(get_option(AD_AD4_OPTIONS)==AD_EMPTY_VALUE || $type==AD_FUNC_TYPE_RESET)
      update_option(AD_AD4_OPTIONS, $ad4->wp_options);

   if(get_option(AD_AD5_OPTIONS)==AD_EMPTY_VALUE || $type==AD_FUNC_TYPE_RESET)
      update_option(AD_AD5_OPTIONS, $ad5->wp_options);

   if(get_option(AD_AD6_OPTIONS)==AD_EMPTY_VALUE || $type==AD_FUNC_TYPE_RESET)
      update_option(AD_AD6_OPTIONS, $ad6->wp_options);

   if(get_option(AD_AD7_OPTIONS)==AD_EMPTY_VALUE || $type==AD_FUNC_TYPE_RESET)
      update_option(AD_AD7_OPTIONS, $ad7->wp_options);

   if(get_option(AD_AD8_OPTIONS)==AD_EMPTY_VALUE || $type==AD_FUNC_TYPE_RESET)
      update_option(AD_AD8_OPTIONS, $ad8->wp_options);

}

//reset all the value
function ai_resetIt() {
   ai_DataInitializer (AD_FUNC_TYPE_RESET);
}

//initial the unset value
function ai_initializeIt() {
   ai_DataInitializer (AD_FUNC_TYPE_INITIAL);
}

function ai_admin_menu() {
   add_submenu_page('options-general.php', 'Ad Inserter Options', 'Ad Inserter', 8, basename(__FILE__), 'ai_menu');
}

function filter_characters($str){

  $str = str_replace("\\\"", "\"", $str);
  return $str;
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

function ai_menu(){

  ai_initializeIt();

  $ad1 = new Ad1();
  $ad2 = new Ad2();
  $ad3 = new Ad3();
  $ad4 = new Ad4();
  $ad5 = new Ad5();
  $ad6 = new Ad6();
  $ad7 = new Ad7();
  $ad8 = new Ad8();

  if (isset($_POST[AD_FORM_SAVE])) {

     foreach(array_keys($ad1->wp_options) as $key){

        if(isset($_POST[$key])){
            $ad1->wp_options[$key] = filter_characters($_POST[$key]);
        }

      }

      foreach(array_keys($ad2->wp_options) as $key){

      if(isset($_POST[$key])){
            $ad2->wp_options[$key] = filter_characters($_POST[$key]);
        }

      }

      foreach(array_keys($ad3->wp_options) as $key){

      if(isset($_POST[$key])){
            $ad3->wp_options[$key] = filter_characters($_POST[$key]);
        }

      }

      foreach(array_keys($ad4->wp_options) as $key){

      if(isset($_POST[$key])){
            $ad4->wp_options[$key] = filter_characters($_POST[$key]);
        }

      }

     foreach(array_keys($ad5->wp_options) as $key){

        if(isset($_POST[$key])){
            $ad5->wp_options[$key] = filter_characters($_POST[$key]);
        }

      }

      foreach(array_keys($ad6->wp_options) as $key){

      if(isset($_POST[$key])){
            $ad6->wp_options[$key] = filter_characters($_POST[$key]);
        }

      }

      foreach(array_keys($ad7->wp_options) as $key){

      if(isset($_POST[$key])){
            $ad7->wp_options[$key] = filter_characters($_POST[$key]);
        }

      }

      foreach(array_keys($ad8->wp_options) as $key){

      if(isset($_POST[$key])){
            $ad8->wp_options[$key] = filter_characters($_POST[$key]);
        }

      }

      update_option(AD_AD1_OPTIONS, $ad1->wp_options);
      update_option(AD_AD2_OPTIONS, $ad2->wp_options);
      update_option(AD_AD3_OPTIONS, $ad3->wp_options);
      update_option(AD_AD4_OPTIONS, $ad4->wp_options);
      update_option(AD_AD5_OPTIONS, $ad5->wp_options);
      update_option(AD_AD6_OPTIONS, $ad6->wp_options);
      update_option(AD_AD7_OPTIONS, $ad7->wp_options);
      update_option(AD_AD8_OPTIONS, $ad8->wp_options);

    echo "<div id='message' class='updated fade'><strong><p>Updated Successful.</p></strong></div>";


    } else if(isset($_POST[AD_FORM_CLEAR])){
        ai_resetIt();
        echo "<div id='message' class='error fade'><p>Settings Cleared.</p></div>";
    }

    //load options from db
   $ad1->wp_options = ai_get_option(AD_AD1_OPTIONS);
   $ad2->wp_options = ai_get_option(AD_AD2_OPTIONS);
   $ad3->wp_options = ai_get_option(AD_AD3_OPTIONS);
   $ad4->wp_options = ai_get_option(AD_AD4_OPTIONS);
   $ad5->wp_options = ai_get_option(AD_AD5_OPTIONS);
   $ad6->wp_options = ai_get_option(AD_AD6_OPTIONS);
   $ad7->wp_options = ai_get_option(AD_AD7_OPTIONS);
   $ad8->wp_options = ai_get_option(AD_AD8_OPTIONS);

   $ad_array = array($ad1,$ad2,$ad3,$ad4,$ad5,$ad6,$ad7,$ad8);

   print_settings_form($ad_array);

}


function ai_content_hook($content = ''){

   if(!is_single())
     return $content;

   $ad1 = new Ad1();
   $ad2 = new Ad2();
   $ad3 = new Ad3();
   $ad4 = new Ad4();
   $ad5 = new Ad5();
   $ad6 = new Ad6();
   $ad7 = new Ad7();
   $ad8 = new Ad8();

    //load options from db
   $ad1->wp_options = ai_get_option(AD_AD1_OPTIONS);
   $ad2->wp_options = ai_get_option(AD_AD2_OPTIONS);
   $ad3->wp_options = ai_get_option(AD_AD3_OPTIONS);
   $ad4->wp_options = ai_get_option(AD_AD4_OPTIONS);
   $ad5->wp_options = ai_get_option(AD_AD5_OPTIONS);
   $ad6->wp_options = ai_get_option(AD_AD6_OPTIONS);
   $ad7->wp_options = ai_get_option(AD_AD7_OPTIONS);
   $ad8->wp_options = ai_get_option(AD_AD8_OPTIONS);

   $ad_all_data = array($ad1,$ad2,$ad3,$ad4,$ad5,$ad6,$ad7,$ad8);

  //get post published date
  $publish_date = the_date('U','','',false);

  $http_referer = '';
  //get referer
  if(isset($_SERVER['HTTP_REFERER'])) {
      $http_referer = $_SERVER['HTTP_REFERER'];
  }

   $content = generateAdInserterDiv($content, $ad_all_data, $publish_date, $http_referer);

   return $content;
}

function ai_isCategoryAllowed($categories, $cat_type){

  $categories = trim(strtolower($categories));

  //echo ' listed categories: ' . $categories;

  if ($cat_type == AD_CATEGORY_BLACK_LIST) {

    if($categories == AD_EMPTY_DATA){
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

function ai_isDisplayAllowed($obj, $content){

  if (preg_match("/" . $obj->get_ad_disable() . "/i", $content)) {
      return false;
  } else {
      return true;
  }

}

function ai_isDisplayDateAllowed($obj, $publish_date){

  $after_days = trim ($obj->get_ad_after_day());

  //if 0, display immediately
  if($after_days == AD_ZERO_DATA || $after_days == AD_EMPTY_DATA){
    return true;
  }

  return (date ('U', time ()) >= $publish_date + $after_days * 86400);
}


function ai_isRefererAllowed($obj, $http_referer){

  $websites = explode(",",$obj->get_ad_block_user());

  $referer_allow=true;

  foreach($websites as $referer){

    $referer = trim($referer);

    //avoid empty value
    if($referer==AD_EMPTY_DATA){
      continue;
    }

    if (preg_match("/" . $referer . "/i", $http_referer)) {
        $referer_allow = false;
    }else{
    }

  }

  return $referer_allow;

}

function generateAdInserterDiv($content, $ad_all_data, $publish_date, $http_referer){

  foreach($ad_all_data as $obj){

    //if empty data, continue next
    if($obj->get_ad_data()==AD_EMPTY_DATA){
      continue;
    }

      if(ai_isDisplayAllowed($obj, $content)==false){
      continue;
    }

      if(ai_isCategoryAllowed($obj->get_ad_block_cat(), $obj->get_ad_block_cat_type())==false){
      continue;
    }

      if(ai_isDisplayDateAllowed($obj, $publish_date)==false){
      continue;
    }

      if(ai_isRefererAllowed($obj, $http_referer)==false){
      continue;
    }

    if($obj->get_append_type() == AD_SELECT_BEFORE_PARAGRAPH){

       $content = ai_generateBeforeParagraph($content, $obj);

    }elseif($obj->get_append_type() == AD_SELECT_BEFORE_CONTENT){

       $content = ai_generateDivBefore($content, $obj);

    }elseif($obj->get_append_type() == AD_SELECT_AFTER_CONTENT){

       $content = ai_generateDivAfter($content, $obj);

    }elseif($obj->get_append_type() == AD_SELECT_MANUAL){

       $content = ai_generateDivManual($content, $obj);

    }
  }

   // Clean remaining tags
   $content = preg_replace ("/{adinserter (.*)}/", "", $content);

   $content .= AD_AUTHOR_SITE;

  return $content;
}


function ai_getAdCode ($obj){
  $ad_code = $obj->get_ad_data_replaced();
  if (strpos ($ad_code, "||") !== false) {
    $ads = explode ("||", $ad_code);
    $ad_code = $ads [rand (0, sizeof ($ads) - 1)];
  }
  return $ad_code;
}


function ai_generateBeforeParagraph($content, $obj){

   if ($obj->get_float_type() == AD_ALIGNMENT_LEFT)
   {
      $style = "text-align:left;padding:8px 0px;";
   }
   elseif ($obj->get_float_type() == AD_ALIGNMENT_RIGHT)
   {
      $style = "text-align:right;padding:8px 0px;";
   }
   elseif ($obj->get_float_type() == AD_ALIGNMENT_CENTER)
   {
      $style = "text-align:center;margin-left:auto;margin-right:auto;padding:8px 0px;";
   }
   elseif ($obj->get_float_type() == AD_ALIGNMENT_FLOAT_LEFT)
   {
      $style = "float:left;padding:8px 8px 8px 0px;";
   }
   elseif ($obj->get_float_type() == AD_ALIGNMENT_FLOAT_RIGHT)
   {
      $style = "float:right;padding:8px 0px 8px 8px;";
   }
   else $style = "padding:8px 0px;";

   $poses = array();
    $poseslast = array();
   $lastpos = -1;

   $findchar = "<p>";
   if(strpos($content, "<p") === false)
     $repchar = "<br";

   while(strpos($content, $findchar, $lastpos+1) !== false){
     $lastpos = strpos($content, $findchar, $lastpos+1);
     $poses[] = $lastpos;
   }

   $para = $obj->get_paragraph_number();
   if ($para <= 0)
   {
     $para = rand (0, sizeof ($poses) - 1);
   }
   elseif ($obj->get_direction_type() == AD_DIRECTION_FROM_BOTTOM)
   {
     $poses = array_reverse ($poses);
     $para = $para - 1;
   } else $para --;


   if(sizeof($poses)>$para)
   {

      $pickme = $poses[$para];

      $content = substr_replace($content, "<div style='" . $style . "'>" . ai_getAdCode ($obj) . "</div>", $pickme, 0);

      //reset it
      $lastpos = -1;
   }

   return $content;

}

function ai_generateDivBefore($content, $obj){

   return "<div style='padding-top:8px; padding-bottom:8px'>" . ai_getAdCode ($obj) . "</div>" . $content;

}

function ai_generateDivAfter($content, $obj){

   return $content . "<div style='padding-top:8px; padding-bottom:8px'>" . ai_getAdCode ($obj) . "</div>";

}

function ai_generateDivManual($content, $obj){

   if (preg_match_all("/{adinserter (.+?)}/", $content, $tags)){
     foreach ($tags [1] as $tag) {
        $ad_tag = strtolower (trim ($tag));
        $ad_name = strtolower (trim ($obj->get_ad_name()));
        if ($ad_tag == $ad_name) {
          $content = preg_replace ("/{adinserter " . $tag . "}/", ai_getAdCode ($obj), $content);
        }
     }
   }

   return $content;
}

function ai_widget1($args) {

  $ad = new Ad1();

  //load options from db
  $ad->wp_options = ai_get_option (AD_AD1_OPTIONS);

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

  //load options from db
  $ad->wp_options = ai_get_option (AD_AD2_OPTIONS);

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

  //load options from db
  $ad->wp_options = ai_get_option (AD_AD3_OPTIONS);

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

  //load options from db
  $ad->wp_options = ai_get_option (AD_AD4_OPTIONS);

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

  //load options from db
  $ad->wp_options = ai_get_option (AD_AD5_OPTIONS);

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

  //load options from db
  $ad->wp_options = ai_get_option (AD_AD6_OPTIONS);

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

  //load options from db
  $ad->wp_options = ai_get_option (AD_AD7_OPTIONS);

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

  //load options from db
  $ad->wp_options = ai_get_option (AD_AD8_OPTIONS);

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

     //if empty data, continue next
     if($obj->get_ad_data()==AD_EMPTY_DATA){
        return;
     }

     if(ai_isDisplayAllowed($obj, $content)==false){
        return;
     }

     if(ai_isCategoryAllowed($obj->get_ad_block_cat(), $obj->get_ad_block_cat_type())==false){
        return;
     }

     if(ai_isDisplayDateAllowed($obj, $publish_date)==false){
        return;
     }

     if(ai_isRefererAllowed($obj, $http_referer)==false){
        return;
     }

     if($obj->get_append_type() == AD_SELECT_WIDGET){

       echo $args['before_widget'] . ai_getAdCode ($obj) . $args['after_widget'];

     }
}

?>
