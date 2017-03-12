<?php

namespace Laralum\Forum\Models;

use Illuminate\Database\Eloquent\Model;
use Laralum\Forum\Models\Thread;
use Laralum\Users\Models\User;

class Comment extends Model {

	/**
     * The table associated with the model.
     *
     * @var string
     */
	protected $table = 'laralum_forum_comments';

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'thread_id', 'comment'];


	public function thread()
	{
		return $this->belongsTo(Thread::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

}
