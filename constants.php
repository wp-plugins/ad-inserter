<?php

if (!defined ('AD_INSERTER_PLUGIN_DIR'))
  define ('AD_INSERTER_PLUGIN_DIR', plugin_dir_path (__FILE__));

if (file_exists (AD_INSERTER_PLUGIN_DIR.'functions.php')) {
  include_once 'functions.php';
}

if (!defined( 'AD_INSERTER_TITLE'))
  define ('AD_INSERTER_TITLE', 'Ad Inserter');

if (!defined( 'AD_INSERTER_VERSION'))
  define ('AD_INSERTER_VERSION', '1.5.5');

if (!defined ('AD_INSERTER_BLOCKS'))
  define ('AD_INSERTER_BLOCKS', 16);

if (!defined ('AD_INSERTER_PLUGIN_BASENAME'))
  define ('AD_INSERTER_PLUGIN_BASENAME', plugin_basename (__FILE__));

if (!defined ('AD_INSERTER_PLUGIN_DIRNAME'))
  define ('AD_INSERTER_PLUGIN_DIRNAME', dirname (AD_INSERTER_PLUGIN_BASENAME));

if (!defined ('AD_INSERTER_PLUGIN_URL'))
  define ('AD_INSERTER_PLUGIN_URL', plugin_dir_url ( __FILE__));

if (!defined ('AD_INSERTER_PLUGIN_IMAGES_URL'))
  define ('AD_INSERTER_PLUGIN_IMAGES_URL', AD_INSERTER_PLUGIN_URL. 'images/');

if (!defined( 'WP_CONTENT_URL'))
  define ('WP_CONTENT_URL', get_option ('siteurl') . '/wp-content');

if (!defined ('WP_ADMIN_URL'))
  define ('WP_ADMIN_URL', get_option ('siteurl') . '/wp-admin');

if (!defined ('WP_CONTENT_DIR'))
  define ('WP_CONTENT_DIR', ABSPATH . 'wp-content');

if (!defined ('WP_PLUGIN_URL'))
  define ('WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins');

if (!defined ('WP_PLUGIN_DIR'))
  define ('WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins');

define ('DEFAULT_SYNTAX_HIGHLIGHTER_THEME', 'ad_inserter');
define ('DEFAULT_BLOCK_CLASS_NAME', 'code-block');

define ('AD_EMPTY_DATA', '');
define ('AD_ZERO_DATA', '0');
define ('AD_GENERAL_TAG', 'gadgets');
define ('AD_NAME', 'Block');

// Old options
define ('AD_OPTIONS',     'AdInserterOptions');   // general plugin options
define ('AD_ADx_OPTIONS', 'AdInserter#Options');

// Options
define ('WP_OPTION_NAME',                        'ad_inserter');
define ('WP_FORM_FIELD_POSTFIX',                 '_block_');
define ('AI_GLOBAL_OPTION_NAME',                 'global');
define ('AI_HEADER_OPTION_NAME',                 'h');
define ('AI_FOOTER_OPTION_NAME',                 'f');

define ('AI_OPTION_CODE',                        'code');
define ('AI_OPTION_ENABLE_MANUAL',               'enable_manual');
define ('AI_OPTION_PROCESS_PHP',                 'process_php');

define ('AI_OPTION_NAME',                        'name');
define ('AI_OPTION_DISPLAY_TYPE',                'display_type');
define ('AI_OPTION_PARAGRAPH_NUMBER',            'paragraph_number');
define ('AI_OPTION_MIN_PARAGRAPHS',              'min_paragraphs');
define ('AI_OPTION_MIN_WORDS',                   'min_words');
define ('AI_OPTION_EXCERPT_NUMBER',              'excerpt_number');
define ('AI_OPTION_DIRECTION_TYPE',              'direction_type');
define ('AI_OPTION_ALIGNMENT_TYPE',              'alignment_type');
define ('AI_OPTION_GENERAL_TAG',                 'general_tag');
define ('AI_OPTION_AFTER_DAYS',                  'after_days');
define ('AI_OPTION_URL_LIST',                    'url_list');
define ('AI_OPTION_URL_LIST_TYPE',               'url_list_type');
define ('AI_OPTION_DOMAIN_LIST',                 'domain_list');
define ('AI_OPTION_DOMAIN_LIST_TYPE',            'domain_list_type');
define ('AI_OPTION_CATEGORY_LIST',               'category_list');
define ('AI_OPTION_CATEGORY_LIST_TYPE',          'category_list_type');
define ('AI_OPTION_TAG_LIST',                    'tag_list');
define ('AI_OPTION_TAG_LIST_TYPE',               'tag_list_type');
define ('AI_OPTION_DISPLAY_ON_HOMEPAGE',         'display_on_homepage');
define ('AI_OPTION_DISPLAY_ON_PAGES',            'display_on_pages');
define ('AI_OPTION_DISPLAY_ON_POSTS',            'display_on_posts');
define ('AI_OPTION_DISPLAY_ON_CATEGORY_PAGES',   'display_on_category_pages');
define ('AI_OPTION_DISPLAY_ON_SEARCH_PAGES',     'display_on_search_pages');
define ('AI_OPTION_DISPLAY_ON_ARCHIVE_PAGES',    'display_on_archive_pages');
define ('AI_OPTION_ENABLED_ON_WHICH_PAGES',      'enabled_on_which_pages');
define ('AI_OPTION_ENABLED_ON_WHICH_POSTS',      'enabled_on_which_posts');
define ('AI_OPTION_ENABLE_PHP_CALL',             'enable_php_call');
define ('AI_OPTION_PARAGRAPH_TEXT',              'paragraph_text');
define ('AI_OPTION_PARAGRAPH_TEXT_TYPE',         'paragraph_text_type');
define ('AI_OPTION_CUSTOM_CSS',                  'custom_css');
define ('AI_OPTION_DISPLAY_FOR_USERS',           'display_for_users');
define ('AI_OPTION_DISPLAY_FOR_DEVICES',         'display_for_devices');

define ('AI_OPTION_IMPORT',                      'import');

//misc
define('AD_EMPTY_VALUE','');

//define constant variable form
define('AD_FORM_SAVE','ad_save');
define('AD_FORM_CLEAR','ad_clear');

define('AD_AUTHOR_SITE', '<!-- Powered by Ad Inserter Plugin By Spacetime -->');
define('AD_SEPARATOR', '|rotate|');

//form select options
define('AD_SELECT_SELECTED','selected');

//Position options
define('AD_SELECT_NONE','None');
define('AD_SELECT_BEFORE_TITLE','Before Title');
define('AD_SELECT_BEFORE_PARAGRAPH','Before Paragraph');
define('AD_SELECT_AFTER_PARAGRAPH','After Paragraph');
define('AD_SELECT_BEFORE_CONTENT','Before Content');
define('AD_SELECT_AFTER_CONTENT','After Content');
define('AD_SELECT_BEFORE_EXCERPT','Before Excerpt');
define('AD_SELECT_AFTER_EXCERPT','After Excerpt');
define('AD_SELECT_WIDGET','Widget');
// Deprecated
define('AD_SELECT_MANUAL','Manual');

//Display options
define('AD_DISPLAY_ALL_USERS','all');
define('AD_DISPLAY_LOGGED_IN_USERS','logged in');
define('AD_DISPLAY_NOT_LOGGED_IN_USERS','not logged in');

define('AD_DISPLAY_ALL_DEVICES','all');
define('AD_DISPLAY_DESKTOP_DEVICES','desktop');
define('AD_DISPLAY_MOBILE_DEVICES','mobile');
define('AD_DISPLAY_TABLET_DEVICES','tablet');
define('AD_DISPLAY_PHONE_DEVICES','phone');
define('AD_DISPLAY_DESKTOP_TABLET_DEVICES','desktop and tablet');
define('AD_DISPLAY_DESKTOP_PHONE_DEVICES','desktop and phone');

//Direction options
define('AD_DIRECTION_FROM_TOP','From Top');
define('AD_DIRECTION_FROM_BOTTOM','From Bottom');

//Post-Page options
define('AD_ENABLED_ON_ALL',                     'On all');
define('AD_ENABLED_ON_ALL_EXCEPT_ON_SELECTED',  'On all except selected');
define('AD_ENABLED_ONLY_ON_SELECTED',           'Only on selected');

//Alignment options
define('AD_ALIGNMENT_NO_WRAPPING','No Wrapping');
define('AD_ALIGNMENT_CUSTOM_CSS','Custom CSS');
define('AD_ALIGNMENT_NONE','None');
define('AD_ALIGNMENT_LEFT','Align Left');
define('AD_ALIGNMENT_RIGHT','Align Right');
define('AD_ALIGNMENT_CENTER','Center');
define('AD_ALIGNMENT_FLOAT_LEFT','Float Left');
define('AD_ALIGNMENT_FLOAT_RIGHT','Float Right');

//List Type
define('AD_BLACK_LIST','Black List');
define('AD_WHITE_LIST','White List');

//Text List Type
define('AD_CONTAIN','contain');
define('AD_DO_NOT_CONTAIN','do not contain');

//Settings
define('AD_SETTINGS_CHECKED', '1');
define('AD_SETTINGS_NOT_CHECKED', '0');
