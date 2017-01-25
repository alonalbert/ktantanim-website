<?php

class UriBuilder {
  private $path;
  private $params;

  /**
   * UriBuilder constructor.
   */
  public function __construct($uri) {
    // Parse URI - Add a HTTP Prefix so we can parse it.
    $url_parts = parse_url("http://host" . $uri);
    $this->path = $url_parts['path'];
    parse_str(isset($url_parts['query']) ? $url_parts['query'] : '', $this-> params);
  }

  public function setParam($name, $value) {
    $this->params[$name] = $value;
  }

  public function build() {
    return  $this->path . '?' . http_build_query($this->params);
  }
}