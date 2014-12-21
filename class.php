<?php

require_once 'constants.php';

abstract class BaseCodeBlock {
    var $wp_options;

    var $option_ad_data;
    var $option_process_php;
    var $option_enable_manual;

    function BaseCodeBlock() {
      $this->wp_options[$this->option_ad_data]          = AD_EMPTY_DATA;
      $this->wp_options[$this->option_process_php]      = AD_SETTINGS_NOT_CHECKED;
      $this->wp_options[$this->option_enable_manual]    = AD_SETTINGS_NOT_CHECKED;
    }

   public function load_options ($options_name){
     $options = ai_get_option ($options_name);
     if ($options != '') $this->wp_options = $options;
    }

   public function get_ad_data(){
     $ad_data = $this->wp_options [$this->option_ad_data];
     return $ad_data;
   }

   public function get_enable_manual (){
     $enable_manual = $this->wp_options [$this->option_enable_manual];
     if ($enable_manual == '') $enable_manual = AD_SETTINGS_NOT_CHECKED;
     return $enable_manual;
   }

   public function get_process_php (){
     $process_php = $this->wp_options [$this->option_process_php];
     if ($process_php == '') $process_php = AD_SETTINGS_NOT_CHECKED;
     return $process_php;
   }
}

abstract class CodeBlock extends BaseCodeBlock {

    // define properties
    var $number;

    var $option_append_type;
    var $option_paragraph_number;
    var $option_paragraph_number_minimum;
    var $option_excerpt_number;
    var $option_direction_type;
    var $option_float_type;
    var $option_ad_general_tag;
    var $option_ad_name;
    var $option_ad_after_day;
    var $option_ad_domain_list;
    var $option_ad_domain_list_type;
    var $option_ad_category_list;
    var $option_ad_category_list_type;
    var $option_widget_settings_home;
    var $option_widget_settings_page;
    var $option_widget_settings_post;
    var $option_widget_settings_category;
    var $option_widget_settings_search;
    var $option_widget_settings_archive;
    var $option_enable_php_call;
    var $option_paragraph_text;
    var $option_custom_css;
    var $option_display_for_users;

    function CodeBlock() {

      $this->number = 0;

      $this->wp_options = array ();

      $this->wp_options[$this->option_ad_name]                  = AD_NAME;
      $this->wp_options[$this->option_append_type]              = AD_SELECT_NONE;
      $this->wp_options[$this->option_paragraph_number]         = AD_ZERO_DATA;
      $this->wp_options[$this->option_paragraph_number_minimum] = AD_ZERO_DATA;
      $this->wp_options[$this->option_excerpt_number]           = AD_ZERO_DATA;
      $this->wp_options[$this->option_direction_type]           = AD_DIRECTION_FROM_TOP;
      $this->wp_options[$this->option_float_type]               = AD_ALIGNMENT_NONE;
      $this->wp_options[$this->option_ad_general_tag]           = AD_GENERAL_TAG;
      $this->wp_options[$this->option_ad_after_day]             = AD_ZERO_DATA;
      $this->wp_options[$this->option_ad_domain_list]           = AD_EMPTY_DATA;
      $this->wp_options[$this->option_ad_domain_list_type]      = AD_BLACK_LIST;
      $this->wp_options[$this->option_ad_category_list]         = AD_EMPTY_DATA;
      $this->wp_options[$this->option_ad_category_list_type]    = AD_BLACK_LIST;
      $this->wp_options[$this->option_widget_settings_home]     = AD_SETTINGS_CHECKED;
      $this->wp_options[$this->option_widget_settings_page]     = AD_SETTINGS_NOT_CHECKED;
      $this->wp_options[$this->option_widget_settings_post]     = AD_SETTINGS_CHECKED;
      $this->wp_options[$this->option_widget_settings_category] = AD_SETTINGS_CHECKED;
      $this->wp_options[$this->option_widget_settings_search]   = AD_SETTINGS_CHECKED;
      $this->wp_options[$this->option_widget_settings_archive]  = AD_SETTINGS_CHECKED;
      $this->wp_options[$this->option_enable_php_call]          = AD_SETTINGS_NOT_CHECKED;
      $this->wp_options[$this->option_paragraph_text]           = AD_EMPTY_DATA;
      $this->wp_options[$this->option_custom_css]               = AD_EMPTY_DATA;
      $this->wp_options[$this->option_display_for_users]        = AD_DISPLAY_ALL_USERS;

      parent::BaseCodeBlock();
    }

   public function get_append_type(){
     $option = $this->wp_options [$this->option_append_type];
     if ($option == '') $option = AD_SELECT_NONE;
     elseif ($option == AD_SELECT_MANUAL) $option = AD_SELECT_NONE;
     return $option;
    }

   public function get_paragraph_number(){
     $option = $this->wp_options [$this->option_paragraph_number];
     if ($option == '') $option = AD_ZERO_DATA;
     return $option;
    }

   public function get_paragraph_number_minimum(){
     $option = $this->wp_options [$this->option_paragraph_number_minimum];
     if ($option == '') $option = AD_ZERO_DATA;
     return $option;
    }

   public function get_excerpt_number(){
     $option = $this->wp_options [$this->option_excerpt_number];
     if ($option == '') $option = AD_ZERO_DATA;
     return $option;
    }

   public function get_direction_type(){
     $option = $this->wp_options [$this->option_direction_type];
     if ($option == '') $option = AD_DIRECTION_FROM_TOP;
     return $option;
    }

   public function get_alignment_type(){
     // Update old field names
     $alignment = $this->wp_options [$this->option_float_type];
     if($alignment == 'Left'){
       $alignment = AD_ALIGNMENT_FLOAT_LEFT;
       $this->wp_options [$this->option_float_type] = $alignment;
     } else
     if($alignment == 'Right'){
       $alignment = AD_ALIGNMENT_FLOAT_RIGHT;
       $this->wp_options [$this->option_float_type] = $alignment;
     }
     if ($alignment == '') $option = AD_ALIGNMENT_NONE;
     return $this->wp_options [$this->option_float_type];
    }

   public function get_alignmet_style($margin = true){
    if ($this->get_alignment_type() == AD_ALIGNMENT_LEFT) {
      if ($margin)
        $style = "text-align:left;margin:8px 0px;"; else
          $style = "text-align:left;";
    }
    elseif ($this->get_alignment_type() == AD_ALIGNMENT_RIGHT) {
      if ($margin)
        $style = "text-align:right;margin:8px 0px;"; else
          $style = "text-align:right;";
    }
    elseif ($this->get_alignment_type() == AD_ALIGNMENT_CENTER) {
      if ($margin)
        $style = "text-align:center;margin-left:auto;margin-right:auto;margin-top:8px;margin-bottom:8px;"; else
          $style = "text-align:center;margin-left:auto;margin-right:auto;";
    }
    elseif ($this->get_alignment_type() == AD_ALIGNMENT_FLOAT_LEFT) {
      if ($margin)
        $style = "float:left;margin:8px 8px 8px 0px;"; else
          $style = "float:left;";
    }
    elseif ($this->get_alignment_type() == AD_ALIGNMENT_FLOAT_RIGHT) {
      if ($margin)
        $style = "float:right;margin:8px 0px 8px 8px;"; else
          $style = "float:right;";
    }
    elseif ($this->get_alignment_type() == AD_ALIGNMENT_CUSTOM_CSS) {
      $style = $this->get_custom_css ();
    }
    else {
      if ($margin)
        $style = "margin:8px 0px;"; else
          $style = "margin:0;";
    }

    return $style;
   }

   public function get_display_settings_home(){
     $widget_settings = $this->wp_options[$this->option_widget_settings_home];
     if ($widget_settings == '') $widget_settings = AD_SETTINGS_CHECKED;
     return $widget_settings;
   }

   public function get_display_settings_page(){
     $widget_settings = $this->wp_options[$this->option_widget_settings_page];
     if ($widget_settings == '') $widget_settings = AD_SETTINGS_NOT_CHECKED;
     return $widget_settings;
   }

   public function get_display_settings_post(){
     $widget_settings = $this->wp_options[$this->option_widget_settings_post];
     if ($widget_settings == '') $widget_settings = AD_SETTINGS_CHECKED;
     return $widget_settings;
   }

   public function get_display_settings_category(){
     $widget_settings = $this->wp_options[$this->option_widget_settings_category];
     if ($widget_settings == '') $widget_settings = AD_SETTINGS_CHECKED;
     return $widget_settings;
   }

   public function get_display_settings_search(){
     $widget_settings = $this->wp_options[$this->option_widget_settings_search];
     if ($widget_settings == '') $widget_settings = AD_SETTINGS_CHECKED;
     return $widget_settings;
   }

   public function get_display_settings_archive(){
     $widget_settings = $this->wp_options[$this->option_widget_settings_archive];
     if ($widget_settings == '') $widget_settings = AD_SETTINGS_CHECKED;
     return $widget_settings;
   }

   public function get_enable_manual (){
     $enable_manual = $this->wp_options [$this->option_enable_manual];
     if ($enable_manual == '') {
       $display_option = $this->wp_options [$this->option_append_type];
       if ($display_option == AD_SELECT_MANUAL)
         $enable_manual = AD_SETTINGS_CHECKED; else
           $enable_manual = AD_SETTINGS_NOT_CHECKED;
     }
     return $enable_manual;
   }

   public function get_enable_php_call (){
     $enable_php_call = $this->wp_options [$this->option_enable_php_call];
     if ($enable_php_call == '') $enable_php_call = AD_SETTINGS_NOT_CHECKED;
     return $enable_php_call;
   }

   public function get_paragraph_text (){
     return $this->wp_options [$this->option_paragraph_text];
   }

   public function get_custom_css (){
     return $this->wp_options [$this->option_custom_css];
   }

   public function get_display_for_users (){
     if (isset ($this->wp_options [$this->option_display_for_users])) {
      $display_for_users = $this->wp_options [$this->option_display_for_users];
     } else $display_for_users = '';
     if ($display_for_users == '') $display_for_users = AD_DISPLAY_ALL_USERS;
     return $display_for_users;
   }

   public function get_ad_data_replaced(){

     $general_tag = $this->wp_options[$this->option_ad_general_tag];
     $general_tag = str_replace ("&amp;", " and ", $general_tag);
     $title = $general_tag;
     $short_title = $general_tag;
     $category = $general_tag;
     $short_category = $general_tag;
     $tag = $general_tag;
     $smart_tag = $general_tag;
     if (is_category ()) {
         $categories = get_the_category();
         if (!empty ($categories)) {
           $first_category = reset ($categories);
           $category = str_replace ("&amp;", "and", $first_category->name);
           if ($category == "Uncategorized") $category = $general_tag;
         } else {
             $category = $general_tag;
         }
         if (strpos ($category, ",") !== false) {
           $short_category = trim (substr ($category, 0, strpos ($category, ",")));
         } else $short_category = $category;
         if (strpos ($short_category, "and") !== false) {
           $short_category = trim (substr ($short_category, 0, strpos ($short_category, "and")));
         }

         $title = $category;
         $title = str_replace ("&amp;", "and", $title);
         $short_title = implode (" ", array_slice (explode (" ", $title), 0, 3));
         $tag = $short_title;
         $smart_tag = $short_title;
     } elseif (is_tag ()) {
         $title = single_tag_title('', false);
         $title = str_replace ("&amp;", "and", $title);
         $short_title = implode (" ", array_slice (explode (" ", $title), 0, 3));
         $category = $short_title;
         if (strpos ($category, ",") !== false) {
           $short_category = trim (substr ($category, 0, strpos ($category, ",")));
         } else $short_category = $category;
         if (strpos ($short_category, "and") !== false) {
           $short_category = trim (substr ($short_category, 0, strpos ($short_category, "and")));
         }
         $tag = $short_title;
         $smart_tag = $short_title;
     } elseif (is_search ()) {
         $title = get_search_query();
         $title = str_replace ("&amp;", "and", $title);
         $short_title = implode (" ", array_slice (explode (" ", $title), 0, 3));
         $category = $short_title;
         if (strpos ($category, ",") !== false) {
           $short_category = trim (substr ($category, 0, strpos ($category, ",")));
         } else $short_category = $category;
         if (strpos ($short_category, "and") !== false) {
           $short_category = trim (substr ($short_category, 0, strpos ($short_category, "and")));
         }
         $tag = $short_title;
         $smart_tag = $short_title;
     } elseif (is_page () || is_single ()) {
         $title = get_the_title();
         $title = str_replace ("&amp;", "and", $title);

         $short_title = implode (" ", array_slice (explode (" ", $title), 0, 3));

         $categories = get_the_category();
         if (!empty ($categories)) {
           $first_category = reset ($categories);
           $category = str_replace ("&amp;", "and", $first_category->name);
           if ($category == "Uncategorized") $category = $general_tag;
         } else {
             $category = $short_title;
         }
         if (strpos ($category, ",") !== false) {
           $short_category = trim (substr ($category, 0, strpos ($category, ",")));
         } else $short_category = $category;
         if (strpos ($short_category, "and") !== false) {
           $short_category = trim (substr ($short_category, 0, strpos ($short_category, "and")));
         }

         $tags = get_the_tags();
         if (!empty ($tags)) {

           $first_tag = reset ($tags);
           $tag = $first_tag->name;

           $tag_array = array ();
           foreach ($tags as $tag_data) {
             $tag_array [] = explode (" ", $tag_data->name);
           }

           $selected_tag = '';

           if (count ($tag_array [0]) == 2) $selected_tag = $tag_array [0];
           elseif (count ($tag_array) > 1 && count ($tag_array [1]) == 2) $selected_tag = $tag_array [1];
           elseif (count ($tag_array) > 2 && count ($tag_array [2]) == 2) $selected_tag = $tag_array [2];
           elseif (count ($tag_array) > 3 && count ($tag_array [3]) == 2) $selected_tag = $tag_array [3];
           elseif (count ($tag_array) > 4 && count ($tag_array [4]) == 2) $selected_tag = $tag_array [4];


           if ($selected_tag == '' && count ($tag_array) >= 2 && count ($tag_array [0]) == 1 && count ($tag_array [1]) == 1) {

             if (strpos ($tag_array [0][0], $tag_array [1][0]) !== false) $tag_array = array_slice ($tag_array, 1, count ($tag_array) - 1);
             if (strpos ($tag_array [1][0], $tag_array [0][0]) !== false) $tag_array = array_slice ($tag_array, 1, count ($tag_array) - 1);

             if (count ($tag_array) >= 2 && count ($tag_array [0]) == 1 && count ($tag_array [1]) == 1) {
               $selected_tag = array ($tag_array [0][0], $tag_array [1][0]);
             }
           }

           if ($selected_tag == '') {
             $first_tag = reset ($tags);
             $smart_tag = implode (" ", array_slice (explode (" ", $first_tag->name), 0, 3));
           } else $smart_tag = implode (" ", $selected_tag);

           $smart_tag = str_replace ("&amp;", "and", $smart_tag);

         } else {
             $tag = $category;
             $smart_tag = $category;
         }
     }

     $title = str_replace (array ("'", '"'), array ("&#8217;", "&#8221;"), $title);
     $title = html_entity_decode ($title, ENT_QUOTES, "utf-8");

     $short_title = str_replace (array ("'", '"'), array ("&#8217;", "&#8221;"), $short_title);
     $short_title = html_entity_decode ($short_title, ENT_QUOTES, "utf-8");

     $search_query = "";
     if (isset ($_SERVER['HTTP_REFERER'])) {
       $referrer = $_SERVER['HTTP_REFERER'];
     } else $referrer = '';
     if (preg_match("/[\.\/](google|yahoo|bing|ask)\.[a-z\.]{2,5}[\/]/i",$referrer,$search_engine)){
        $referrer_query = parse_url($referrer);
        $referrer_query = $referrer_query["query"];
        parse_str ($referrer_query, $value);
        $search_query = $value ["q"];
        if ($search_query == "") {
          $search_query = $value ["p"];
        }
     }
     if ($search_query == "") $search_query = $smart_tag;

     $ad_data = preg_replace ("/{title}/i",          $title,          parent::get_ad_data());
     $ad_data = preg_replace ("/{short_title}/i",    $short_title,    $ad_data);
     $ad_data = preg_replace ("/{category}/i",       $category,       $ad_data);
     $ad_data = preg_replace ("/{short_category}/i", $short_category, $ad_data);
     $ad_data = preg_replace ("/{tag}/i",            $tag,            $ad_data);
     $ad_data = preg_replace ("/{smart_tag}/i",      $smart_tag,      $ad_data);
     $ad_data = preg_replace ("/{search_query}/i",   $search_query,   $ad_data);

     return $ad_data;
   }

   public function get_ad_general_tag(){
     $option = $this->wp_options [$this->option_ad_general_tag];
     if ($option == '') $option = AD_GENERAL_TAG;
     return $option;
   }

	public function get_ad_after_day(){
     $option = $this->wp_options [$this->option_ad_after_day];
     if ($option == '') $option = AD_ZERO_DATA;
     return $option;
   }

  public function get_ad_domain_list(){
      return $this->wp_options [$this->option_ad_domain_list];
   }

  public function get_ad_domain_list_type (){
     $option = $this->wp_options [$this->option_ad_domain_list_type];
     if ($option == '') $option = AD_BLACK_LIST;
     return $option;
   }

	public function get_ad_name(){
     $option = $this->wp_options [$this->option_ad_name];
     if ($option == '') $option = AD_NAME. " " . $this->number;
     return $option;
   }

	public function get_ad_block_cat(){
      return $this->wp_options[$this->option_ad_category_list];
   }

  public function get_ad_block_cat_type(){
     // Update old data
     if($this->wp_options [$this->option_ad_category_list_type] == ''){
       $this->wp_options [$this->option_ad_category_list_type] = AD_BLACK_LIST;
     }

     $option = $this->wp_options [$this->option_ad_category_list_type];
     if ($option == '') $option = AD_BLACK_LIST;
     return $option;
   }
}

class Block extends CodeBlock{

   const OPTION_AD_NAME                   = "ad#_name";
   const OPTION_APPEND_TYPE               = "ad#_displayType";
   const OPTION_PARAGRAPH_NUMBER          = "ad#_paragraphNumber";
   const OPTION_PARAGRAPH_NUMBER_MINIMUM  = "ad#_minimum_paragraphs";
   const OPTION_EXCERPT_NUMBER            = "ad#_excerptNumber";
   const OPTION_DIRECTION_TYPE            = "ad#_directionType";
   const OPTION_FLOAT_TYPE                = "ad#_floatType";
   const OPTION_AD_DATA                   = "ad#_data";
   const OPTION_AD_GENERAL_TAG            = "ad#_general_tag";
   const OPTION_AD_AFTER_DAY              = "ad#_after_day";
   const OPTION_AD_DOMAIN_LIST            = "ad#_block_user";
   const OPTION_AD_DOMAIN_LIST_TYPE       = "ad#_domain_list_type";
   const OPTION_AD_CATEGORY_LIST          = "ad#_block_cat";
   const OPTION_AD_CATEGORY_LIST_TYPE     = "ad#_block_cat_type";
   const OPTION_WIDGET_SETTINGS_HOME      = "ad#_widget_settings_home";
   const OPTION_WIDGET_SETTINGS_PAGE      = "ad#_widget_settings_page";
   const OPTION_WIDGET_SETTINGS_POST      = "ad#_widget_settings_post";
   const OPTION_WIDGET_SETTINGS_CATEGORY  = "ad#_widget_settings_category";
   const OPTION_WIDGET_SETTINGS_SEARCH    = "ad#_widget_settings_search";
   const OPTION_WIDGET_SETTINGS_ARCHIVE   = "ad#_widget_settings_archive";
   const OPTION_PROCESS_PHP               = "ad#_process_php";
   const OPTION_ENABLE_MANUAL             = "ad#_enable_manual";
   const OPTION_ENABLE_PHP_CALL           = "ad#_enable_php_call";
   const OPTION_PARAGRAPH_TEXT            = "ad#_paragraph_text";
   const OPTION_CUSTOM_CSS                = "ad#_custom_css";
   const OPTION_DISPLAY_FOR_USERS         = "ad#_display_for_users";

	//constructor
    public function Block ($number) {

      $this->option_ad_name                   = str_replace ("#", $number, self::OPTION_AD_NAME);
      $this->option_append_type               = str_replace ("#", $number, self::OPTION_APPEND_TYPE);
      $this->option_paragraph_number          = str_replace ("#", $number, self::OPTION_PARAGRAPH_NUMBER);
      $this->option_paragraph_number_minimum  = str_replace ("#", $number, self::OPTION_PARAGRAPH_NUMBER_MINIMUM);
      $this->option_excerpt_number            = str_replace ("#", $number, self::OPTION_EXCERPT_NUMBER);
      $this->option_direction_type            = str_replace ("#", $number, self::OPTION_DIRECTION_TYPE);
      $this->option_float_type                = str_replace ("#", $number, self::OPTION_FLOAT_TYPE);
      $this->option_ad_data                   = str_replace ("#", $number, self::OPTION_AD_DATA);
      $this->option_ad_general_tag            = str_replace ("#", $number, self::OPTION_AD_GENERAL_TAG);
      $this->option_ad_after_day              = str_replace ("#", $number, self::OPTION_AD_AFTER_DAY);
      $this->option_ad_domain_list            = str_replace ("#", $number, self::OPTION_AD_DOMAIN_LIST);
      $this->option_ad_domain_list_type       = str_replace ("#", $number, self::OPTION_AD_DOMAIN_LIST_TYPE);
      $this->option_ad_category_list          = str_replace ("#", $number, self::OPTION_AD_CATEGORY_LIST);
      $this->option_ad_category_list_type     = str_replace ("#", $number, self::OPTION_AD_CATEGORY_LIST_TYPE);
      $this->option_widget_settings_home      = str_replace ("#", $number, self::OPTION_WIDGET_SETTINGS_HOME);
      $this->option_widget_settings_page      = str_replace ("#", $number, self::OPTION_WIDGET_SETTINGS_PAGE);
      $this->option_widget_settings_post      = str_replace ("#", $number, self::OPTION_WIDGET_SETTINGS_POST);
      $this->option_widget_settings_category  = str_replace ("#", $number, self::OPTION_WIDGET_SETTINGS_CATEGORY);
      $this->option_widget_settings_search    = str_replace ("#", $number, self::OPTION_WIDGET_SETTINGS_SEARCH);
      $this->option_widget_settings_archive   = str_replace ("#", $number, self::OPTION_WIDGET_SETTINGS_ARCHIVE);
      $this->option_process_php               = str_replace ("#", $number, self::OPTION_PROCESS_PHP);
      $this->option_enable_manual             = str_replace ("#", $number, self::OPTION_ENABLE_MANUAL);
      $this->option_enable_php_call           = str_replace ("#", $number, self::OPTION_ENABLE_PHP_CALL);
      $this->option_paragraph_text            = str_replace ("#", $number, self::OPTION_PARAGRAPH_TEXT);
      $this->option_custom_css                = str_replace ("#", $number, self::OPTION_CUSTOM_CSS);
      $this->option_display_for_users         = str_replace ("#", $number, self::OPTION_DISPLAY_FOR_USERS);

      parent::CodeBlock();
      $this->number = $number;
      $this->wp_options[$this->option_ad_name] = AD_NAME." ".$number;
    }

}


class AdH extends BaseCodeBlock{

   const OPTION_AD_DATA       = "adH_data";
   const OPTION_ENABLE_MANUAL = "adH_enable";
   const OPTION_PROCESS_PHP   = "adH_process_php";

    // constructor
    public function AdH() {

      $this->option_ad_data       = self::OPTION_AD_DATA;
      $this->option_enable_manual = self::OPTION_ENABLE_MANUAL;
      $this->option_process_php   = self::OPTION_PROCESS_PHP;

      parent::BaseCodeBlock();
    }

}

class AdF extends BaseCodeBlock{

   const OPTION_AD_DATA       = "adF_data";
   const OPTION_ENABLE_MANUAL = "adF_enable";
   const OPTION_PROCESS_PHP   = "adF_process_php";

    // constructor
    public function AdF() {

      $this->option_ad_data       = self::OPTION_AD_DATA;
      $this->option_enable_manual = self::OPTION_ENABLE_MANUAL;
      $this->option_process_php   = self::OPTION_PROCESS_PHP;

      parent::BaseCodeBlock();
    }

}



