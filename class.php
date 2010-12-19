<?php

require_once 'constants.php';

abstract class BaseAdInserter {

    // define properties
    var $wp_options;

    var $option_append_type;
    var $option_paragraph_number;
    var $option_direction_type;
    var $option_float_type;
    var $option_ad_data;
    var $option_ad_general_tag;
    var $option_ad_name;
    var $option_ad_after_day;
    var $option_ad_block_user;
    var $option_ad_disabled;
    var $option_ad_block_cat;

    function BaseAdInserter() {

    	$this->wp_options = array();

      $this->wp_options[$this->option_ad_name] = AD_NAME;
      $this->wp_options[$this->option_append_type] = AD_SELECT_NONE;
      $this->wp_options[$this->option_paragraph_number] = AD_ZERO_DATA;
      $this->wp_options[$this->option_direction_type] = AD_DIRECTION_FROM_TOP;
      $this->wp_options[$this->option_float_type] = AD_FLOAT_NONE;
      $this->wp_options[$this->option_ad_data] = AD_EMPTY_DATA;
      $this->wp_options[$this->option_ad_general_tag] = AD_GENERAL_TAG;
    	$this->wp_options[$this->option_ad_after_day] = AD_ZERO_DATA;
    	$this->wp_options[$this->option_ad_block_user] = AD_EMPTY_DATA;
    	$this->wp_options[$this->option_ad_disabled] = AD_EMPTY_DATA;
    	$this->wp_options[$this->option_ad_block_cat] = AD_EMPTY_DATA;
    }

   public function get_append_type(){
      return $this->wp_options[$this->option_append_type];
    }

   public function get_paragraph_number(){
      return $this->wp_options[$this->option_paragraph_number];
    }

   public function get_direction_type(){
      return $this->wp_options[$this->option_direction_type];
    }

   public function get_float_type(){
      return $this->wp_options[$this->option_float_type];
    }

   public function get_ad_data(){

      return $this->wp_options[$this->option_ad_data];
    }

   public function get_ad_data_replaced(){

     $general_tag = $this->wp_options[$this->option_ad_general_tag];
     $general_tag = str_replace ("&amp;", "and", $general_tag);
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

     $ad_data = preg_replace ("/{title}/i",          $title,          $this->wp_options[$this->option_ad_data]);
     $ad_data = preg_replace ("/{short_title}/i",    $short_title,    $ad_data);
     $ad_data = preg_replace ("/{category}/i",       $category,       $ad_data);
     $ad_data = preg_replace ("/{short_category}/i", $short_category, $ad_data);
     $ad_data = preg_replace ("/{tag}/i",            $tag,            $ad_data);
     $ad_data = preg_replace ("/{smart_tag}/i",      $smart_tag,      $ad_data);

     return $ad_data;
   }

   public function get_ad_general_tag(){
      return $this->wp_options[$this->option_ad_general_tag];
   }

	public function get_ad_after_day(){
    	return $this->wp_options[$this->option_ad_after_day];
   }

	public function get_ad_block_user(){
    	return $this->wp_options[$this->option_ad_block_user];
   }

	public function get_ad_name(){
      return $this->wp_options[$this->option_ad_name];
   }

	public function get_ad_disable(){
    	return $this->wp_options[$this->option_ad_disabled];
   }

	public function get_ad_block_cat(){
    	return $this->wp_options[$this->option_ad_block_cat];
   }

}

class Ad1 extends BaseAdInserter{

   const OPTION_AD_NAME = "ad1_name";
   const OPTION_APPEND_TYPE = "ad1_displayType";
   const OPTION_PARAGRAPH_NUMBER = "ad1_paragraphNumber";
   const OPTION_DIRECTION_TYPE = "ad1_directionType";
   const OPTION_FLOAT_TYPE = "ad1_floatType";
   const OPTION_AD_DATA = "ad1_data";
   const OPTION_AD_GENERAL_TAG = "ad1_general_tag";
   const OPTION_AD_AFTER_DAY = "ad1_after_day";
   const OPTION_AD_BLOCK_USER = "ad1_block_user";
   const OPTION_AD_DISABLE = "ad1_disabled";
   const OPTION_AD_BLOCK_CAT = "ad1_block_cat";

	//constructor
    public function Ad1() {

      $this->option_ad_name = self::OPTION_AD_NAME;
      $this->option_append_type = self::OPTION_APPEND_TYPE;
      $this->option_paragraph_number = self::OPTION_PARAGRAPH_NUMBER;
      $this->option_direction_type = self::OPTION_DIRECTION_TYPE;
      $this->option_float_type = self::OPTION_FLOAT_TYPE;
      $this->option_ad_data = self::OPTION_AD_DATA;
      $this->option_ad_general_tag = self::OPTION_AD_GENERAL_TAG;
      $this->option_ad_after_day = self::OPTION_AD_AFTER_DAY;
      $this->option_ad_block_user = self::OPTION_AD_BLOCK_USER;
      $this->option_ad_disabled = self::OPTION_AD_DISABLE;
      $this->option_ad_block_cat = self::OPTION_AD_BLOCK_CAT;

      parent::BaseAdInserter();
      $this->wp_options[$this->option_ad_name] = AD_NAME." 1";
      $this->wp_options[$this->option_ad_disabled] = AD_DISABLED_1;
    }

}

class Ad2 extends BaseAdInserter{

   const OPTION_AD_NAME = "ad2_name";
   const OPTION_APPEND_TYPE = "ad2_displayType";
   const OPTION_PARAGRAPH_NUMBER = "ad2_paragraphNumber";
   const OPTION_DIRECTION_TYPE = "ad2_directionType";
   const OPTION_FLOAT_TYPE = "ad2_floatType";
   const OPTION_AD_DATA = "ad2_data";
   const OPTION_AD_GENERAL_TAG = "ad2_general_tag";
   const OPTION_AD_AFTER_DAY = "ad2_after_day";
   const OPTION_AD_BLOCK_USER = "ad2_block_user";
   const OPTION_AD_DISABLE = "ad2_disabled";
   const OPTION_AD_BLOCK_CAT = "ad2_block_cat";

	//constructor
    public function Ad2() {

      $this->option_ad_name = self::OPTION_AD_NAME;
      $this->option_append_type = self::OPTION_APPEND_TYPE;
      $this->option_paragraph_number = self::OPTION_PARAGRAPH_NUMBER;
      $this->option_direction_type = self::OPTION_DIRECTION_TYPE;
      $this->option_float_type = self::OPTION_FLOAT_TYPE;
      $this->option_ad_data = self::OPTION_AD_DATA;
      $this->option_ad_general_tag = self::OPTION_AD_GENERAL_TAG;
      $this->option_ad_after_day = self::OPTION_AD_AFTER_DAY;
      $this->option_ad_block_user = self::OPTION_AD_BLOCK_USER;
      $this->option_ad_disabled = self::OPTION_AD_DISABLE;
      $this->option_ad_block_cat = self::OPTION_AD_BLOCK_CAT;

      parent::BaseAdInserter();
      $this->wp_options[$this->option_ad_name] = AD_NAME." 2";
      $this->wp_options[$this->option_ad_disabled] = AD_DISABLED_2;
    }

}

class Ad3 extends BaseAdInserter{

   const OPTION_AD_NAME = "ad3_name";
   const OPTION_APPEND_TYPE = "ad3_displayType";
   const OPTION_PARAGRAPH_NUMBER = "ad3_paragraphNumber";
   const OPTION_DIRECTION_TYPE = "ad3_directionType";
   const OPTION_FLOAT_TYPE = "ad3_floatType";
   const OPTION_AD_DATA = "ad3_data";
   const OPTION_AD_GENERAL_TAG = "ad3_general_tag";
   const OPTION_AD_AFTER_DAY = "ad3_after_day";
   const OPTION_AD_BLOCK_USER = "ad3_block_user";
   const OPTION_AD_DISABLE = "ad3_disabled";
   const OPTION_AD_BLOCK_CAT = "ad3_block_cat";

	//constructor
    public function Ad3() {

      $this->option_ad_name = self::OPTION_AD_NAME;
      $this->option_append_type = self::OPTION_APPEND_TYPE;
      $this->option_paragraph_number = self::OPTION_PARAGRAPH_NUMBER;
      $this->option_direction_type = self::OPTION_DIRECTION_TYPE;
      $this->option_float_type = self::OPTION_FLOAT_TYPE;
      $this->option_ad_data = self::OPTION_AD_DATA;
      $this->option_ad_general_tag = self::OPTION_AD_GENERAL_TAG;
      $this->option_ad_after_day = self::OPTION_AD_AFTER_DAY;
      $this->option_ad_block_user = self::OPTION_AD_BLOCK_USER;
      $this->option_ad_disabled = self::OPTION_AD_DISABLE;
      $this->option_ad_block_cat = self::OPTION_AD_BLOCK_CAT;

      parent::BaseAdInserter();
      $this->wp_options[$this->option_ad_name] = AD_NAME." 3";
      $this->wp_options[$this->option_ad_disabled] = AD_DISABLED_3;
    }

}

class Ad4 extends BaseAdInserter{

   const OPTION_AD_NAME = "ad4_name";
   const OPTION_APPEND_TYPE = "ad4_displayType";
   const OPTION_PARAGRAPH_NUMBER = "ad4_paragraphNumber";
   const OPTION_DIRECTION_TYPE = "ad4_directionType";
   const OPTION_FLOAT_TYPE = "ad4_floatType";
   const OPTION_AD_DATA = "ad4_data";
   const OPTION_AD_GENERAL_TAG = "ad4_general_tag";
   const OPTION_AD_AFTER_DAY = "ad4_after_day";
   const OPTION_AD_BLOCK_USER = "ad4_block_user";
   const OPTION_AD_DISABLE = "ad4_disabled";
   const OPTION_AD_BLOCK_CAT = "ad4_block_cat";

	//constructor
    public function Ad4() {

      $this->option_ad_name = self::OPTION_AD_NAME;
      $this->option_append_type = self::OPTION_APPEND_TYPE;
      $this->option_paragraph_number = self::OPTION_PARAGRAPH_NUMBER;
      $this->option_direction_type = self::OPTION_DIRECTION_TYPE;
      $this->option_float_type = self::OPTION_FLOAT_TYPE;
      $this->option_ad_data = self::OPTION_AD_DATA;
      $this->option_ad_general_tag = self::OPTION_AD_GENERAL_TAG;
      $this->option_ad_after_day = self::OPTION_AD_AFTER_DAY;
      $this->option_ad_block_user = self::OPTION_AD_BLOCK_USER;
      $this->option_ad_disabled = self::OPTION_AD_DISABLE;
      $this->option_ad_block_cat = self::OPTION_AD_BLOCK_CAT;

      parent::BaseAdInserter();
      $this->wp_options[$this->option_ad_name] = AD_NAME." 4";
      $this->wp_options[$this->option_ad_disabled] = AD_DISABLED_4;
    }

}

class Ad5 extends BaseAdInserter{

   const OPTION_AD_NAME = "ad5_name";
   const OPTION_APPEND_TYPE = "ad5_displayType";
   const OPTION_PARAGRAPH_NUMBER = "ad5_paragraphNumber";
   const OPTION_DIRECTION_TYPE = "ad5_directionType";
   const OPTION_FLOAT_TYPE = "ad5_floatType";
   const OPTION_AD_DATA = "ad5_data";
   const OPTION_AD_GENERAL_TAG = "ad5_general_tag";
   const OPTION_AD_AFTER_DAY = "ad5_after_day";
   const OPTION_AD_BLOCK_USER = "ad5_block_user";
   const OPTION_AD_DISABLE = "ad5_disabled";
   const OPTION_AD_BLOCK_CAT = "ad5_block_cat";

  //constructor
    public function Ad5() {

      $this->option_ad_name = self::OPTION_AD_NAME;
      $this->option_append_type = self::OPTION_APPEND_TYPE;
      $this->option_paragraph_number = self::OPTION_PARAGRAPH_NUMBER;
      $this->option_direction_type = self::OPTION_DIRECTION_TYPE;
      $this->option_float_type = self::OPTION_FLOAT_TYPE;
      $this->option_ad_data = self::OPTION_AD_DATA;
      $this->option_ad_general_tag = self::OPTION_AD_GENERAL_TAG;
      $this->option_ad_after_day = self::OPTION_AD_AFTER_DAY;
      $this->option_ad_block_user = self::OPTION_AD_BLOCK_USER;
      $this->option_ad_disabled = self::OPTION_AD_DISABLE;
      $this->option_ad_block_cat = self::OPTION_AD_BLOCK_CAT;

      parent::BaseAdInserter();
      $this->wp_options[$this->option_ad_name] = AD_NAME." 5";
      $this->wp_options[$this->option_ad_disabled] = AD_DISABLED_5;
    }

}

class Ad6 extends BaseAdInserter{

   const OPTION_AD_NAME = "ad6_name";
   const OPTION_APPEND_TYPE = "ad6_displayType";
   const OPTION_PARAGRAPH_NUMBER = "ad6_paragraphNumber";
   const OPTION_DIRECTION_TYPE = "ad6_directionType";
   const OPTION_FLOAT_TYPE = "ad6_floatType";
   const OPTION_AD_DATA = "ad6_data";
   const OPTION_AD_GENERAL_TAG = "ad6_general_tag";
   const OPTION_AD_AFTER_DAY = "ad6_after_day";
   const OPTION_AD_BLOCK_USER = "ad6_block_user";
   const OPTION_AD_DISABLE = "ad6_disabled";
   const OPTION_AD_BLOCK_CAT = "ad6_block_cat";

  //constructor
    public function Ad6() {

      $this->option_ad_name = self::OPTION_AD_NAME;
      $this->option_append_type = self::OPTION_APPEND_TYPE;
      $this->option_paragraph_number = self::OPTION_PARAGRAPH_NUMBER;
      $this->option_direction_type = self::OPTION_DIRECTION_TYPE;
      $this->option_float_type = self::OPTION_FLOAT_TYPE;
      $this->option_ad_data = self::OPTION_AD_DATA;
      $this->option_ad_general_tag = self::OPTION_AD_GENERAL_TAG;
      $this->option_ad_after_day = self::OPTION_AD_AFTER_DAY;
      $this->option_ad_block_user = self::OPTION_AD_BLOCK_USER;
      $this->option_ad_disabled = self::OPTION_AD_DISABLE;
      $this->option_ad_block_cat = self::OPTION_AD_BLOCK_CAT;

      parent::BaseAdInserter();
      $this->wp_options[$this->option_ad_name] = AD_NAME." 6";
      $this->wp_options[$this->option_ad_disabled] = AD_DISABLED_6;
    }

}

class Ad7 extends BaseAdInserter{

   const OPTION_AD_NAME = "ad7_name";
   const OPTION_APPEND_TYPE = "ad7_displayType";
   const OPTION_PARAGRAPH_NUMBER = "ad7_paragraphNumber";
   const OPTION_DIRECTION_TYPE = "ad7_directionType";
   const OPTION_FLOAT_TYPE = "ad7_floatType";
   const OPTION_AD_DATA = "ad7_data";
   const OPTION_AD_GENERAL_TAG = "ad7_general_tag";
   const OPTION_AD_AFTER_DAY = "ad7_after_day";
   const OPTION_AD_BLOCK_USER = "ad7_block_user";
   const OPTION_AD_DISABLE = "ad7_disabled";
   const OPTION_AD_BLOCK_CAT = "ad7_block_cat";

  //constructor
    public function Ad7() {

      $this->option_ad_name = self::OPTION_AD_NAME;
      $this->option_append_type = self::OPTION_APPEND_TYPE;
      $this->option_paragraph_number = self::OPTION_PARAGRAPH_NUMBER;
      $this->option_direction_type = self::OPTION_DIRECTION_TYPE;
      $this->option_float_type = self::OPTION_FLOAT_TYPE;
      $this->option_ad_data = self::OPTION_AD_DATA;
      $this->option_ad_general_tag = self::OPTION_AD_GENERAL_TAG;
      $this->option_ad_after_day = self::OPTION_AD_AFTER_DAY;
      $this->option_ad_block_user = self::OPTION_AD_BLOCK_USER;
      $this->option_ad_disabled = self::OPTION_AD_DISABLE;
      $this->option_ad_block_cat = self::OPTION_AD_BLOCK_CAT;

      parent::BaseAdInserter();
      $this->wp_options[$this->option_ad_name] = AD_NAME." 7";
      $this->wp_options[$this->option_ad_disabled] = AD_DISABLED_7;
    }

}

class Ad8 extends BaseAdInserter{

   const OPTION_AD_NAME = "ad8_name";
   const OPTION_APPEND_TYPE = "ad8_displayType";
   const OPTION_PARAGRAPH_NUMBER = "ad8_paragraphNumber";
   const OPTION_DIRECTION_TYPE = "ad8_directionType";
   const OPTION_FLOAT_TYPE = "ad8_floatType";
   const OPTION_AD_DATA = "ad8_data";
   const OPTION_AD_GENERAL_TAG = "ad8_general_tag";
   const OPTION_AD_AFTER_DAY = "ad8_after_day";
   const OPTION_AD_BLOCK_USER = "ad8_block_user";
   const OPTION_AD_DISABLE = "ad8_disabled";
   const OPTION_AD_BLOCK_CAT = "ad8_block_cat";

  //constructor
    public function Ad8() {

      $this->option_ad_name = self::OPTION_AD_NAME;
      $this->option_append_type = self::OPTION_APPEND_TYPE;
      $this->option_paragraph_number = self::OPTION_PARAGRAPH_NUMBER;
      $this->option_direction_type = self::OPTION_DIRECTION_TYPE;
      $this->option_float_type = self::OPTION_FLOAT_TYPE;
      $this->option_ad_data = self::OPTION_AD_DATA;
      $this->option_ad_general_tag = self::OPTION_AD_GENERAL_TAG;
      $this->option_ad_after_day = self::OPTION_AD_AFTER_DAY;
      $this->option_ad_block_user = self::OPTION_AD_BLOCK_USER;
      $this->option_ad_disabled = self::OPTION_AD_DISABLE;
      $this->option_ad_block_cat = self::OPTION_AD_BLOCK_CAT;

      parent::BaseAdInserter();
      $this->wp_options[$this->option_ad_name] = AD_NAME." 8";
      $this->wp_options[$this->option_ad_disabled] = AD_DISABLED_8;
    }

}


?>
