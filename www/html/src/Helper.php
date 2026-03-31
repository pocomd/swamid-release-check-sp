<?php
namespace releasecheck;

/**
 * Helper class containig useful functions 
 *
 */
class Helper {

  protected array $replacements = array ();

  protected Configuration $config;

  public function __construct(Configuration $config)
  {
    $this->config = $config;
    $this->replacements = array (
      "FED_NAME" => ($this->config->getFederation()["displayName"] ?? _("IdP home Federation")),
    );
  }

  /**
   * Get string from array of results.
   * 
   * @param array $statusTextArr of results string
   *
   * @return string
   */
  public function getStatusTranslated(array|string $statusTextArr): string {
    $string = [];

    if (is_string($statusTextArr)) {
      $decoded  = json_decode($statusTextArr, true);

      if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded )) {
        return $statusTextArr;
      }
      $statusTextArr = $decoded ;
    }

    if (!empty($statusTextArr)) {
      $placeholders = is_array($this->replacements) ? $this->replacements : [];

      foreach ($statusTextArr as $entry) {
        $trans = _($entry);
        $trans = preg_replace_callback('/\[\[(.*?)\]\]/', 
            function ($matches) use ($placeholders) {
                $key = $matches[1];

                return $placeholders[$key] ?? $key; 
            },
            $trans
        );
        $string[] = $trans;
      }
    }

    return implode("<br>", $string);
  }
}
