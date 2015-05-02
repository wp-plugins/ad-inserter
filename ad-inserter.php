<?php
/*
Plugin Name: Ad Inserter
Version: 1.5.3
Description: A simple solution to insert any code into Wordpress. Simply enter any HTML, Javascript or PHP code and select where and how you want to display it.
Author: Spacetime
Author URI: http://igorfuna.com/
Plugin URI: http://igorfuna.com/software/web/ad-inserter-wordpress-plugin
*/

/*
TO DO
*/

/*
Change Log

Ad Inserter 1.5.3 - 25 April 2015
- Fixed Security Vulnerability: Plugin was vulnerable to a combination of CSRF/XSS attacks (credits to Kaustubh Padwad)
- Fixed bug: In some cases deprecated widgets warning reported errors
- Added support to white-list or black-list tags
- Added support for category slugs in category list
- Added support for relative paragraph positions
- Added support for individual code block exceptions on post/page editor page
- Added support for minimum number of words

Ad Inserter 1.5.2 - 15 March 2015
- Fixed bug: Widget titles might be displayed at wrong sidebar positions
- Change: Default code block CSS class name was changed from ad-inserter to code-block to prevent Ad Blockers from blocking Ad Inserter divs
- Added warning message if deprecated widgets are used
- Added support to display blocks on desktop + tablet and desktop + phone devices

Ad Inserter 1.5.1 - 3 March 2015
- Few fixes to solve plugin incompatibility issues
- Added support to disable all ads on specific page

Ad Inserter 1.5.0 - 2 March 2015
- Added support to display blocks on all, desktop or mobile devices
- Added support for new widgets API - one widget for all code blocks with multiple instances
- Added support to change wrapping code CSS class name
- Fixed bug: Display block N days after post is published was not working properly
- Fixed bug: Display block after paragraph in some cases was not working propery

Ad Inserter 1.4.1 - 29 December 2014
- Fixed bug: Code blocks configured as widgets were not displayed properly on widgets admin page

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
- Added support for minimum number of paragraphs
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
require_once 'includes/Mobile_Detect.php';


$detect = new ai_Mobile_Detect;

define ('AI_MOBILE',   $detect->isMobile ());
define ('AI_TABLET',   $detect->isTablet ());
define ('AI_PHONE',    AI_MOBILE && !AI_TABLET);
define ('AI_DESKTOP',  !AI_MOBILE);


//hooks
add_action ('admin_menu',       'ai_admin_menu_hook');
add_filter ('the_content',      'ai_content_hook', 99999);
add_filter ('the_excerpt',      'ai_excerpt_hook', 99999);
add_action ('loop_start',       'ai_loop_start_hook');
add_action ('init',             'ai_init_hook');
add_action ('admin_notices',    'ai_admin_notice_hook');
add_action ('wp_head',          'ai_wp_head_hook');
add_action ('wp_footer',        'ai_wp_footer_hook');
add_action ('widgets_init',     'ai_widgets_init_hook');
add_action ('add_meta_boxes',   'ai_add_meta_box');
add_action ('save_post',        'ai_save_meta_box_data');


function ai_init_hook() {

  // OLD WIDGETS - DEPRECATED
  for ($counter = 1; $counter <= 16; $counter ++) {
    $obj = new ai_Block ($counter);
    $obj->load_options ("AdInserter".$counter."Options");
    if($obj->get_append_type() == AD_SELECT_WIDGET){
      // register widget
//      $widget_options = array ('classname' => 'ad-inserter-widget', 'description' => "Ad Inserter code block ".$counter);
      $widget_options = array ('classname' => 'ad-inserter-widget', 'description' => "DEPRECATED - Use 'Ad Inserter' widget instead.");
      $widget_parameters = array ('block' => $counter);
      // Different callback functions because widgets that share callback functions don't get displayed
      wp_register_sidebar_widget ('ai_widget'.$counter, $obj->get_ad_name().' - DEPRECATED', 'ai_widget'.$counter, $widget_options, $widget_parameters);
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
    echo "<div class='updated'><strong>
      Notice: ".AD_INSERTER_TITLE." plugin was updated. New version can insert ads also on static pages.
      Please <a href=\"/wp-admin/options-general.php?page=ad-inserter.php\">check</a> if page display options for all ad slots are set properly.
      Make required changes and save ".AD_INSERTER_TITLE." settings to remove this notice.</strong></div>";
  }

  $sidebar_widgets = wp_get_sidebars_widgets();
  $sidebars_with_deprecated_widgets = array ();

  foreach ($sidebar_widgets as $sidebar_widget_index => $sidebar_widget) {
    if (is_array ($sidebar_widget))
      foreach ($sidebar_widget as $widget) {
        if (preg_match ("/ai_widget([\d]+)/", $widget)) {
          $sidebars_with_deprecated_widgets [$sidebar_widget_index] = $GLOBALS ['wp_registered_sidebars'][$sidebar_widget_index]['name'];
        }
      }
  }

  if (!empty ($sidebars_with_deprecated_widgets)) {
    echo "<div class='update-nag'><strong>Warning: You are using deprecated Ad Inserter widgets in the following sidebars: ",
    implode (", ", $sidebars_with_deprecated_widgets),
    ". Please replace them with the new 'Ad Inserter' code block widget. See <a href='https://wordpress.org/plugins/ad-inserter/faq/' target='_blank'>FAQ</a> for details.</strong></div>";
  }
}

function ai_add_meta_box() {

  $screens = array ('post', 'page');

  foreach ($screens as $screen) {
    add_meta_box(
      'adinserter_sectionid',
      'Ad Inserter Exceptions',
      'ai_meta_box_callback',
      $screen
    );
  }
}

function ai_meta_box_callback ($post) {

  // Add an nonce field so we can check for it later.
  wp_nonce_field ('adinserter_meta_box', 'adinserter_meta_box_nonce');

  $ad1  = new ai_Block (1);
  $ad2  = new ai_Block (2);
  $ad3  = new ai_Block (3);
  $ad4  = new ai_Block (4);
  $ad5  = new ai_Block (5);
  $ad6  = new ai_Block (6);
  $ad7  = new ai_Block (7);
  $ad8  = new ai_Block (8);
  $ad9  = new ai_Block (9);
  $ad10 = new ai_Block (10);
  $ad11 = new ai_Block (11);
  $ad12 = new ai_Block (12);
  $ad13 = new ai_Block (13);
  $ad14 = new ai_Block (14);
  $ad15 = new ai_Block (15);
  $ad16 = new ai_Block (16);

  $ad_array = array ($ad1,$ad2,$ad3,$ad4,$ad5,$ad6,$ad7,$ad8,$ad9,$ad10,$ad11,$ad12,$ad13,$ad14,$ad15,$ad16);

  $post_type = get_post_type ($post);

  /*
   * Use get_post_meta() to retrieve an existing value
   * from the database and use the value for the form.
   */
  $post_meta = get_post_meta ($post->ID, '_adinserter_block_exceptions', true);
  $selected_blocks = explode (",", $post_meta);

  echo '<table>';
  echo '<thead style="font-weight: bold;">';
    echo '  <td>Block</td>';
    echo '  <td style="padding: 0 10px 0 10px;">Name</td>';
    echo '  <td style="padding: 0 10px 0 10px;">Automatic Display Type</td>';
    echo '  <td style="padding: 0 5px 0 5px;">Posts</td>';
    echo '  <td style="padding: 0 5px 0 5px;">Pages</td>';
    echo '  <td style="padding: 0 5px 0 5px;">Manual</td>';
    echo '  <td style="padding: 0 5px 0 5px;">PHP</td>';
    echo '  <td style="padding: 0 10px 0 10px;">Default</td>';
    echo '  <td style="padding: 0 10px 0 10px;">For this ', $post_type, '</td>';
  echo '</thead>';
  echo '<tbody>';
  $rows = 0;
  foreach ($ad_array as $key => $obj){
    $obj->load_options ("AdInserter".($key + 1)."Options");

    if ($post_type == 'post') {
      $enabled_on_text  = $obj->get_ad_enabled_on_which_posts ();
      $general_enabled  = $obj->get_display_settings_post();
    } else {
        $enabled_on_text = $obj->get_ad_enabled_on_which_pages ();
        $general_enabled = $obj->get_display_settings_page();
      }

    $individual_option_enabled  = $general_enabled && ($enabled_on_text == AD_ENABLED_ON_ALL_EXCEPT_ON_SELECTED || $enabled_on_text == AD_ENABLED_ONLY_ON_SELECTED);
    $individual_text_enabled    = $enabled_on_text == AD_ENABLED_ON_ALL_EXCEPT_ON_SELECTED;

    $ad_number = $obj->number;
    $display_type = $obj->get_append_type();
    if ($rows % 2 != 0) $background = "#F0F0F0"; else $background = "#FFF";
    echo '<tr style="background: ', $background, ';">';
    echo '  <td style="text-align: right;">', $obj->number, '</td>';
    echo '  <td style="padding: 0 10px 0 10px;">', $obj->get_ad_name(), '</td>';
    echo '  <td style="padding: 0 10px 0 10px;">', $display_type, '</td>';

    echo '  <td style="padding: 0 10px 0 10px; text-align: center;">';
    if ($obj->get_display_settings_post ()) echo '&check;';
    echo '  </td>';
    echo '  <td style="padding: 0 10px 0 10px; text-align: center;">';
    if ($obj->get_display_settings_page ()) echo '&check;';
    echo '  </td>';
    echo '  <td style="padding: 0 10px 0 10px; text-align: center;">';
    if ($obj->get_enable_manual ()) echo '&check;';
    echo '  </td>';
    echo '  <td style="padding: 0 10px 0 10px; text-align: center;">';
    if ($obj->get_enable_php_call ()) echo '&check;';
    echo '  </td>';

    echo '  <td style="padding: 0 10px 0 10px; text-align: left;">';

    if ($individual_option_enabled) {
      if ($individual_text_enabled) echo 'Enabled'; else echo 'Disabled';
    } else {
        if ($general_enabled) echo 'Enabled on all ', $post_type, 's'; else
          echo 'Disabled on all ', $post_type, 's';
      }
    echo '  </td>';

    echo '  <td style="padding: 0 10px 0 10px; text-align: left;">';
    echo '<input type="hidden"   style="border-radius: 5px;" name="adinserter_selected_block_', $ad_number, '" value="0" />';
    echo '<input type="', $individual_option_enabled ? 'checkbox' : 'hidden', '" style="border-radius: 5px;" name="adinserter_selected_block_', $ad_number, '" value="1"';
    if (in_array ($ad_number, $selected_blocks)) echo 'checked ';
    echo '>';

    echo '<label for="adinserter_selected_block_', $ad_number, '">';
    if ($individual_option_enabled) {
      if (!$individual_text_enabled) echo 'Enabled'; else echo 'Disabled';
    }
    echo '</label>';
    echo '  </td>';

    echo '</tr>';
    $rows ++;
  }

  echo '</tbody>';
  echo '</table>';

  echo '<p>Default behavior for all code blocks for ', $post_type, 's (enabled or disabled) can be configured on <a href="/wp-admin/options-general.php?page=ad-inserter.php" target="_blank">Ad Inserter Settings page</a>. Here you can configure exceptions for this ', $post_type, '.</p>';
}

function ai_save_meta_box_data ($post_id) {

  // Check if our nonce is set.
  if (!isset ($_POST ['adinserter_meta_box_nonce'])) return;

  // Verify that the nonce is valid.
  if (!wp_verify_nonce ($_POST ['adinserter_meta_box_nonce'], 'adinserter_meta_box')) return;

  // If this is an autosave, our form has not been submitted, so we don't want to do anything.
  if (defined ('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

  // Check the user's permissions.
  if (isset ($_POST ['post_type']) && 'page' == $_POST ['post_type']) {
    if (!current_user_can ('edit_page', $post_id)) return;
  } else {
    if (!current_user_can ('edit_post', $post_id)) return;
  }

  /* OK, it's safe for us to save the data now. */

  $selected = array ();
  for ($block = 1; $block <= 16; $block ++) {
    $option_name = 'adinserter_selected_block_' . $block;
    if (!isset ($_POST [$option_name])) return;
    if ($_POST [$option_name]) $selected []= $block;
  }

  // Update the meta field in the database.
  update_post_meta ($post_id, '_adinserter_block_exceptions', implode (",", $selected));
}

function ai_widgets_init_hook () {
  register_widget ('ai_widget');
}

function ai_wp_head_hook () {
  $obj = new ai_AdH();
  $obj->load_options (AD_HEADER_OPTIONS);

  if ($obj->get_enable_manual ()) {
    echo ai_getCode ($obj);
  }
}

function ai_wp_footer_hook () {
  $obj = new ai_AdF();
  $obj->load_options (AD_FOOTER_OPTIONS);

  if ($obj->get_enable_manual ()) {
    echo ai_getCode ($obj);
  }
}

function ai_plugin_options ($syntax_highlighter_theme = DEFAULT_SYNTAX_HIGHLIGHTER_THEME, $block_class_name = DEFAULT_BLOCK_CLASS_NAME){
  $plugin_options = array ();

  $version_array = explode (".", AD_INSERTER_VERSION);
  $version_string = "";
  foreach ($version_array as $number) {
    $version_string .= sprintf ("%02d", $number);
  }

  $plugin_options ['VERSION'] = $version_string;

  $plugin_options ['SYNTAX_HIGHLIGHTER_THEME']  = $syntax_highlighter_theme;
  $plugin_options ['BLOCK_CLASS_NAME']          = $block_class_name;

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
    $plugin_db_options ['SYNTAX_HIGHLIGHTER_THEME'] = DEFAULT_SYNTAX_HIGHLIGHTER_THEME;
  }

  return ($plugin_db_options ['SYNTAX_HIGHLIGHTER_THEME']);
}

function get_block_class_name () {
  $plugin_db_options = get_option (AD_OPTIONS);

  if (!isset ($plugin_db_options ['BLOCK_CLASS_NAME']) || $plugin_db_options ['BLOCK_CLASS_NAME'] == '') {
    $plugin_db_options ['BLOCK_CLASS_NAME'] = DEFAULT_BLOCK_CLASS_NAME;
  }

  return ($plugin_db_options ['BLOCK_CLASS_NAME']);
}

function filter_html_class ($str){

  $str = str_replace (array ("\\\""), array ("\""), $str);
  $str = sanitize_html_class ($str);

  return $str;
}

function filter_string ($str){

  $str = str_replace (array ("\\\""), array ("\""), $str);
  $str = str_replace (array ("\"", "<", ">"), "", $str);
  $str = esc_html ($str);

  return $str;
}

function filter_option ($ad, $option, $value){
  $value = str_replace (array ("\\\""), array ("\""), $value);

  if ($option == $ad->option_ad_name ||
      $option == $ad->option_ad_general_tag ||
      $option == $ad->option_ad_domain_list ||
      $option == $ad->option_ad_category_list ||
      $option == $ad->option_ad_tag_list ||
      $option == $ad->option_paragraph_text ||
      $option == $ad->option_paragraph_number ||
      $option == $ad->option_paragraph_number_minimum ||
      $option == $ad->option_ad_after_day ||
      $option == $ad->option_excerpt_number ||
      $option == $ad->option_custom_css) {
        $value = str_replace (array ("\"", "<", ">"), "", $value);
        $value = esc_html ($value);
      }

  return $value;
}

function filter_option_hf ($option, $value){
  $value = str_replace (array ("\\\""), array ("\""), $value);

        if ($option == "adH_data"        || $option == "adF_data") {
  } elseif ($option == "adH_enable"      || $option == "adF_enable") {
  } elseif ($option == "adH_process_php" || $option == "adF_process_php") {
  }

  return $value;
}

function ai_menu () {

  if (isset ($_POST [AD_FORM_SAVE])) {

    check_admin_referer ('save_adinserter_settings');

    $ad1  = new ai_Block (1);
    $ad2  = new ai_Block (2);
    $ad3  = new ai_Block (3);
    $ad4  = new ai_Block (4);
    $ad5  = new ai_Block (5);
    $ad6  = new ai_Block (6);
    $ad7  = new ai_Block (7);
    $ad8  = new ai_Block (8);
    $ad9  = new ai_Block (9);
    $ad10 = new ai_Block (10);
    $ad11 = new ai_Block (11);
    $ad12 = new ai_Block (12);
    $ad13 = new ai_Block (13);
    $ad14 = new ai_Block (14);
    $ad15 = new ai_Block (15);
    $ad16 = new ai_Block (16);

    $adH  = new ai_AdH();
    $adF  = new ai_AdF();

     foreach(array_keys($ad1->wp_options) as $key){
        if(isset($_POST[$key])){
            $ad1->wp_options[$key] = filter_option ($ad1, $key, $_POST[$key]);
        }
      }

      foreach(array_keys($ad2->wp_options) as $key){
        if(isset($_POST[$key])){
            $ad2->wp_options[$key] = filter_option ($ad2, $key, $_POST[$key]);
        }
      }

      foreach(array_keys($ad3->wp_options) as $key){
        if(isset($_POST[$key])){
            $ad3->wp_options[$key] = filter_option ($ad3, $key, $_POST[$key]);
        }
      }

      foreach(array_keys($ad4->wp_options) as $key){
        if(isset($_POST[$key])){
            $ad4->wp_options[$key] = filter_option ($ad4, $key, $_POST[$key]);
        }
      }

     foreach(array_keys($ad5->wp_options) as $key){
        if(isset($_POST[$key])){
            $ad5->wp_options[$key] = filter_option ($ad5, $key, $_POST[$key]);
        }
      }

      foreach(array_keys($ad6->wp_options) as $key){
        if(isset($_POST[$key])){
            $ad6->wp_options[$key] = filter_option ($ad6, $key, $_POST[$key]);
        }
      }

      foreach(array_keys($ad7->wp_options) as $key){
        if(isset($_POST[$key])){
            $ad7->wp_options[$key] = filter_option ($ad7, $key, $_POST[$key]);
        }
      }

      foreach(array_keys($ad8->wp_options) as $key){
        if(isset($_POST[$key])){
            $ad8->wp_options[$key] = filter_option ($ad8, $key, $_POST[$key]);
        }
      }

     foreach(array_keys($ad9->wp_options) as $key){
        if(isset($_POST[$key])){
            $ad9->wp_options[$key] = filter_option ($ad9, $key, $_POST[$key]);
        }
      }

      foreach(array_keys($ad10->wp_options) as $key){
        if(isset($_POST[$key])){
            $ad10->wp_options[$key] = filter_option ($ad10, $key, $_POST[$key]);
        }
      }

      foreach(array_keys($ad11->wp_options) as $key){
        if(isset($_POST[$key])){
            $ad11->wp_options[$key] = filter_option ($ad11, $key, $_POST[$key]);
        }
      }

      foreach(array_keys($ad12->wp_options) as $key){
        if(isset($_POST[$key])){
            $ad12->wp_options[$key] = filter_option ($ad12, $key, $_POST[$key]);
        }
      }

     foreach(array_keys($ad13->wp_options) as $key){
        if(isset($_POST[$key])){
            $ad13->wp_options[$key] = filter_option ($ad13, $key, $_POST[$key]);
        }
      }

      foreach(array_keys($ad14->wp_options) as $key){
        if(isset($_POST[$key])){
            $ad14->wp_options[$key] = filter_option ($ad14, $key, $_POST[$key]);
        }
      }

      foreach(array_keys($ad15->wp_options) as $key){
        if(isset($_POST[$key])){
            $ad15->wp_options[$key] = filter_option ($ad15, $key, $_POST[$key]);
        }
      }

      foreach(array_keys($ad16->wp_options) as $key){
        if(isset($_POST[$key])){
            $ad16->wp_options[$key] = filter_option ($ad16, $key, $_POST[$key]);
        }
      }

      foreach(array_keys($adH->wp_options) as $key){
        if(isset($_POST[$key])){
            $adH->wp_options[$key] = filter_option_hf ($key, $_POST[$key]);
        }
      }

      foreach(array_keys($adF->wp_options) as $key){
        if(isset($_POST[$key])){
            $adF->wp_options[$key] = filter_option_hf ($key, $_POST[$key]);
        }
      }

      update_option (AD_OPTIONS, ai_plugin_options (filter_string ($_POST ['syntax-highlighter-theme']), filter_html_class ($_POST ['block-class-name'])));

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

    echo "<div class='updated' style='margin: 5px 15px 2px 0px; padding: 10px;'><strong>Settings saved.</strong></div>";

  } elseif (isset ($_POST [AD_FORM_CLEAR])) {

      check_admin_referer ('save_adinserter_settings');

      $ad1  = new ai_Block (1);
      $ad2  = new ai_Block (2);
      $ad3  = new ai_Block (3);
      $ad4  = new ai_Block (4);
      $ad5  = new ai_Block (5);
      $ad6  = new ai_Block (6);
      $ad7  = new ai_Block (7);
      $ad8  = new ai_Block (8);
      $ad9  = new ai_Block (9);
      $ad10 = new ai_Block (10);
      $ad11 = new ai_Block (11);
      $ad12 = new ai_Block (12);
      $ad13 = new ai_Block (13);
      $ad14 = new ai_Block (14);
      $ad15 = new ai_Block (15);
      $ad16 = new ai_Block (16);

      $adH  = new ai_AdH();
      $adF  = new ai_AdF();

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

      echo "<div class='error' style='margin: 5px 15px 2px 0px; padding: 10px;'>Settings cleared.</div>";
  }

  print_settings_form ();
}


function adinserter ($ad_number = ""){
  if ($ad_number == "") return "";

  $ad1  = new ai_Block (1);
  $ad2  = new ai_Block (2);
  $ad3  = new ai_Block (3);
  $ad4  = new ai_Block (4);
  $ad5  = new ai_Block (5);
  $ad6  = new ai_Block (6);
  $ad7  = new ai_Block (7);
  $ad8  = new ai_Block (8);
  $ad9  = new ai_Block (9);
  $ad10 = new ai_Block (10);
  $ad11 = new ai_Block (11);
  $ad12 = new ai_Block (12);
  $ad13 = new ai_Block (13);
  $ad14 = new ai_Block (14);
  $ad15 = new ai_Block (15);
  $ad16 = new ai_Block (16);

  $ad_all_data = array ($ad1,$ad2,$ad3,$ad4,$ad5,$ad6,$ad7,$ad8,$ad9,$ad10,$ad11,$ad12,$ad13,$ad14,$ad15,$ad16);

  if (!is_numeric ($ad_number)) return "";

  $ad_number = (int) $ad_number;

  if ($ad_number < 1 || $ad_number > 16) return "";

  // Load options from db
  $obj = $ad_all_data [$ad_number - 1];
  $obj->load_options ("AdInserter".$ad_number."Options");

  $display_for_users = $obj->get_display_for_users ();

  if ($display_for_users == AD_DISPLAY_LOGGED_IN_USERS && !is_user_logged_in ()) return "";
  if ($display_for_users == AD_DISPLAY_NOT_LOGGED_IN_USERS && is_user_logged_in ()) return "";

  $display_for_devices = $obj->get_display_for_devices ();

  if ($display_for_devices == AD_DISPLAY_DESKTOP_DEVICES && !AI_DESKTOP) return "";
  if ($display_for_devices == AD_DISPLAY_MOBILE_DEVICES && !AI_MOBILE) return "";
  if ($display_for_devices == AD_DISPLAY_TABLET_DEVICES && !AI_TABLET) return "";
  if ($display_for_devices == AD_DISPLAY_PHONE_DEVICES && !AI_PHONE) return "";
  if ($display_for_devices == AD_DISPLAY_DESKTOP_TABLET_DEVICES && !(AI_DESKTOP || AI_TABLET)) return "";
  if ($display_for_devices == AD_DISPLAY_DESKTOP_PHONE_DEVICES && !(AI_DESKTOP || AI_PHONE)) return "";

  if (!$obj->get_enable_php_call ()) return "";

  if (is_front_page ()){
    if (!$obj->get_display_settings_home()) return "";
  } else  if (is_single ()) {
    if (!$obj->get_display_settings_post ()) return "";

    $meta_value = get_post_meta (get_the_ID (), '_adinserter_block_exceptions', true);
    $selected_blocks = explode (",", $meta_value);

    $enabled_on_text = $obj->get_ad_enabled_on_which_posts ();
    if ($enabled_on_text == AD_ENABLED_ON_ALL_EXCEPT_ON_SELECTED) {
      if (in_array ($obj->number, $selected_blocks)) return "";
    }
    elseif ($enabled_on_text == AD_ENABLED_ONLY_ON_SELECTED) {
      if (!in_array ($obj->number, $selected_blocks)) return "";
    }
  } elseif (is_page ()) {
    if (!$obj->get_display_settings_page ()) return "";

    $meta_value = get_post_meta (get_the_ID (), '_adinserter_block_exceptions', true);
    $selected_blocks = explode (",", $meta_value);

    $enabled_on_text = $obj->get_ad_enabled_on_which_pages ();
    if ($enabled_on_text == AD_ENABLED_ON_ALL_EXCEPT_ON_SELECTED) {
      if (in_array ($obj->number, $selected_blocks)) return "";
    }
    elseif ($enabled_on_text == AD_ENABLED_ONLY_ON_SELECTED) {
      if (!in_array ($obj->number, $selected_blocks)) return "";
    }
  } elseif (is_category()){
    if (!$obj->get_display_settings_category()) return "";
  } elseif (is_search()){
    if (!$obj->get_display_settings_search()) return "";
  } elseif (is_archive()){
    if (!$obj->get_display_settings_archive()) return "";
  }

  //get post published date
  $publish_date = get_the_date ('U');

  //get referer
  $http_referer = '';
  if (isset ($_SERVER['HTTP_REFERER'])) {
      $http_referer = $_SERVER['HTTP_REFERER'];
  }

  if ($obj->get_ad_data() == AD_EMPTY_DATA) return "";

  if (ai_isCategoryAllowed ($obj->get_ad_block_cat(), $obj->get_ad_block_cat_type()) == false) return "";

  if (ai_isTagAllowed ($obj->get_ad_block_tag(), $obj->get_ad_block_tag_type()) == false) return "";

  if (ai_isDisplayDateAllowed ($obj, $publish_date) == false) return "";

  if (ai_isRefererAllowed ($obj, $http_referer, $obj->get_ad_domain_list_type()) == false) return "";

  $block_class_name = get_block_class_name ();

  if ($obj->get_alignment_type() == AD_ALIGNMENT_NO_WRAPPING) $ad_code = ai_getAdCode ($obj); else
    $ad_code = "<div class='" . $block_class_name . " " . $block_class_name . "-" . $ad_number."' style='" . $obj->get_alignmet_style() . "'>" . ai_getAdCode ($obj) . "</div>";

  return $ad_code;
}


function ai_content_hook ($content = ''){

  $ad1  = new ai_Block (1);
  $ad2  = new ai_Block (2);
  $ad3  = new ai_Block (3);
  $ad4  = new ai_Block (4);
  $ad5  = new ai_Block (5);
  $ad6  = new ai_Block (6);
  $ad7  = new ai_Block (7);
  $ad8  = new ai_Block (8);
  $ad9  = new ai_Block (9);
  $ad10 = new ai_Block (10);
  $ad11 = new ai_Block (11);
  $ad12 = new ai_Block (12);
  $ad13 = new ai_Block (13);
  $ad14 = new ai_Block (14);
  $ad15 = new ai_Block (15);
  $ad16 = new ai_Block (16);

  $ad_all_data = array ($ad1,$ad2,$ad3,$ad4,$ad5,$ad6,$ad7,$ad8,$ad9,$ad10,$ad11,$ad12,$ad13,$ad14,$ad15,$ad16);

  // Load options from db
  foreach ($ad_all_data as $key => $obj){
    $obj->load_options ("AdInserter".($key + 1)."Options");
  }

  //get post published date
  $publish_date = get_the_date ('U');

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

  $ad1  = new ai_Block (1);
  $ad2  = new ai_Block (2);
  $ad3  = new ai_Block (3);
  $ad4  = new ai_Block (4);
  $ad5  = new ai_Block (5);
  $ad6  = new ai_Block (6);
  $ad7  = new ai_Block (7);
  $ad8  = new ai_Block (8);
  $ad9  = new ai_Block (9);
  $ad10 = new ai_Block (10);
  $ad11 = new ai_Block (11);
  $ad12 = new ai_Block (12);
  $ad13 = new ai_Block (13);
  $ad14 = new ai_Block (14);
  $ad15 = new ai_Block (15);
  $ad16 = new ai_Block (16);

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

    $display_for_users = $obj->get_display_for_users ();

    if ($display_for_users == AD_DISPLAY_LOGGED_IN_USERS && !is_user_logged_in ()) continue;
    if ($display_for_users == AD_DISPLAY_NOT_LOGGED_IN_USERS && is_user_logged_in ()) continue;

    $display_for_devices = $obj->get_display_for_devices ();

    if ($display_for_devices == AD_DISPLAY_DESKTOP_DEVICES && !AI_DESKTOP) continue;
    if ($display_for_devices == AD_DISPLAY_MOBILE_DEVICES && !AI_MOBILE) continue;
    if ($display_for_devices == AD_DISPLAY_TABLET_DEVICES && !AI_TABLET) continue;
    if ($display_for_devices == AD_DISPLAY_PHONE_DEVICES && !AI_PHONE) continue;
    if ($display_for_devices == AD_DISPLAY_DESKTOP_TABLET_DEVICES && !(AI_DESKTOP || AI_TABLET)) continue;
    if ($display_for_devices == AD_DISPLAY_DESKTOP_PHONE_DEVICES && !(AI_DESKTOP || AI_PHONE)) continue;

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

    if (ai_isCategoryAllowed ($obj->get_ad_block_cat(), $obj->get_ad_block_cat_type()) == false) {
      continue;
    }

    if (ai_isTagAllowed ($obj->get_ad_block_tag(), $obj->get_ad_block_tag_type()) == false) {
      continue;
    }

    if (ai_isRefererAllowed ($obj, $http_referer, $obj->get_ad_domain_list_type()) == false){
      continue;
    }

    $block_class_name = get_block_class_name ();

    if ($obj->get_alignment_type() == AD_ALIGNMENT_NO_WRAPPING) $ad_code .= ai_getAdCode ($obj); else
      $ad_code .= "<div class='" . $block_class_name . " " . $block_class_name . "-" .($block_index + 1)."' style='" . $obj->get_alignmet_style() . "'>" . ai_getAdCode ($obj) . "</div>";

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

  $ad1  = new ai_Block (1);
  $ad2  = new ai_Block (2);
  $ad3  = new ai_Block (3);
  $ad4  = new ai_Block (4);
  $ad5  = new ai_Block (5);
  $ad6  = new ai_Block (6);
  $ad7  = new ai_Block (7);
  $ad8  = new ai_Block (8);
  $ad9  = new ai_Block (9);
  $ad10 = new ai_Block (10);
  $ad11 = new ai_Block (11);
  $ad12 = new ai_Block (12);
  $ad13 = new ai_Block (13);
  $ad14 = new ai_Block (14);
  $ad15 = new ai_Block (15);
  $ad16 = new ai_Block (16);

  $ad_all_data = array ($ad1,$ad2,$ad3,$ad4,$ad5,$ad6,$ad7,$ad8,$ad9,$ad10,$ad11,$ad12,$ad13,$ad14,$ad15,$ad16);

  // Load options from db
  foreach($ad_all_data as $key => $obj){
    $obj->load_options ("AdInserter".($key + 1)."Options");
  }

  //get post published date
  $publish_date = get_the_date ('U');

  //get referer
  $http_referer = '';
  if(isset($_SERVER['HTTP_REFERER'])) {
     $http_referer = $_SERVER['HTTP_REFERER'];
  }

  $meta_value = get_post_meta (get_the_ID (), '_adinserter_block_exceptions', true);
  $selected_blocks = explode (",", $meta_value);

  $ad_code = "";

  foreach ($ad_all_data as $block_index => $obj){

    $display_for_users = $obj->get_display_for_users ();

    if ($display_for_users == AD_DISPLAY_LOGGED_IN_USERS && !is_user_logged_in ()) continue;
    if ($display_for_users == AD_DISPLAY_NOT_LOGGED_IN_USERS && is_user_logged_in ()) continue;

    $display_for_devices = $obj->get_display_for_devices ();

    if ($display_for_devices == AD_DISPLAY_DESKTOP_DEVICES && !AI_DESKTOP) continue;
    if ($display_for_devices == AD_DISPLAY_MOBILE_DEVICES && !AI_MOBILE) continue;
    if ($display_for_devices == AD_DISPLAY_TABLET_DEVICES && !AI_TABLET) continue;
    if ($display_for_devices == AD_DISPLAY_PHONE_DEVICES && !AI_PHONE) continue;
    if ($display_for_devices == AD_DISPLAY_DESKTOP_TABLET_DEVICES && !(AI_DESKTOP || AI_TABLET)) continue;
    if ($display_for_devices == AD_DISPLAY_DESKTOP_PHONE_DEVICES && !(AI_DESKTOP || AI_PHONE)) continue;

    if ($obj->get_append_type () != AD_SELECT_BEFORE_TITLE) continue;

    if (is_front_page ()){
      if (!$obj->get_display_settings_home()) continue;
    }
    elseif (is_page()){
      if (!$obj->get_display_settings_page()) continue;

      $enabled_on_text = $obj->get_ad_enabled_on_which_pages ();
      if ($enabled_on_text == AD_ENABLED_ON_ALL_EXCEPT_ON_SELECTED) {
        if (in_array ($obj->number, $selected_blocks)) continue;
      }
      elseif ($enabled_on_text == AD_ENABLED_ONLY_ON_SELECTED) {
        if (!in_array ($obj->number, $selected_blocks)) continue;
      }
    }
    elseif (is_single()){
      if (!$obj->get_display_settings_post()) continue;

      $enabled_on_text = $obj->get_ad_enabled_on_which_posts ();
      if ($enabled_on_text == AD_ENABLED_ON_ALL_EXCEPT_ON_SELECTED) {
        if (in_array ($obj->number, $selected_blocks)) continue;
      }
      elseif ($enabled_on_text == AD_ENABLED_ONLY_ON_SELECTED) {
        if (!in_array ($obj->number, $selected_blocks)) continue;
      }
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

    if (ai_isTagAllowed ($obj->get_ad_block_tag(), $obj->get_ad_block_tag_type())==false){
      continue;
    }

    if (ai_isDisplayDateAllowed ($obj, $publish_date)==false){
      continue;
    }

    if (ai_isRefererAllowed ($obj, $http_referer, $obj->get_ad_domain_list_type()) == false){
      continue;
    }

    $block_class_name = get_block_class_name ();

    if ($obj->get_alignment_type() == AD_ALIGNMENT_NO_WRAPPING) $ad_code .= ai_getAdCode ($obj); else
      $ad_code .= "<div class='" . $block_class_name . " " . $block_class_name . "-" . ($block_index + 1)."' style='" . $obj->get_alignmet_style() . "'>" . ai_getAdCode ($obj) . "</div>";
  }

  echo $ad_code;
}

function ai_isCategoryAllowed ($categories, $cat_type){

  $categories = trim (strtolower ($categories));

//  echo ' listed categories: ' . $categories, "<br />\n";

  if ($cat_type == AD_BLACK_LIST) {

    if($categories == AD_EMPTY_DATA) {
      return true;
    }

    $cats_listed = explode (",", $categories);

    foreach((get_the_category()) as $post_category) {

      //echo '<br/> post category name : ' . $post_category->cat_name;

      foreach($cats_listed as $cat_disabled){

        $cat_disabled = trim ($cat_disabled);

        $post_category_name = strtolower ($post_category->cat_name);
        $post_category_slug = strtolower ($post_category->slug);

        //echo '<br/>Category disabled loop : ' . $cat_disabled . '<br/> category name : ' . $post_category_name;

        if ($post_category_name == $cat_disabled || $post_category_slug == $cat_disabled) {
          //echo ' match';
          return false;
        }else{
          //echo ' not match';
        }
      }
    }
    return true;

  } else {

      if ($categories == AD_EMPTY_DATA){
        return false;
      }

      $cats_listed = explode (",", $categories);

      foreach((get_the_category()) as $post_category) {

        //echo '<br/> post category name : ' . $post_category->cat_name;

        foreach($cats_listed as $cat_enabled){

          $cat_enabled = trim ($cat_enabled);

          $post_category_name = strtolower ($post_category->cat_name);
          $post_category_slug = strtolower ($post_category->slug);

//          echo '<br/>Category enabled loop : ' . $cat_enabled . '<br/> category name : ' . $post_category_name . '<br/> category slug: ' . $post_category_slug;

          if ($post_category_name == $cat_enabled || $post_category_slug == $cat_enabled) {
//            echo '#match';
            return true;
          }else{
//            echo '#no match';
          }
        }
      }
      return false;
    }
}

function ai_isTagAllowed ($tags, $tag_type){

  $tags = trim (strtolower ($tags));
  $tags_listed = explode (",", $tags);
  foreach ($tags_listed as $index => $tag_listed) {
    $tags_listed [$index] = trim ($tag_listed);
  }
  $has_any_of_the_given_tags = has_tag ($tags_listed);

//  echo ' listed tags: ' . $tags, "\n";

  if ($tag_type == AD_BLACK_LIST) {

    if ($tags == AD_EMPTY_DATA) {
      return true;
    }

    if (is_tag()) {
      foreach ($tags_listed as $tag_listed) {
        if (is_tag ($tag_listed)) return false;
      }
      return true;
    }

    return !$has_any_of_the_given_tags;

  } else {

      if ($tags == AD_EMPTY_DATA){
        return false;
      }

      if (is_tag()) {
        foreach ($tags_listed as $tag_listed) {
          if (is_tag ($tag_listed)) return true;
        }
        return false;
      }

      return $has_any_of_the_given_tags;
    }
}

function ai_isDisplayDisabled ($obj, $content){

  $ad_name = $obj->get_ad_name();

  if (preg_match ("/<!-- +Ad +Inserter +Ad +".($obj->number)." +Disabled +-->/i", $content)) return true;

  if (preg_match ("/<!-- +disable +adinserter +\* +-->/i", $content)) return true;

  if (preg_match ("/<!-- +disable +adinserter +".($obj->number)." +-->/i", $content)) return true;

//  if (preg_match ("/<!-- +disable +adinserter +".(trim ($obj->get_ad_name()))." +-->/i", $content)) return true;
  if (strpos ($content, "<!-- disable adinserter " . $ad_name . " -->") != false) return true;

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

    $display_for_users = $obj->get_display_for_users ();

    if ($display_for_users == AD_DISPLAY_LOGGED_IN_USERS && !is_user_logged_in ()) continue;
    if ($display_for_users == AD_DISPLAY_NOT_LOGGED_IN_USERS && is_user_logged_in ()) continue;

    $display_for_devices = $obj->get_display_for_devices ();

    if ($display_for_devices == AD_DISPLAY_DESKTOP_DEVICES && !AI_DESKTOP) continue;
    if ($display_for_devices == AD_DISPLAY_MOBILE_DEVICES && !AI_MOBILE) continue;
    if ($display_for_devices == AD_DISPLAY_TABLET_DEVICES && !AI_TABLET) continue;
    if ($display_for_devices == AD_DISPLAY_PHONE_DEVICES && !AI_PHONE) continue;
    if ($display_for_devices == AD_DISPLAY_DESKTOP_TABLET_DEVICES && !(AI_DESKTOP || AI_TABLET)) continue;
    if ($display_for_devices == AD_DISPLAY_DESKTOP_PHONE_DEVICES && !(AI_DESKTOP || AI_PHONE)) continue;

    //if empty data, continue next
    if ($obj->get_ad_data() == AD_EMPTY_DATA) {
      continue;
    }

    if (is_single ()) {
      if (!$obj->get_display_settings_post ()) continue;

      $meta_value = get_post_meta (get_the_ID (), '_adinserter_block_exceptions', true);
      $selected_blocks = explode (",", $meta_value);

      $enabled_on_text = $obj->get_ad_enabled_on_which_posts ();
      if ($enabled_on_text == AD_ENABLED_ON_ALL_EXCEPT_ON_SELECTED) {
        if (in_array ($obj->number, $selected_blocks)) continue;
      }
      elseif ($enabled_on_text == AD_ENABLED_ONLY_ON_SELECTED) {
        if (!in_array ($obj->number, $selected_blocks)) continue;
      }
    } elseif (is_page ()) {
      if (!$obj->get_display_settings_page ()) continue;

      $meta_value = get_post_meta (get_the_ID (), '_adinserter_block_exceptions', true);
      $selected_blocks = explode (",", $meta_value);

      $enabled_on_text = $obj->get_ad_enabled_on_which_pages ();
      if ($enabled_on_text == AD_ENABLED_ON_ALL_EXCEPT_ON_SELECTED) {
        if (in_array ($obj->number, $selected_blocks)) continue;
      }
      elseif ($enabled_on_text == AD_ENABLED_ONLY_ON_SELECTED) {
        if (!in_array ($obj->number, $selected_blocks)) continue;
      }
    } else continue;

    if (ai_isDisplayDisabled ($obj, $content)) {
      continue;
    }

    if (ai_isCategoryAllowed ($obj->get_ad_block_cat(), $obj->get_ad_block_cat_type()) == false) {
      continue;
    }

    if (ai_isTagAllowed ($obj->get_ad_block_tag(), $obj->get_ad_block_tag_type()) == false) {
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
      $content = ai_generateAfterParagraph ($index + 1, $content, $obj);
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


function ai_generateBeforeParagraph ($block, $content, $obj){

  $paragraph_positions = array ();
  $poseslast = array ();
  $last_position = - 1;

  $paragraph_start = "<p";

  while (stripos ($content, $paragraph_start, $last_position + 1) !== false){
    $last_position = stripos ($content, $paragraph_start, $last_position + 1);
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

  $position = $obj->get_paragraph_number();

  if ($position > 0 && $position < 1) {
    $position = intval ($position * sizeof ($paragraph_positions) + 0.5);
  }

  if ($position <= 0) {
    $position = rand (0, sizeof ($paragraph_positions) - 1);
  } elseif ($obj->get_direction_type() == AD_DIRECTION_FROM_BOTTOM) {
    $paragraph_positions = array_reverse ($paragraph_positions);
    $position --;
  } else $position --;

  $text = str_replace (array ("\n", "  "), " ", $content);
  $text = strip_tags ($text);
  $number_of_words = sizeof (explode (" ", $text));

  if (sizeof ($paragraph_positions) >= $obj->get_paragraph_number_minimum() && $number_of_words >= $obj->get_minimum_words()) {
    if (sizeof ($paragraph_positions) > $position) {
      $content_position = $paragraph_positions [$position];

      $block_class_name = get_block_class_name ();

      if ($obj->get_alignment_type() == AD_ALIGNMENT_NO_WRAPPING) $content = substr_replace ($content, ai_getAdCode ($obj), $content_position, 0); else
        $content = substr_replace ($content, "<div class='" . $block_class_name . " " . $block_class_name . "-" . $block."' style='" . $obj->get_alignmet_style() . "'>" . ai_getAdCode ($obj) . "</div>", $content_position, 0);
    }
  }

  return $content;
}

function ai_generateAfterParagraph ($block, $content, $obj, $before = true){

  $paragraph_positions = array ();
  $poseslast = array ();
  $last_position = - 1;

  $paragraph_end = "</p>";

  while (stripos ($content, $paragraph_end, $last_position + 1) !== false){
    $last_position = stripos ($content, $paragraph_end, $last_position + 1) + 3;
    $paragraph_positions [] = $last_position;
  }

  $paragraph_texts = explode (",", $obj->get_paragraph_text());
  if ($obj->get_paragraph_text() != "" && count ($paragraph_texts != 0)) {
    $filtered_paragraph_positions = array ();
    foreach ($paragraph_positions as $index => $paragraph_position) {
      $paragraph_code = $index == 0 ? substr ($content, 0, $paragraph_position + 1) : substr ($content, $paragraph_positions [$index - 1] + 1, $paragraph_position - $paragraph_positions [$index - 1]);
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

  $position = $obj->get_paragraph_number();

  if ($position > 0 && $position < 1) {
    $position = intval ($position * sizeof ($paragraph_positions) + 0.5);
  }

  if ($position <= 0) {
    $position = rand (0, sizeof ($paragraph_positions) - 1);
  } elseif ($obj->get_direction_type() == AD_DIRECTION_FROM_BOTTOM) {
    $paragraph_positions = array_reverse ($paragraph_positions);
    $position --;
  } else $position --;

  $text = str_replace (array ("\n", "  "), " ", $content);
  $text = strip_tags ($text);
  $number_of_words = sizeof (explode (" ", $text));

  if (sizeof ($paragraph_positions) >= $obj->get_paragraph_number_minimum() && $number_of_words >= $obj->get_minimum_words()) {
    if (sizeof ($paragraph_positions) > $position) {
      $content_position = $paragraph_positions [$position];

      $block_class_name = get_block_class_name ();

      if ($content_position >= strlen ($content) - 1) {
        if ($obj->get_alignment_type() == AD_ALIGNMENT_NO_WRAPPING) $content = $content = $content . ai_getAdCode ($obj); else
          $content = $content . "<div class='" . $block_class_name . " " . $block_class_name . "-" . $block."' style='" . $obj->get_alignmet_style() . "'>" . ai_getAdCode ($obj) . "</div>";
      } else {
          if ($obj->get_alignment_type() == AD_ALIGNMENT_NO_WRAPPING) $content = substr_replace ($content, ai_getAdCode ($obj), $content_position + 1, 0); else
            $content = substr_replace ($content, "<div class='" . $block_class_name . " " . $block_class_name . "-" . $block."' style='" . $obj->get_alignmet_style() . "'>" . ai_getAdCode ($obj) . "</div>", $content_position + 1, 0);
        }
    }
  }

  return $content;
}

function ai_generateDivBefore ($block, $content, $obj){
  $block_class_name = get_block_class_name ();

  if ($obj->get_alignment_type() == AD_ALIGNMENT_NO_WRAPPING) return ai_getAdCode ($obj) . $content; else
    return "<div class='" . $block_class_name . " " . $block_class_name . "-" . $block."' style='" . $obj->get_alignmet_style() . "'>" . ai_getAdCode ($obj) . "</div>" . $content;
}

function ai_generateDivAfter ($block, $content, $obj){
  $block_class_name = get_block_class_name ();

  if ($obj->get_alignment_type() == AD_ALIGNMENT_NO_WRAPPING) return $content . ai_getAdCode ($obj); else
    return $content . "<div class='" . $block_class_name . " " . $block_class_name . "-" . $block."' style='" . $obj->get_alignmet_style() . "'>" . ai_getAdCode ($obj) . "</div>";
}

function ai_generateDivManual ($block, $content, $obj, $ad_number){

   $block_class_name = get_block_class_name ();

   if (preg_match_all("/{adinserter (.+?)}/", $content, $tags)){
     foreach ($tags [1] as $tag) {
        $ad_tag = strtolower (trim ($tag));
        $ad_name = strtolower (trim ($obj->get_ad_name()));
        if ($ad_tag == $ad_name || $ad_tag == $ad_number) {
         if ($obj->get_alignment_type() == AD_ALIGNMENT_NO_WRAPPING) $ad_code = ai_getAdCode ($obj); else
           $ad_code = "<div class='" . $block_class_name . " " . $block_class_name . "-" . $block."' style='" . $obj->get_alignmet_style() . "'>" . ai_getAdCode ($obj) . "</div>";
         $content = preg_replace ("/{adinserter " . $tag . "}/", $ad_code, $content);
        }
     }
   }

   return $content;
}

function process_shortcodes ($atts) {
  $ad1  = new ai_Block (1);
  $ad2  = new ai_Block (2);
  $ad3  = new ai_Block (3);
  $ad4  = new ai_Block (4);
  $ad5  = new ai_Block (5);
  $ad6  = new ai_Block (6);
  $ad7  = new ai_Block (7);
  $ad8  = new ai_Block (8);
  $ad9  = new ai_Block (9);
  $ad10 = new ai_Block (10);
  $ad11 = new ai_Block (11);
  $ad12 = new ai_Block (12);
  $ad13 = new ai_Block (13);
  $ad14 = new ai_Block (14);
  $ad15 = new ai_Block (15);
  $ad16 = new ai_Block (16);

  $ad_all_data = array ($ad1,$ad2,$ad3,$ad4,$ad5,$ad6,$ad7,$ad8,$ad9,$ad10,$ad11,$ad12,$ad13,$ad14,$ad15,$ad16);

  // Load options from db
  foreach($ad_all_data as $key => $obj){
    $obj->load_options ("AdInserter".($key + 1)."Options");
  }

  $parameters = shortcode_atts (array (
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
      $display_for_users = $obj->get_display_for_users ();

      if ($display_for_users == AD_DISPLAY_LOGGED_IN_USERS && !is_user_logged_in ()) return "";
      if ($display_for_users == AD_DISPLAY_NOT_LOGGED_IN_USERS && is_user_logged_in ()) return "";

      $display_for_devices = $obj->get_display_for_devices ();

      if ($display_for_devices == AD_DISPLAY_DESKTOP_DEVICES && !AI_DESKTOP) return "";
      if ($display_for_devices == AD_DISPLAY_MOBILE_DEVICES && !AI_MOBILE) return "";
      if ($display_for_devices == AD_DISPLAY_TABLET_DEVICES && !AI_TABLET) return "";
      if ($display_for_devices == AD_DISPLAY_PHONE_DEVICES && !AI_PHONE) return "";
      if ($display_for_devices == AD_DISPLAY_DESKTOP_TABLET_DEVICES && !(AI_DESKTOP || AI_TABLET)) return "";
      if ($display_for_devices == AD_DISPLAY_DESKTOP_PHONE_DEVICES && !(AI_DESKTOP || AI_PHONE)) return "";

      $block_class_name = get_block_class_name ();

      if ($obj->get_alignment_type() == AD_ALIGNMENT_NO_WRAPPING) $ad_code = ai_getAdCode ($obj); else
        $ad_code = "<div class='" . $block_class_name . " " . $block_class_name . "-" . $block."' style='" . $obj->get_alignmet_style() . "'>" . ai_getAdCode ($obj) . "</div>";
      return ($ad_code);
    }
  }
  return "";
}


class ai_widget extends WP_Widget {

  function __construct () {
    parent::__construct (
      false,                                  // Base ID
      'Ad Inserter',               // Name
      array (                                 // Args
        'classname'   => 'ai_widget',
        'description' => 'Ad Inserter code block widget.')
    );
  }

  function widget ($args, $instance) {
    // Widget output

    $title = !empty ($instance ['widget-title']) ? $instance ['widget-title'] : '';
    $block = !empty ($instance ['block']) ? $instance ['block'] : 1;

    $ad = new ai_Block ($block);
    $ad->load_options (str_replace ("#", $block, AD_ADx_OPTIONS));
    ai_widget_draw ($block, $ad, $args, $title);
  }

  function form ($instance) {
    // Output admin widget options form

    $blocks = array ();
    for ($block_index = 1; $block_index <= 16; $block_index ++) {
      $obj = new ai_Block ($block_index);
      $obj->load_options (str_replace ("#", $block_index, AD_ADx_OPTIONS));
      $blocks [$block_index] = $obj;
    }

    $widget_title = !empty ($instance ['widget-title']) ? $instance ['widget-title'] : '';
    $block = !empty ($instance ['block']) ? $instance ['block'] : 1;

    $obj = $blocks [$block];
    $title = '[' . $block . '] ' . $obj->get_ad_name();
    if (!empty ($widget_title)) $title .= ' - ' . $widget_title

    ?>
    <input id="<?php echo $this->get_field_id ('title'); ?>" name="<?php echo $this->get_field_name ('title'); ?>" type="hidden" value="<?php echo esc_attr ($title); ?>">

    <p>
      <label for="<?php echo $this->get_field_id ('widget-title'); ?>">Title:</label>
      <input id="<?php echo $this->get_field_id ('widget-title'); ?>" name="<?php echo $this->get_field_name ('widget-title'); ?>" type="text" value="<?php echo esc_attr ($widget_title); ?>" style="width: 90%;">
    </p>

    <p>
      <label for="<?php echo $this->get_field_id ('block'); ?>">Block:</label>
      <select id="<?php echo $this->get_field_id ('block'); ?>" name="<?php echo $this->get_field_name('block'); ?>" style="width: 88%;">
        <?php
          for ($block_index = 1; $block_index <= 16; $block_index ++) {
            $obj = $blocks [$block_index];
        ?>
        <option value='<?php echo $block_index; ?>' <?php if ($block_index == $block) echo 'selected="selected"'; ?>><?php echo $obj->get_ad_name(); ?></option>
        <?php } ?>
      </select>
    </p>
    <?php
  }

  function update ($new_instance, $old_instance) {
    // Save widget options
    $instance = $old_instance;

    $instance ['widget-title'] = (!empty ($new_instance ['widget-title'])) ? strip_tags ($new_instance ['widget-title']) : '';
    $instance ['title'] = (!empty ($new_instance ['title'])) ? strip_tags ($new_instance ['title']) : '';
    $instance ['block'] = (!empty ($new_instance ['block'])) ? $new_instance ['block'] : 1;

    return $instance;
  }
}


// OLD WIDGETS - DEPRECATED

function ai_widget ($args, $parameters) {
  $block = $parameters ['block'];
  $ad = new ai_Block ($block);
  $ad->load_options (str_replace ("#", $block, AD_ADx_OPTIONS));
  ai_widget_draw ($block, $ad, $args);
}

// Fix because widgets that share callback functions don't get displayed

function ai_widget1 ($args, $parameters) {
  ai_widget ($args, $parameters);
}

function ai_widget2 ($args, $parameters) {
  ai_widget ($args, $parameters);
}

function ai_widget3 ($args, $parameters) {
  ai_widget ($args, $parameters);
}

function ai_widget4 ($args, $parameters) {
  ai_widget ($args, $parameters);
}

function ai_widget5 ($args, $parameters) {
  ai_widget ($args, $parameters);
}

function ai_widget6 ($args, $parameters) {
  ai_widget ($args, $parameters);
}

function ai_widget7 ($args, $parameters) {
  ai_widget ($args, $parameters);
}

function ai_widget8 ($args, $parameters) {
  ai_widget ($args, $parameters);
}

function ai_widget9 ($args, $parameters) {
  ai_widget ($args, $parameters);
}

function ai_widget10 ($args, $parameters) {
  ai_widget ($args, $parameters);
}

function ai_widget11 ($args, $parameters) {
  ai_widget ($args, $parameters);
}

function ai_widget12 ($args, $parameters) {
  ai_widget ($args, $parameters);
}

function ai_widget13 ($args, $parameters) {
  ai_widget ($args, $parameters);
}

function ai_widget14 ($args, $parameters) {
  ai_widget ($args, $parameters);
}

function ai_widget15 ($args, $parameters) {
  ai_widget ($args, $parameters);
}

function ai_widget16 ($args, $parameters) {
  ai_widget ($args, $parameters);
}
// OLD WIDGETS END


function ai_widget_draw ($block, $obj, $args, $title = '') {

  $display_for_users = $obj->get_display_for_users ();

  if ($display_for_users == AD_DISPLAY_LOGGED_IN_USERS && !is_user_logged_in ()) return;
  if ($display_for_users == AD_DISPLAY_NOT_LOGGED_IN_USERS && is_user_logged_in ()) return;

  $display_for_devices = $obj->get_display_for_devices ();

  if ($display_for_devices == AD_DISPLAY_DESKTOP_DEVICES && !AI_DESKTOP) return;
  if ($display_for_devices == AD_DISPLAY_MOBILE_DEVICES && !AI_MOBILE) return;;
  if ($display_for_devices == AD_DISPLAY_TABLET_DEVICES && !AI_TABLET) return;
  if ($display_for_devices == AD_DISPLAY_PHONE_DEVICES && !AI_PHONE) return;
  if ($display_for_devices == AD_DISPLAY_DESKTOP_TABLET_DEVICES && !(AI_DESKTOP || AI_TABLET)) return;
  if ($display_for_devices == AD_DISPLAY_DESKTOP_PHONE_DEVICES && !(AI_DESKTOP || AI_PHONE)) return;

  //get post published date
//  $publish_date = get_the_date ('U');      // Widgets are not in posts

  $http_referer = '';
  if(isset($_SERVER['HTTP_REFERER'])) {
      $http_referer = $_SERVER['HTTP_REFERER'];
  }

//  if ($obj->get_append_type () != AD_SELECT_WIDGET) return;

  //if empty data, continue next
  if($obj->get_ad_data()==AD_EMPTY_DATA){
     return;
  }

  if(is_front_page ()){
     if (!$obj->get_display_settings_home()) return;
  }
  elseif(is_page()){
     if (!$obj->get_display_settings_page()) return;

     $meta_value = get_post_meta (get_the_ID (), '_adinserter_block_exceptions', true);
     $selected_blocks = explode (",", $meta_value);

     $enabled_on_text = $obj->get_ad_enabled_on_which_pages ();
     if ($enabled_on_text == AD_ENABLED_ON_ALL_EXCEPT_ON_SELECTED) {
       if (in_array ($obj->number, $selected_blocks)) return;
     }
     elseif ($enabled_on_text == AD_ENABLED_ONLY_ON_SELECTED) {
       if (!in_array ($obj->number, $selected_blocks)) return;
     }
  }
  elseif(is_single()){
     if (!$obj->get_display_settings_post()) return;

     $meta_value = get_post_meta (get_the_ID (), '_adinserter_block_exceptions', true);
     $selected_blocks = explode (",", $meta_value);

     $enabled_on_text = $obj->get_ad_enabled_on_which_posts ();
     if ($enabled_on_text == AD_ENABLED_ON_ALL_EXCEPT_ON_SELECTED) {
       if (in_array ($obj->number, $selected_blocks)) return;
     }
     elseif ($enabled_on_text == AD_ENABLED_ONLY_ON_SELECTED) {
       if (!in_array ($obj->number, $selected_blocks)) return;
     }
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

  if(ai_isTagAllowed($obj->get_ad_block_tag(), $obj->get_ad_block_tag_type())==false){
     return;
  }

//  if(ai_isDisplayDateAllowed($obj, $publish_date)==false){
//     return;
//  }

  if(ai_isRefererAllowed($obj, $http_referer, $obj->get_ad_domain_list_type()) == false){
     return;
  }

  $block_class_name = get_block_class_name ();

  echo $args ['before_widget'];

  if (!empty ($title)) {
    echo $args ['before_title'], apply_filters ('widget_title', $title), $args ['after_title'];
  }

  if ($obj->get_alignment_type() == AD_ALIGNMENT_NO_WRAPPING) echo ai_getAdCode ($obj); else
    echo "<div class='" . $block_class_name . " " . $block_class_name . "-" . $block."' style='" . $obj->get_alignmet_style(false) . "'>" . ai_getAdCode ($obj) . "</div>";

  echo $args ['after_widget'];
}
