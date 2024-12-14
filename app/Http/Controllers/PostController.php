<?php

namespace App\Http\Controllers;

use Lapetus\Request;

class PostController
{
  public function index()
  {
    echo 'PostController::index';
  }

  public function store()
  {
    echo 'PostController::index';
  }

  public function show(Request $request, $id)
  {
    echo 'PostController::show';
  }

  public function update()
  {
    echo 'PostController::update';
  }

  public function destroy()
  {
    echo 'PostController::destroy';
  }
}
