<?php

if (\Illuminate\Support\Facades\Schema::hasTable('laralum_forum_settings')) {
    $public_url = \Laralum\Forum\Models\Settings::first()->public_url;
} else {
    $public_url = 'forum';
}
Route::group([
        'middleware' => [
            'web', 'laralum.base',
        ],
        'namespace' => 'Laralum\Forum\Controllers',
        'prefix' => $public_url,
        'as' => 'laralum_public::forum.'
    ], function () use ($public_url) {
        Route::get('/', 'PublicCategoryController@index')->name('categories.index');
        Route::get('/categories/{category}', 'PublicCategoryController@show')->name('categories.show');
        Route::get('/categories/{category}/threads/{thread}', 'PublicThreadController@show')->name('categories.threads.show');

        Route::group([
                'middleware' => [
                    'auth', 'can:publicAccess,Laralum\Forum\Models\Comment',
                ],
            ], function () use ($public_url) {
                Route::resource('categories.threads.comments', 'PublicCommentController', [
                    'names' => [
                        'store'   => 'categories.threads.comments.store',
                        'update'  => 'categories.threads.comments.update',
                        'destroy' => 'categories.threads.comments.destroy',
                    ],
                    'only' => [
                        'store', 'update', 'destroy'
                    ],
                ]);
        });

});


Route::group([
        'middleware' => [
            'web', 'laralum.base', 'laralum.auth',
            'can:access,Laralum\Forum\Models\Category',
        ],
        'prefix' => config('laralum.settings.base_url').'/forum',
        'namespace' => 'Laralum\Forum\Controllers',
        'as' => 'laralum::forum.'
    ], function () {
        Route::get('categories/{category}/delete', 'CategoryController@confirmDestroy')->name('categories.destroy.confirm');
        Route::resource('categories', 'CategoryController');
        Route::group([
                'middleware' => [
                    'can:access,Laralum\Forum\Models\Thread',
                ],
            ], function () {
                Route::get('categories/{category}/threads/{thread}/delete', 'ThreadController@confirmDestroy')->name('categories.threads.destroy.confirm');
                Route::resource('categories.threads', 'ThreadController', ['except' => ['index']]);
                Route::group([
                        'middleware' => [
                            'can:access,Laralum\Forum\Models\Comment',
                        ],
                    ], function () {
                        Route::get('categories/{category}/threads/{thread}/comment/{comment}/delete', 'CommentController@confirmDestroy')->name('categories.threads.comments.destroy.confirm');
                        Route::resource('categories.threads.comments', 'CommentController', ['only' => ['store', 'update', 'destroy']]);
                });
        });
});

Route::group([
        'middleware' => [
            'web', 'laralum.base', 'laralum.auth',
            'can:access,Laralum\Forum\Models\Settings',
        ],
        'prefix' => config('laralum.settings.base_url'),
        'namespace' => 'Laralum\Forum\Controllers',
        'as' => 'laralum::forum.'
    ], function () {
        Route::post('/forum/settings', 'SettingsController@update')->name('settings.update');
});
