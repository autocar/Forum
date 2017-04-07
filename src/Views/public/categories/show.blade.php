@extends('laralum::layouts.public')
@section('title', __('laralum_forum::general.category_threads'))
@section('content')
<div>
        @if ($category->threads->count())
            @foreach ($category->threads as $thread)
                <div>
                    <h3>{{ $thread->title }}</h3>
                    <p><time datetime="2016-04-01T19:00">{{ $thread->created_at->diffForHumans() }}</time></p>
                    <p>{{ $thread->description }}</p>
                    <a href="{{ route('laralum_public::forum.categories.threads.show', ['category' => $category->id, 'thread' => $thread->id]) }}" >@lang('laralum_forum::general.view_thread')</a>
                    <span>{{ trans_choice('laralum_forum::general.comments_choice', $thread->comments->count(), ['num' => $thread->comments->count()]) }}</span>
                </div>
            @endforeach
        @else
            <div class="uk-width-1-1">
                <div class="uk-card uk-card-default uk-card-body">
                    <div uk-alert>
                        <p>@lang('laralum_forum::general.no_threads_yet')</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
