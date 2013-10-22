<?php
namespace Fortissimo\Guzzle;

/**
 * Create a Guzzle client.
 *
 * Params:
 * - baseUrl (string): The base URL. Paths are resolved relative to this.
 * - options (array): An array of Guzzle options.
 * - userAgent (string): A user agent string.
 * - plugins (array): An array of Plugin objects that will be attached to the object.
 *
 * Returns an initialized and configured Guzzle Client.
 */
class CreateClient extends \Fortissimo\Command\Base {
  public function expects() {
    return $this
      ->description("Create a new Guzzle client.")
      ->usesParam("baseUrl", "The base URL. Paths can be appended to this later.")
        ->whichIsRequired()->withFilter('url') // SANITIZE filter.
      ->usesParam("options", "An array of Guzzle client options.")
      ->usesParam("userAgent", "The user agent string.")
      ->usesParam("plugins", "An array of Plugin objects.")
      ->andReturns("An intialized Guzzle client.")
      ;
  }

  public function doCommand() {
    $url = $this->param('baseUrl');
    $opts = $this->param('options');
    $ua = $this->param('userAgent');
    $plugins = $this->param('plugins');
    $client = new \Guzzle\Http\Client($url, $opts);

    if (isset($ua)) {
      $client->setUserAgent($ua);
    }

    if (!empty($plugins)) {
      foreach ($plugins as $plugin) {
        $client->addSubscriber($plugin);
      }
    }

    // XXX: Could add event listeners here, too.

    return $client;
  }

}
