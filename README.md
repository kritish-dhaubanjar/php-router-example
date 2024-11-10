## PHP Router Example

```php
<?php

use App\Route;
use App\Controllers\PostController;

Route::get('/posts', [PostController::class, 'index']);

Route::get('/posts/{id}/comments/{id}', function ($post, $comment) {
  echo "GET /posts/$post/comments/$comment";
});
```
