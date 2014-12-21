<?php

//define constant variable Ad Inserter

if (!defined( 'AD_INSERTER_TITLE'))
  define ('AD_INSERTER_TITLE', 'Ad Inserter');

if (!defined( 'AD_INSERTER_VERSION'))
  define ('AD_INSERTER_VERSION', '1.3.6');

if (!defined ('AD_INSERTER_PLUGIN_DIR'))
  define ('AD_INSERTER_PLUGIN_DIR', plugin_dir_path (__FILE__));

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

define('AD_EMPTY_DATA', '');
define('AD_ZERO_DATA', '0');
define('AD_GENERAL_TAG', 'gadgets');
define('AD_NAME', 'Block');

//options
define('AD_OPTIONS',     'AdInserterOptions');   // general plugin options
define('AD_ADx_OPTIONS', 'AdInserter#Options');
define('AD_AD1_OPTIONS', 'AdInserter1Options');
define('AD_AD2_OPTIONS', 'AdInserter2Options');
define('AD_AD3_OPTIONS', 'AdInserter3Options');
define('AD_AD4_OPTIONS', 'AdInserter4Options');
define('AD_AD5_OPTIONS', 'AdInserter5Options');
define('AD_AD6_OPTIONS', 'AdInserter6Options');
define('AD_AD7_OPTIONS', 'AdInserter7Options');
define('AD_AD8_OPTIONS', 'AdInserter8Options');
define('AD_AD9_OPTIONS', 'AdInserter9Options');
define('AD_AD10_OPTIONS','AdInserter10Options');
define('AD_AD11_OPTIONS','AdInserter11Options');
define('AD_AD12_OPTIONS','AdInserter12Options');
define('AD_AD13_OPTIONS','AdInserter13Options');
define('AD_AD14_OPTIONS','AdInserter14Options');
define('AD_AD15_OPTIONS','AdInserter15Options');
define('AD_AD16_OPTIONS','AdInserter16Options');
define('AD_HEADER_OPTIONS','AdInserterHeaderOptions');
define('AD_FOOTER_OPTIONS','AdInserterFooterOptions');

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

//Direction options
define('AD_DIRECTION_FROM_TOP','From Top');
define('AD_DIRECTION_FROM_BOTTOM','From Bottom');

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

//Settings
define('AD_SETTINGS_CHECKED', '1');
define('AD_SETTINGS_NOT_CHECKED', '0');
