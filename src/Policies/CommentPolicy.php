<?php

namespace Laralum\Forum\Policies;

use Laralum\Users\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Laralum\Forum\Models\Comment;

class CommentPolicy
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
     * Determine if the current user can access comments.
     *
     * @param  mixed $user
     * @return bool
     */
    public function access($user)
    {
        return User::findOrFail($user->id)->hasPermission('laralum::forum.comments.access');
    }


    /**
     * Determine if the current user can create comments.
     *
     * @param  mixed  $user
     * @return bool
     */
    public function create($user)
    {
        return User::findOrFail($user->id)->hasPermission('laralum::forum.comments.create');
    }


    /**
     * Determine if the current user can view comments.
     *
     * @param  mixed $user
     * @param  \Laralum\Forum\Models\Comment $comment
     * @return bool
     */
    public function view($user, Comment $comment)
    {
        if ($comment->user->id == $user->id) {
            return true;
        }
        return User::findOrFail($user->id)->hasPermission('laralum::forum.comments.view');
    }


    /**
     * Determine if the current user can update comments.
     *
     * @param  mixed $user
     * @param  \Laralum\Forum\Models\Comment $comment
     * @return bool
     */
    public function update($user, Comment $comment)
    {
        if ($comment->user->id == $user->id) {
            return true;
        }
        return User::findOrFail($user->id)->hasPermission('laralum::forum.comments.update');
    }


    /**
     * Determine if the current user can delete comments.
     *
     * @param  mixed $user
     * @param  \Laralum\Forum\Models\Comment $comment
     * @return bool
     */
    public function delete($user, Comment $comment)
    {
        if ($comment->user->id == $user->id) {
            return true;
        }
        return User::findOrFail($user->id)->hasPermission('laralum::forum.comments.delete');
    }
}
