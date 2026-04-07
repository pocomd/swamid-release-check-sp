<?php
namespace releasecheck;

/**
 * Class to handle Localization of application
 */
class Localize {

  /**
   * Setup the class
   *
   * @return void
   */
  public function __construct() {
    if(session_status() !== PHP_SESSION_ACTIVE) {
      session_start();
    }

    $selectedLang = '';

    if (isset($_GET['lang'])) {
      $selectedLang = 'en';
      if ($_GET['lang'] == 'sv') {
        $selectedLang = 'sv_SE';
      }
      $_SESSION['lang'] = $selectedLang;
    }
    elseif (isset($_SESSION['lang'])) {
      $selectedLang = $_SESSION['lang'];
    } else {
      $langs = array();

      if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        // break up string into pieces (languages and q factors)
        preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.\d+))?/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang_parse);

        if (count($lang_parse[1])) {
          // create a list like "en" => 0.8
          $langs = array_combine($lang_parse[1], $lang_parse[4]);

          // set default to 1 for any without q factor
          foreach ($langs as $lang => $val) {
            $langs[$lang] = $val === '' ? 1 : $val;
          }

          // sort list based on value
          arsort($langs, SORT_NUMERIC);
        }
      }

      // look through sorted list and use first one that matches our languages
      // https://simplelocalize.io/data/locales/ for other codes
      foreach ($langs as $lang => $val) {
        if ($selectedLang == '' ) {
          switch ($lang) {
            case 'en' :
            case 'en-GB' :
            case 'en-US' :
              $selectedLang = 'en_GB';
              break;
            case 'fr' :
            case 'fr-CA' :
            case 'fr-FR' :
              $selectedLang = 'fr_FR';
              break;
            case 'it' :
            case 'it-IT' :
              $selectedLang = 'it_IT';
              break;
            case 'sr' :
            case 'sr-RS' :
              $selectedLang = 'sr_RS';
              break;
            case 'sv' :
            case 'sv-SE' :
              $selectedLang = 'sv_SE';
              break;
            default:
          }
        }
      }
    }
    if ($selectedLang != '') {
      $this->setLocale($selectedLang);
    }
  }

  /**
   * Set langauage of translation
   *
   * @param string $locale locale to translae into
   * @return void
   */
  private function setLocale($locale) {
    setlocale(LC_MESSAGES, $locale); // Linux
    bindtextdomain('Common', __DIR__ . '/../locale');
    textdomain('Common');
  }
}
