<?php

require_once AD_INSERTER_PLUGIN_DIR.'constants.php';

abstract class ai_BaseCodeBlock {
    var $wp_options;

    function ai_BaseCodeBlock() {
      $this->wp_options = array ();

    $this->wp_options [AI_OPTION_CODE]           = AD_EMPTY_DATA;
    $this->wp_options [AI_OPTION_PROCESS_PHP]    = AD_SETTINGS_NOT_CHECKED;
    $this->wp_options [AI_OPTION_ENABLE_MANUAL]  = AD_SETTINGS_NOT_CHECKED;
    }

  public function load_options ($block) {
    global $ai_db_options;

    if (isset ($ai_db_options [$block])) $options = $ai_db_options [$block]; else $options = '';

    // Convert old options
    if (!$options) {
      if     ($block == "h") $options = ai_get_option (str_replace ("#", "Header", AD_ADx_OPTIONS));
      elseif ($block == "f") $options = ai_get_option (str_replace ("#", "Footer", AD_ADx_OPTIONS));
      else                   $options = ai_get_option (str_replace ("#", $block, AD_ADx_OPTIONS));

      if (is_array ($options)) {

        $old_name = "ad" . $block . "_data";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_CODE] = $options [$old_name];
          unset ($options [$old_name]);
        }
        $old_name = "ad" . $block . "_enable_manual";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_ENABLE_MANUAL] = $options [$old_name];
          unset ($options [$old_name]);
        }
        $old_name = "ad" . $block . "_process_php";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_PROCESS_PHP] = $options [$old_name];
          unset ($options [$old_name]);
        }

        $old_name = "adH_data";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_CODE] = $options [$old_name];
          unset ($options [$old_name]);
        }
        $old_name = "adH_enable";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_ENABLE_MANUAL] = $options [$old_name];
          unset ($options [$old_name]);
        }
        $old_name = "adH_process_php";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_PROCESS_PHP] = $options [$old_name];
          unset ($options [$old_name]);
        }

        $old_name = "adF_data";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_CODE] = $options [$old_name];
          unset ($options [$old_name]);
        }
        $old_name = "adF_enable";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_ENABLE_MANUAL] = $options [$old_name];
          unset ($options [$old_name]);
        }
        $old_name = "adF_process_php";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_PROCESS_PHP] = $options [$old_name];
          unset ($options [$old_name]);
        }

        $old_name = "ad" . $block . "_name";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_NAME] = $options [$old_name];
          unset ($options [$old_name]);
        }
        $old_name = "ad" . $block . "_displayType";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_DISPLAY_TYPE] = $options [$old_name];
          unset ($options [$old_name]);
        }
        $old_name = "ad" . $block . "_paragraphNumber";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_PARAGRAPH_NUMBER] = $options [$old_name];
          unset ($options [$old_name]);
        }
        $old_name = "ad" . $block . "_minimum_paragraphs";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_MIN_PARAGRAPHS] = $options [$old_name];
          unset ($options [$old_name]);
        }
        $old_name = "ad" . $block . "_minimum_words";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_MIN_WORDS] = $options [$old_name];
          unset ($options [$old_name]);
        }
        $old_name = "ad" . $block . "_excerptNumber";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_EXCERPT_NUMBER] = $options [$old_name];
          unset ($options [$old_name]);
        }
        $old_name = "ad" . $block . "_directionType";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_DIRECTION_TYPE] = $options [$old_name];
          unset ($options [$old_name]);
        }
        $old_name = "ad" . $block . "_floatType";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_ALIGNMENT_TYPE] = $options [$old_name];
          unset ($options [$old_name]);
        }
        $old_name = "ad" . $block . "_general_tag";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_GENERAL_TAG] = $options [$old_name];
          unset ($options [$old_name]);
        }
        $old_name = "ad" . $block . "_after_day";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_AFTER_DAYS] = $options [$old_name];
          unset ($options [$old_name]);
        }
        $old_name = "ad" . $block . "_block_user";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_DOMAIN_LIST] = $options [$old_name];
          unset ($options [$old_name]);
        }
        $old_name = "ad" . $block . "_domain_list_type";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_DOMAIN_LIST_TYPE] = $options [$old_name];
          unset ($options [$old_name]);
        }
        $old_name = "ad" . $block . "_block_cat";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_CATEGORY_LIST] = $options [$old_name];
          unset ($options [$old_name]);
        }
        $old_name = "ad" . $block . "_block_cat_type";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_CATEGORY_LIST_TYPE] = $options [$old_name];
          unset ($options [$old_name]);
        }
        $old_name = "ad" . $block . "_block_tag";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_TAG_LIST] = $options [$old_name];
          unset ($options [$old_name]);
        }
        $old_name = "ad" . $block . "_block_tag_type";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_TAG_LIST_TYPE] = $options [$old_name];
          unset ($options [$old_name]);
        }
        $old_name = "ad" . $block . "_widget_settings_home";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_DISPLAY_ON_HOMEPAGE] = $options [$old_name];
          unset ($options [$old_name]);
        }
        $old_name = "ad" . $block . "_widget_settings_page";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_DISPLAY_ON_PAGES] = $options [$old_name];
          unset ($options [$old_name]);
        }
        $old_name = "ad" . $block . "_widget_settings_post";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_DISPLAY_ON_POSTS] = $options [$old_name];
          unset ($options [$old_name]);
        }
        $old_name = "ad" . $block . "_widget_settings_category";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_DISPLAY_ON_CATEGORY_PAGES] = $options [$old_name];
          unset ($options [$old_name]);
        }
        $old_name = "ad" . $block . "_widget_settings_search";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_DISPLAY_ON_SEARCH_PAGES] = $options [$old_name];
          unset ($options [$old_name]);
        }
        $old_name = "ad" . $block . "_widget_settings_archive";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_DISPLAY_ON_ARCHIVE_PAGES] = $options [$old_name];
          unset ($options [$old_name]);
        }
        $old_name = "ad" . $block . "_enabled_on_which_pages";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_ENABLED_ON_WHICH_PAGES] = $options [$old_name];
          unset ($options [$old_name]);
        }
        $old_name = "ad" . $block . "_enabled_on_which_posts";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_ENABLED_ON_WHICH_POSTS] = $options [$old_name];
          unset ($options [$old_name]);
        }
        $old_name = "ad" . $block . "_enable_php_call";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_ENABLE_PHP_CALL] = $options [$old_name];
          unset ($options [$old_name]);
        }
        $old_name = "ad" . $block . "_paragraph_text";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_PARAGRAPH_TEXT] = $options [$old_name];
          unset ($options [$old_name]);
        }
        $old_name = "ad" . $block . "_custom_css";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_CUSTOM_CSS] = $options [$old_name];
          unset ($options [$old_name]);
        }
        $old_name = "ad" . $block . "_display_for_users";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_DISPLAY_FOR_USERS] = $options [$old_name];
          unset ($options [$old_name]);
        }
        $old_name = "ad" . $block . "_display_for_devices";
        if (isset ($options [$old_name])) {
          $options [AI_OPTION_DISPLAY_FOR_DEVICES] = $options [$old_name];
          unset ($options [$old_name]);
        }
      }
    }

     if ($options != '') $this->wp_options = array_merge ($this->wp_options, $options);
     unset ($this->wp_options ['']);
    }

   public function get_ad_data(){
    $ad_data = isset ($this->wp_options [AI_OPTION_CODE]) ? $this->wp_options [AI_OPTION_CODE] : "";
     return $ad_data;
   }

   public function get_enable_manual (){
    $enable_manual = isset ($this->wp_options [AI_OPTION_ENABLE_MANUAL]) ? $this->wp_options [AI_OPTION_ENABLE_MANUAL] : "";
     if ($enable_manual == '') $enable_manual = AD_SETTINGS_NOT_CHECKED;
     return $enable_manual;
   }

   public function get_process_php (){
    $process_php = isset ($this->wp_options [AI_OPTION_PROCESS_PHP]) ? $this->wp_options [AI_OPTION_PROCESS_PHP] : "";
     if ($process_php == '') $process_php = AD_SETTINGS_NOT_CHECKED;
     return $process_php;
   }
}

abstract class ai_CodeBlock extends ai_BaseCodeBlock {

    var $number;

    function ai_CodeBlock() {

      $this->number = 0;

      parent::ai_BaseCodeBlock();

      $this->wp_options [AI_OPTION_NAME]                       = AD_NAME;
      $this->wp_options [AI_OPTION_DISPLAY_TYPE]               = AD_SELECT_NONE;
      $this->wp_options [AI_OPTION_PARAGRAPH_NUMBER]           = AD_ZERO_DATA;
      $this->wp_options [AI_OPTION_MIN_PARAGRAPHS]             = AD_ZERO_DATA;
      $this->wp_options [AI_OPTION_MIN_WORDS]                  = AD_ZERO_DATA;
      $this->wp_options [AI_OPTION_EXCERPT_NUMBER]             = AD_ZERO_DATA;
      $this->wp_options [AI_OPTION_DIRECTION_TYPE]             = AD_DIRECTION_FROM_TOP;
      $this->wp_options [AI_OPTION_ALIGNMENT_TYPE]             = AD_ALIGNMENT_NONE;
      $this->wp_options [AI_OPTION_GENERAL_TAG]                = AD_GENERAL_TAG;
      $this->wp_options [AI_OPTION_AFTER_DAYS]                 = AD_ZERO_DATA;
      $this->wp_options [AI_OPTION_URL_LIST]                   = AD_EMPTY_DATA;
      $this->wp_options [AI_OPTION_URL_LIST_TYPE]              = AD_BLACK_LIST;
      $this->wp_options [AI_OPTION_DOMAIN_LIST]                = AD_EMPTY_DATA;
      $this->wp_options [AI_OPTION_DOMAIN_LIST_TYPE]           = AD_BLACK_LIST;
      $this->wp_options [AI_OPTION_CATEGORY_LIST]              = AD_EMPTY_DATA;
      $this->wp_options [AI_OPTION_CATEGORY_LIST_TYPE]         = AD_BLACK_LIST;
      $this->wp_options [AI_OPTION_TAG_LIST]                   = AD_EMPTY_DATA;
      $this->wp_options [AI_OPTION_TAG_LIST_TYPE]              = AD_BLACK_LIST;
      $this->wp_options [AI_OPTION_DISPLAY_ON_HOMEPAGE]        = AD_SETTINGS_CHECKED;
      $this->wp_options [AI_OPTION_DISPLAY_ON_PAGES]           = AD_SETTINGS_NOT_CHECKED;
      $this->wp_options [AI_OPTION_DISPLAY_ON_POSTS]           = AD_SETTINGS_CHECKED;
      $this->wp_options [AI_OPTION_DISPLAY_ON_CATEGORY_PAGES]  = AD_SETTINGS_CHECKED;
      $this->wp_options [AI_OPTION_DISPLAY_ON_SEARCH_PAGES]    = AD_SETTINGS_CHECKED;
      $this->wp_options [AI_OPTION_DISPLAY_ON_ARCHIVE_PAGES]   = AD_SETTINGS_CHECKED;
      $this->wp_options [AI_OPTION_ENABLED_ON_WHICH_PAGES]     = AD_ENABLED_ON_ALL;
      $this->wp_options [AI_OPTION_ENABLED_ON_WHICH_POSTS]     = AD_ENABLED_ON_ALL;
      $this->wp_options [AI_OPTION_ENABLE_PHP_CALL]            = AD_SETTINGS_NOT_CHECKED;
      $this->wp_options [AI_OPTION_PARAGRAPH_TEXT]             = AD_EMPTY_DATA;
      $this->wp_options [AI_OPTION_PARAGRAPH_TEXT_TYPE]        = AD_DO_NOT_CONTAIN;
      $this->wp_options [AI_OPTION_CUSTOM_CSS]                 = AD_EMPTY_DATA;
      $this->wp_options [AI_OPTION_DISPLAY_FOR_USERS]          = AD_DISPLAY_ALL_USERS;
      $this->wp_options [AI_OPTION_DISPLAY_FOR_DEVICES]        = AD_DISPLAY_ALL_DEVICES;
    }

   public function get_display_type(){
     $option = isset ($this->wp_options [AI_OPTION_DISPLAY_TYPE]) ? $this->wp_options [AI_OPTION_DISPLAY_TYPE] : "";
     if ($option == '') $option = AD_SELECT_NONE;
     elseif ($option == AD_SELECT_MANUAL) $option = AD_SELECT_NONE;
     return $option;
    }

   public function get_paragraph_number(){
     $option = isset ($this->wp_options [AI_OPTION_PARAGRAPH_NUMBER]) ? $this->wp_options [AI_OPTION_PARAGRAPH_NUMBER] : "";
     if ($option == '') $option = AD_ZERO_DATA;
     return $option;
    }

   public function get_paragraph_number_minimum(){
     $option = isset ($this->wp_options [AI_OPTION_MIN_PARAGRAPHS]) ? $this->wp_options [AI_OPTION_MIN_PARAGRAPHS] : "";
     if ($option == '') $option = AD_ZERO_DATA;
     return $option;
    }

   public function get_minimum_words(){
     $option = isset ($this->wp_options [AI_OPTION_MIN_WORDS]) ? $this->wp_options [AI_OPTION_MIN_WORDS] : "";
     if ($option == '') $option = AD_ZERO_DATA;
     return $option;
    }

   public function get_excerpt_number(){
     $option = isset ($this->wp_options [AI_OPTION_EXCERPT_NUMBER]) ? $this->wp_options [AI_OPTION_EXCERPT_NUMBER] : "";
     if ($option == '') $option = AD_ZERO_DATA;
     return $option;
    }

   public function get_direction_type(){
     $option = isset ($this->wp_options [AI_OPTION_DIRECTION_TYPE]) ? $this->wp_options [AI_OPTION_DIRECTION_TYPE] : "";
     if ($option == '') $option = AD_DIRECTION_FROM_TOP;
     return $option;
    }

   public function get_alignment_type(){
     // Update old field names
     $option = isset ($this->wp_options [AI_OPTION_ALIGNMENT_TYPE]) ? $this->wp_options [AI_OPTION_ALIGNMENT_TYPE] : "";
     if ($option == 'Left'){
       $option = AD_ALIGNMENT_FLOAT_LEFT;
       $this->wp_options [AI_OPTION_ALIGNMENT_TYPE] = $option;
     }
     elseif ($option == 'Right'){
       $option = AD_ALIGNMENT_FLOAT_RIGHT;
       $this->wp_options [AI_OPTION_ALIGNMENT_TYPE] = $option;
     }
     elseif ($option == '') $option = AD_ALIGNMENT_NONE;
     return $option;
    }

   public function get_alignmet_style ($margin = true){
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
     $option = isset ($this->wp_options [AI_OPTION_DISPLAY_ON_HOMEPAGE]) ? $this->wp_options [AI_OPTION_DISPLAY_ON_HOMEPAGE] : "";
     if ($option == '') $option = AD_SETTINGS_CHECKED;
     return $option;
   }

   public function get_display_settings_page(){
     $option = isset ($this->wp_options [AI_OPTION_DISPLAY_ON_PAGES]) ? $this->wp_options [AI_OPTION_DISPLAY_ON_PAGES] : "";
     if ($option == '') $option = AD_SETTINGS_NOT_CHECKED;
     return $option;
   }

   public function get_display_settings_post(){
     $option = isset ($this->wp_options [AI_OPTION_DISPLAY_ON_POSTS]) ? $this->wp_options [AI_OPTION_DISPLAY_ON_POSTS] : "";
     if ($option == '') $option = AD_SETTINGS_CHECKED;
     return $option;
   }

   public function get_display_settings_category(){
     $option = isset ($this->wp_options [AI_OPTION_DISPLAY_ON_CATEGORY_PAGES]) ? $this->wp_options [AI_OPTION_DISPLAY_ON_CATEGORY_PAGES] : "";
     if ($option == '') $option = AD_SETTINGS_CHECKED;
     return $option;
   }

   public function get_display_settings_search(){
     $option = isset ($this->wp_options [AI_OPTION_DISPLAY_ON_SEARCH_PAGES]) ? $this->wp_options [AI_OPTION_DISPLAY_ON_SEARCH_PAGES] : "";
     if ($option == '') $option = AD_SETTINGS_CHECKED;
     return $option;
   }

   public function get_display_settings_archive(){
     $option = isset ($this->wp_options [AI_OPTION_DISPLAY_ON_ARCHIVE_PAGES]) ? $this->wp_options [AI_OPTION_DISPLAY_ON_ARCHIVE_PAGES] : "";
     if ($option == '') $option = AD_SETTINGS_CHECKED;
     return $option;
   }

   public function get_enable_manual (){
     $option = isset ($this->wp_options [AI_OPTION_ENABLE_MANUAL]) ? $this->wp_options [AI_OPTION_ENABLE_MANUAL] : "";
     if ($option == '') {
       $display_option = $this->get_display_type ();
       if ($display_option == AD_SELECT_MANUAL)
         $option = AD_SETTINGS_CHECKED; else
           $option = AD_SETTINGS_NOT_CHECKED;
     }
     return $option;
   }

   public function get_enable_php_call (){
     $option = isset ($this->wp_options [AI_OPTION_ENABLE_PHP_CALL]) ? $this->wp_options [AI_OPTION_ENABLE_PHP_CALL] : "";
     if ($option == '') $option = AD_SETTINGS_NOT_CHECKED;
     return $option;
   }

   public function get_paragraph_text (){
     $paragraph_text = isset ($this->wp_options [AI_OPTION_PARAGRAPH_TEXT]) ? $this->wp_options [AI_OPTION_PARAGRAPH_TEXT] : "";
     return $paragraph_text;
   }

   public function get_paragraph_text_type (){
     $option = isset ($this->wp_options [AI_OPTION_PARAGRAPH_TEXT_TYPE]) ? $this->wp_options [AI_OPTION_PARAGRAPH_TEXT_TYPE] : "";
     if ($option == '') $option = AD_DO_NOT_CONTAIN;
     return $option;
   }

   public function get_custom_css (){
     $option = isset ($this->wp_options [AI_OPTION_CUSTOM_CSS]) ? $this->wp_options [AI_OPTION_CUSTOM_CSS] : "";
     return $option;
   }

   public function get_display_for_users (){
     if (isset ($this->wp_options [AI_OPTION_DISPLAY_FOR_USERS])) {
      $display_for_users = $this->wp_options [AI_OPTION_DISPLAY_FOR_USERS];
     } else $display_for_users = '';
     if ($display_for_users == '') $display_for_users = AD_DISPLAY_ALL_USERS;
     return $display_for_users;
   }

   public function get_display_for_devices (){
     if (isset ($this->wp_options [AI_OPTION_DISPLAY_FOR_DEVICES])) {
      $display_for_devices = $this->wp_options [AI_OPTION_DISPLAY_FOR_DEVICES];
     } else $display_for_devices = '';
     if ($display_for_devices == '') $display_for_devices = AD_DISPLAY_ALL_DEVICES;
     return $display_for_devices;
   }

   public function get_ad_data_replaced(){

     $general_tag = isset ($this->wp_options [AI_OPTION_GENERAL_TAG]) ? $this->wp_options [AI_OPTION_GENERAL_TAG] : "";

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
     $option = isset ($this->wp_options [AI_OPTION_GENERAL_TAG]) ? $this->wp_options [AI_OPTION_GENERAL_TAG] : "";
     if ($option == '') $option = AD_GENERAL_TAG;
     return $option;
   }

	public function get_ad_after_day(){
     $option = isset ($this->wp_options [AI_OPTION_AFTER_DAYS]) ? $this->wp_options [AI_OPTION_AFTER_DAYS] : "";
     if ($option == '') $option = AD_ZERO_DATA;
     return $option;
   }

  public function get_ad_url_list(){
     $option = isset ($this->wp_options [AI_OPTION_URL_LIST]) ? $this->wp_options [AI_OPTION_URL_LIST] : "";
     return $option;
  }

  public function get_ad_url_list_type (){
     $option = isset ($this->wp_options [AI_OPTION_URL_LIST_TYPE]) ? $this->wp_options [AI_OPTION_URL_LIST_TYPE] : "";
     if ($option == '') $option = AD_BLACK_LIST;
     return $option;
  }

  public function get_ad_domain_list(){
     $option = isset ($this->wp_options [AI_OPTION_DOMAIN_LIST]) ? $this->wp_options [AI_OPTION_DOMAIN_LIST] : "";
     return $option;
   }

  public function get_ad_domain_list_type (){
     $option = isset ($this->wp_options [AI_OPTION_DOMAIN_LIST_TYPE]) ? $this->wp_options [AI_OPTION_DOMAIN_LIST_TYPE] : "";
     if ($option == '') $option = AD_BLACK_LIST;
     return $option;
   }

	public function get_ad_name(){
     $option = isset ($this->wp_options [AI_OPTION_NAME]) ? $this->wp_options [AI_OPTION_NAME] : "";
     if ($option == '') $option = AD_NAME. " " . $this->number;
     return $option;
   }

	public function get_ad_block_cat(){
     $option = isset ($this->wp_options [AI_OPTION_CATEGORY_LIST]) ? $this->wp_options [AI_OPTION_CATEGORY_LIST] : "";
     return $option;
   }

  public function get_ad_block_cat_type(){
     $option = isset ($this->wp_options [AI_OPTION_CATEGORY_LIST_TYPE]) ? $this->wp_options [AI_OPTION_CATEGORY_LIST_TYPE] : "";

     // Update old data
     if ($option == ''){
       $option = AD_BLACK_LIST;
       $this->wp_options [AI_OPTION_CATEGORY_LIST_TYPE] = AD_BLACK_LIST;
     }

     if ($option == '') $option = AD_BLACK_LIST;
     return $option;
   }

  public function get_ad_block_tag(){
     $option = isset ($this->wp_options [AI_OPTION_TAG_LIST]) ? $this->wp_options [AI_OPTION_TAG_LIST] : "";
     return $option;
  }

  public function get_ad_block_tag_type(){
     $option = isset ($this->wp_options [AI_OPTION_TAG_LIST_TYPE]) ? $this->wp_options [AI_OPTION_TAG_LIST_TYPE] : "";
     if ($option == '') $option = AD_BLACK_LIST;
     return $option;
  }

  public function get_ad_enabled_on_which_pages (){
    $option = isset ($this->wp_options [AI_OPTION_ENABLED_ON_WHICH_PAGES]) ? $this->wp_options [AI_OPTION_ENABLED_ON_WHICH_PAGES] : "";
    if ($option == '') $option = AD_ENABLED_ON_ALL;
    return $option;
  }

  public function get_ad_enabled_on_which_posts (){
    $option = isset ($this->wp_options [AI_OPTION_ENABLED_ON_WHICH_POSTS]) ? $this->wp_options [AI_OPTION_ENABLED_ON_WHICH_POSTS] : "";
    if ($option == '') $option = AD_ENABLED_ON_ALL;
    return $option;
  }
}

class ai_Block extends ai_CodeBlock {

    public function ai_Block ($number) {
      parent::ai_CodeBlock();

      $this->number = $number;
      $this->wp_options [AI_OPTION_NAME] = AD_NAME." ".$number;
    }
}

class ai_AdH extends ai_BaseCodeBlock {

  public function ai_AdH() {
    parent::ai_BaseCodeBlock();
  }
}

class ai_AdF extends ai_BaseCodeBlock {

  public function ai_AdF() {
    parent::ai_BaseCodeBlock();
  }
}
