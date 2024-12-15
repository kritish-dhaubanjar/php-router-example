<?php

namespace Lapetus;

class Response
{
  public function redirect($url)
  {
    header("Location: $url");
  }

  public function statusCode(int $code)
  {
    http_response_code($code);
    return $this;
  }

  public function headers(array $headers = [])
  {
    foreach ($headers as $header) {
      header($header);
    }
    return $this;
  }

  public function view($view, $data = [])
  {
    return view($view, $data);
  }
}
