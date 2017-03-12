<?php

Route::group([
        'middleware' => [
            'web', 'laralum.base', 'laralum.auth',
            'can:access,Laralum\Forum\Models\Category',
        ],
        'prefix' => config('laralum.settings.base_url').'/forum',
        'namespace' => 'Laralum\Forum\Controllers',
        'as' => 'laralum::forum.'
    ], function () {
        // First the suplementor, then the resource
        // https://laravel.com/docs/5.4/controllers#resource-controllers

        Route::get('categories/{category}/delete', 'CategoryController@confirmDestroy')->name('categories.destroy.confirm');
        Route::resource('categories', 'CategoryController');
        Route::group([
                'middleware' => [
                    'can:access,Laralum\Forum\Models\Thread',
                ],
            ], function () {
                // First the suplementor, then the resource
                // https://laravel.com/docs/5.4/controllers#resource-controllers

                Route::get('categories/{category}/threads/{thread}/delete', 'ThreadController@confirmDestroy')->name('categories.threads.destroy.confirm');
                Route::resource('categories.threads', 'ThreadController', ['except' => ['index']]);
                Route::group([
                        'middleware' => [
                            'can:access,Laralum\Forum\Models\Comment',
                        ],
                    ], function () {
                        // First the suplementor, then the resource
                        // https://laravel.com/docs/5.4/controllers#resource-controllers

                        Route::get('categories/{category}/threads/{thread}/comment/{comment}/delete', 'CommentController@confirmDestroy')->name('categories.threads.comments.destroy.confirm');
                        Route::resource('categories.threads.comments', 'CommentController', ['only' => ['store', 'update', 'destroy']]);
                });
        });
});
