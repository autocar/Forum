<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>@lang('laralum_forum::general.category_threads') - {{ Laralum\Settings\Models\Settings::first()->appname }}</title>
    </head>
    <body>
        <h1>@lang('laralum_forum::general.category_threads')</h1>
        <div>
            @if ($category->threads->count())
                @foreach ($category->threads as $thread)
                    <div>
                        <h3>{{ $thread->title }}</h3>
                        <p><time datetime="2016-04-01T19:00">{{ $thread->created_at->diffForHumans() }}</time></p>
                        <p>{{ $thread->description }}</p>
                        <a href="{{ route('laralum_public::forum.threads.show', ['thread' => $thread->id]) }}" >@lang('laralum_forum::general.view_thread')</a>
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
    </body>
</html>
