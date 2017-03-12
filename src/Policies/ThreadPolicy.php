<?php

namespace Laralum\Forum\Policies;

use Laralum\Users\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Laralum\Forum\Models\Thread;

class ThreadPolicy
{
    use HandlesAuthorization;

    /**
     * Filters the authoritzation.
     *
     * @param mixed $user
     * @param mixed $ability
     */
    public function before($user, $ability)
    {
        if (User::findOrFail($user->id)->superAdmin()) {
            return true;
        }
    }

    /**
     * Determine if the current user can access threads.
     *
     * @param  mixed $user
     * @return bool
     */
    public function access($user)
    {
        return User::findOrFail($user->id)->hasPermission('laralum::forum.threads.access');
    }


    /**
     * Determine if the current user can create threads.
     *
     * @param  mixed  $user
     * @return bool
     */
    public function create($user)
    {
        return User::findOrFail($user->id)->hasPermission('laralum::forum.threads.create');
    }


    /**
     * Determine if the current user can view threads.
     *
     * @param  mixed $user
     * @param  \Laralum\Forum\Models\Thread $thread
     * @return bool
     */
    public function view($user, Thread $thread)
    {
        if ($thread->user->id == $user->id) {
            return true;
        }
        return User::findOrFail($user->id)->hasPermission('laralum::forum.threads.view');
    }


    /**
     * Determine if the current user can update threads.
     *
     * @param  mixed $user
     * @param  \Laralum\Forum\Models\Thread $thread
     * @return bool
     */
    public function update($user, Thread $thread)
    {
        if ($thread->user->id == $user->id) {
            return true;
        }
        return User::findOrFail($user->id)->hasPermission('laralum::forum.threads.update');
    }


    /**
     * Determine if the current user can delete threads.
     *
     * @param  mixed $user
     * @param  \Laralum\Forum\Models\Thread $thread
     * @return bool
     */
    public function delete($user, Thread $thread)
    {
        if ($thread->user->id == $user->id) {
            return true;
        }
        return User::findOrFail($user->id)->hasPermission('laralum::forum.threads.delete');
    }
}
