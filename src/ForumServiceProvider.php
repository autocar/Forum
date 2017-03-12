<?php

namespace Laralum\Forum;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

use Laralum\Forum\Models\Category;
use Laralum\Forum\Models\Comment;
use Laralum\Forum\Models\Thread;
use Laralum\Forum\Policies\CategoryPolicy;
use Laralum\Forum\Policies\CommentPolicy;
use Laralum\Forum\Policies\ThreadPolicy;

use Laralum\Permissions\PermissionsChecker;


class ForumServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Category::class => CategoryPolicy::class,
        Comment::class => CommentPolicy::class,
        Thread::class => ThreadPolicy::class,
    ];

    /**
     * The mandatory permissions for the module.
     *
     * @var array
     */
    protected $permissions = [
        [
            'name' => 'Forum Categories Access',
            'slug' => 'laralum::forum.categories.access',
            'desc' => "Grants access to forum categories",
        ],
        [
            'name' => 'Create Forum Categories',
            'slug' => 'laralum::forum.categories.create',
            'desc' => "Allows creating forum categories",
        ],
        [
            'name' => 'Update Forum Categories',
            'slug' => 'laralum::forum.categories.update',
            'desc' => "Allows updating forum categories",
        ],
        [
            'name' => 'View Forum Categories',
            'slug' => 'laralum::forum.categories.view',
            'desc' => "Allows view forum categories",
        ],
        [
            'name' => 'Delete Forum Categories',
            'slug' => 'laralum::forum.categories.delete',
            'desc' => "Allows delete forum categories",
        ],
        [
            'name' => 'Forum Threads Access',
            'slug' => 'laralum::forum.threads.access',
            'desc' => "Grants access to forum threads",
        ],
        [
            'name' => 'Create Forum Threads',
            'slug' => 'laralum::forum.threads.create',
            'desc' => "Allows creating forum threads",
        ],
        [
            'name' => 'Update Forum Threads',
            'slug' => 'laralum::forum.threads.update',
            'desc' => "Allows updating forum threads",
        ],
        [
            'name' => 'View Forum Threads',
            'slug' => 'laralum::forum.threads.view',
            'desc' => "Allows view forum threads",
        ],
        [
            'name' => 'Delete Forum Threads',
            'slug' => 'laralum::forum.threads.delete',
            'desc' => "Allows delete forum threads",
        ],
        [
            'name' => 'Forum Comments Access',
            'slug' => 'laralum::forum.comments.access',
            'desc' => "Grants access to forum comments",
        ],
        [
            'name' => 'Create Forum Comments',
            'slug' => 'laralum::forum.comments.create',
            'desc' => "Allows creating forum comments",
        ],
        [
            'name' => 'Update Forum Comments',
            'slug' => 'laralum::forum.comments.update',
            'desc' => "Allows updating forum comments",
        ],
        [
            'name' => 'View Forum Comments',
            'slug' => 'laralum::forum.comments.view',
            'desc' => "Allows view forum comments",
        ],
        [
            'name' => 'Delete Forum Comments',
            'slug' => 'laralum::forum.comments.delete',
            'desc' => "Allows delete forum comments",
        ],
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->loadViewsFrom(__DIR__.'/Views', 'laralum_forum');

        $this->loadTranslationsFrom(__DIR__.'/Translations', 'laralum_forum');

        if (!$this->app->routesAreCached()) {
            require __DIR__.'/Routes/web.php';
        }

        $this->loadMigrationsFrom(__DIR__.'/Migrations');

        // Make sure the permissions are OK
        PermissionsChecker::check($this->permissions);
    }

    /**
     * I cheated this comes from the AuthServiceProvider extended by the App\Providers\AuthServiceProvider
     *
     * Register the application's policies.
     *
     * @return void
     */
    public function registerPolicies()
    {
        foreach ($this->policies as $key => $value) {
            Gate::policy($key, $value);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
