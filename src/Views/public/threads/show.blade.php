@extends('laralum::layouts.public')
@section('css')
    <style>
        .hidden {
            display: none;
        }
    </style>
@endsection
@section('title', __('laralum_forum::general.view_thread'))
@section('content')

                <h1>{{ $thread->title }}</h1>

                <p>@lang('laralum_forum::general.written_by', ['username' => $thread->user->name, 'time_ago' => $thread->created_at->diffForHumans(), 'cat' => $thread->category->title])</p>

                <p>{!! $thread->content !!}</p>

                <br>
                <div class="uk-grid-small uk-child-width-1-1" uk-grid>
                    <span>
                        <a href="#comments">{{ trans_choice('laralum_forum::general.comments_choice', $thread->comments->count(), ['num' => $thread->comments->count()]) }}</a>
                    </span>
                </div>

            </article>
        </div>
    </div>
    </div>
    <br><br><br>
    @can('access', \Laralum\Forum\Models\Comment::class)
        <div id="comments">
            <div class="uk-card uk-card-default uk-card-body">
                <h3 class="uk-card-title">@if($thread->comments->count()) @lang('laralum_forum::general.comments') @else @lang('laralum_forum::general.no_comments_yet') @endif</h3>
                @foreach ($thread->comments as $comment)
                    @can('view', $comment)
                        <article class="uk-comment uk-comment-primary">
                            <header class="uk-comment-header uk-grid-medium uk-flex-middle" uk-grid>

                                <div class="uk-width-auto">
                                    <img class="uk-comment-avatar uk-border-circle" src="{{ $comment->user->avatar() }}" width="80" height="80" alt="">
                                </div>
                                <div class="uk-width-expand">
                                    <h4 class="uk-comment-title uk-margin-remove"><span>{{ $comment->user->name }}</span></h4>
                                    <ul class="uk-comment-meta uk-subnav uk-subnav-divider uk-margin-remove-top">
                                        <li><span>{{ $comment->created_at->diffForHumans() }}</span></li>
                                    </ul>
                                </div>
                            </header>
                            <div class="uk-comment-body">
                                @can('delete', $comment)
                                    <form action="{{ route('laralum_public::forum.categories.threads.comments.destroy',['category' => $thread->category->id, 'thread' => $thread->id, 'comment' => $comment->id ]) }}" method="thread">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button type="submit" name="button">@lang('laralum_forum::general.delete')</button>
                                    </form>
                                @endcan
                                @can('update', $comment)
                                    <button class="uk-button uk-button-text uk-align-right edit-comment-button" data-comment="{{ $comment->comment }}" data-url="{{ route('laralum_public::forum.categories.threads.comments.update',['category' => $thread->category->id, 'thread' => $thread->id, 'comment' => $comment->id ]) }}">@lang('laralum_forum::general.edit')</button>
                                @endcan
                                <p class="comment">{{ $comment->comment }}</p>
                            </div>
                        </article>
                        <br>
                    @endcan
                @endforeach
                @can('create', \Laralum\Forum\Models\Comment::class)
                            <img src="{{ \Laralum\Users\Models\User::findOrFail(Auth::id())->avatar() }}" width="80" height="80" alt="">
                            <h4><span>{{ \Laralum\Users\Models\User::findOrFail(Auth::id())->name }}</span></h4>
                    <div>
                        <form method="POST" action="{{ route('laralum::forum.categories.threads.comments.store',['category' => $thread->category->id, 'thread' => $thread->id]) }}">
                            {{ csrf_field() }}
                                    <textarea name="comment" class="uk-textarea" rows="8" placeholder="{{ __('laralum_forum::general.add_a_comment') }}">{{ old('comment') }}</textarea>
                                    <button type="submit">@lang('laralum_forum::general.submit')</button>
                        </form>
                    </div>
                @endcan
            </div>
        </div>
        <form class="hidden" id="edit-comment-form" method="POST">
            {{ csrf_field() }}
            {{ method_field('PATCH') }}
                    <textarea name="comment" class="uk-textarea" id="comment-textarea" rows="8" placeholder="{{ __('laralum_forum::general.edit_a_comment') }}">{{ old('comment') }}</textarea>
                    <button type="submit" class="uk-button uk-button-primary">@lang('laralum_forum::general.submit')</button>
        </form>
    @endcan
</div>
@endsection
@section('js')
    <script>
        $(function() {
            $('.edit-comment-button').click(function() {
                $('.edit-comment-button').prop('disabled', false);
                $(this).attr('disabled', 'disabled');
                var url = $(this).data('url');
                var comment = $(this).data('comment');
                $('#comment-textarea').html(comment);
                var form = $('#edit-comment-form').html();
                $('.edit-comment-form').hide();
                $('.comment').removeClass("hidden"); {{-- Show all comments --}}
                $(this).next().html('<form class="uk-form-stacked edit-comment-form uk-animation-scale-up" id="edit-comment-form" action="' + url + '" method="POST">' + form + '</form><p class="comment hidden">'+comment+'</p>');
            });
        });
    </script>
@endsection
