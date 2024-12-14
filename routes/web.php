<?php

use Lapetus\Routing\Route;
use App\Http\Controllers\PostController;
use Lapetus\Request;
use Lapetus\Support\Env;

Route::get('/posts', [PostController::class, 'index']);

Route::get('/posts/{id}', function (Request $request, $id) {
  var_dump(Env::get('DB_CONNECTION'));
  var_dump($_SERVER);
  var_dump($request->query('name'));
  var_dump($request->query());
  echo "GET /posts/$id";
});

Route::post('/posts/{id}', function (Request $request, $id) {
  // var_dump($request->query('name'));
  // var_dump($request->query());
  // var_dump($request->input('name'));
  // var_dump($request->input());
});

Route::get('/posts/{id}/comments/{id}', function ($post, $comment) {
  echo "GET /posts/$post/comments/$comment";
});
