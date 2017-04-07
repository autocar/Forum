<?php

namespace Laralum\Forum\Models;

use Illuminate\Database\Eloquent\Model;
use Laralum\Forum\Models\Thread;
use Laralum\Forum\Models\Comment;

class Category extends Model {

	/**
     * The table associated with the model.
     *
     * @var string
     */
	protected $table = 'laralum_forum_categories';

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];


	public function threads()
	{
		return $this->hasMany('Laralum\Forum\Models\Thread');
	}

	public function comments()
	{
		return $this->hasManyThrough(Comment::class, Thread::class);
	}

	public function deleteThreads()
	{
		foreach($this->threads as $thread) {
			$thread->delete();
		}
	}

	public function deleteComments()
	{
		foreach($this->comments as $comment) {
			$comment->delete();
		}
	}
}
