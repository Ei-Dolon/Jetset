<?php

if( ! defined( 'ABSPATH' ) ) die;

class WPSCSettingsPage {
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    // get thousands and decimals number separation settings
    static private function getSeparators() {

      global $wp_locale;

      // if set get from user settings
      $settings = self::get();
      if (WPSC_helpers::valArrElement($settings, 'number_separators')) {
        return $settings["number_separators"];

      // if not get from WP settings
      } elseif (isset($wp_locale)) {
        return $wp_locale->number_format['thousands_sep'] . $wp_locale->number_format['decimal_point'];

      // if not return english format
      } else {
        return ',.';
      }

    }

    static public function numberOfDecimalsToShow() {

      $dts = self::get('decimals_to_show');

      if ($dts!==false) {
        return $dts;
      } else {
        return 2;
      }

    }

    static public function nftReverse() {
      if (self::get('wpsc_reverse_order')) {
        return true;
      } else {
        return false;
      }
    }

    static public function nftItemsPerPage() {

      $dts = self::get('nft_items_per_page');

      if ($dts) {
        if ($dts==1) {
          return 2;
        } else {
          return $dts;
        }
      } else {
        return 12;
      }

    }
    
    static public function nftSkin() {
      $skin = self::get('wpsc-skin');
      if ($skin) {
        return $skin;
      } else {
        return "default";
      }
    }

    static public function numberFormatDecimals() {
      $separators = self::getSeparators();
      if (strlen($separators)==2) {
        return substr($separators, 1);
      } else {
        return '.';
      }
    }

    static public function numberFormatThousands() {
      $separators = self::getSeparators();
      if (strlen($separators)==2) {
        $sep = substr($separators, 0, 1);
        if ($sep=="_") return " ";
        if ($sep=="x") return "";
        return $sep;
      } else {
        return ',';
      }
    }

    static public function get($option=null) {
      $options = get_option( 'etherscan_api_key_option' );
      if ($option) {
        if (WPSC_helpers::valArrElement($options, $option)) {
          return $options[$option]; 
        } else {
          return false;
        }
      }
      return $options;
    }

}

if( is_admin() ) new WPSCSettingsPage();