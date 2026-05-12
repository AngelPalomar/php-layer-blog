<?php

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
}
