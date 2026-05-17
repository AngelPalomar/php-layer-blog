<?php declare(strict_types=1);

defined('BASEPATH') OR exit('No direct script access allowed');

abstract class BaseController extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
  }

  protected function _body(): array
  {
    return (array) (json_decode(file_get_contents('php://input'), true) ?? []);
  }

  protected function _json(int $code = 200, $data = [], string $message = '', array $errors = [], array $meta = []): CI_Output
  {
    $response = new HttpResponse($code, $data, $message, $meta, $errors);

    return $this->output
      ->set_status_header($response->getCode())
      ->set_content_type('application/json')
      ->set_output(json_encode($response->generate()));
  }
}
