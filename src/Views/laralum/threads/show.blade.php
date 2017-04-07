@extends('laralum::layouts.master')
@section('icon', 'ion-eye')
@section('title', __('laralum_forum::general.view_thread'))
@section('subtitle', __('laralum_forum::general.view_thread_desc', ['id' => $thread->id]))
@section('breadcrumb')
    <ul class="uk-breadcrumb">
        <li><a href="{{ route('laralum::index') }}">@lang('laralum_forum::general.home')</a></li>
        <li><a href="{{ route('laralum::forum.categories.index') }}">@lang('laralum_forum::general.category_list')</a></li>
        <li><a href="{{ route('laralum::forum.categories.show', ['category' => $thread->category->id]) }}" >@lang('laralum_forum::general.category_threads')</a></li>
        <li><span>@lang('laralum_forum::general.thread_id', ['id' => $thread->id])</span></li>
    </ul>
@endsection
@section('content')
<div class="uk-container uk-container-large">
    <div class="uk-child-width-1-1@s uk-grid-match" uk-grid>
    <div>
        <div class="uk-card uk-card-default uk-card-body">
            <article class="uk-article">

                <h1 class="uk-article-title"><a class="uk-link-reset" href="">{{ $thread->title }}</a></h1>

                <p class="uk-article-meta">@lang('laralum_forum::general.written_by', ['username' => $thread->user->name, 'time_ago' => $thread->created_at->diffForHumans(), 'cat' => $thread->category->title])</p>

                <p>{!! $thread->content !!}</p>

                <br>
                <div class="uk-grid-small uk-child-width-1-1" uk-grid>
                    <span>
                        <a class="uk-button uk-button-text" href="#comments" uk-scroll>{{ trans_choice('laralum_forum::general.comments_choice', $thread->comments->count(), ['num' => $thread->comments->count()]) }}</a>
                        <a class="uk-button uk-button-text uk-align-right" href="{{ route('laralum::forum.categories.threads.destroy.confirm', ['category' => $thread->category->id, 'thread' => $thread->id]) }}"> <i style="font-size:18px;" class="icon ion-trash-b"></i> @lang('laralum_forum::general.delete_thread')</a>
                        <a class="uk-button uk-button-text uk-align-right" href="{{ route('laralum::forum.categories.threads.edit', ['category' => $thread->category->id, 'thread' => $thread->id]) }}"><i style="font-size:18px;" class="icon ion-edit"></i> @lang('laralum_forum::general.edit_thread')</a>
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
                                    <a class="uk-button uk-button-text uk-align-right" href="{{ route('laralum::forum.categories.threads.comments.destroy.confirm',['category' => $thread->category->id, 'thread' => $thread->id, 'comment' => $comment->id ]) }}"><i style="font-size:18px;" class="icon ion-trash-b"></i> @lang('laralum_forum::general.delete')</a>
                                @endcan
                                @can('update', $comment)
                                    <button class="uk-button uk-button-text uk-align-right edit-comment-button" data-comment="{{ $comment->comment }}" data-url="{{ route('laralum::forum.categories.threads.comments.update',['category' => $thread->category->id, 'thread' => $thread->id, 'comment' => $comment->id ]) }}"><i style="font-size:18px;" class="icon ion-edit"></i> @lang('laralum_forum::general.edit')</button>
                                @endcan
                                <p class="comment">{{ $comment->comment }}</p>
                            </div>
                        </article>
                        <br>
                    @endcan
                @endforeach
                @can('create', \Laralum\Forum\Models\Comment::class)
                <article class="uk-comment uk-comment-primary">
                    <header class="uk-comment-header uk-grid-medium uk-flex-middle" uk-grid>
                        <div class="uk-width-auto">
                            <img class="uk-comment-avatar uk-border-circle" src="{{ \Laralum\Users\Models\User::findOrFail(Auth::id())->avatar() }}" width="80" height="80" alt="">
                        </div>
                        <div class="uk-width-expand">
                            <h4 class="uk-comment-title uk-margin-remove"><span>{{ \Laralum\Users\Models\User::findOrFail(Auth::id())->name }}</span></h4>
                        </div>
                    </header>

                    <div class="uk-comment-body">

                        <form class="uk-form-stacked" method="POST" action="{{ route('laralum::forum.categories.threads.comments.store',['category' => $thread->category->id, 'thread' => $thread->id]) }}">
                            {{ csrf_field() }}
                            <fieldset class="uk-fieldset">
                                <div class="uk-margin">
                                    <textarea name="comment" class="uk-textarea" rows="8" placeholder="{{ __('laralum_forum::general.add_a_comment') }}">{{ old('comment') }}</textarea>
                                </div>
                                <div class="uk-margin">
                                    <button type="submit" class="uk-button uk-button-primary">
                                        <span class="ion-forward"></span>&nbsp; @lang('laralum_forum::general.submit')
                                    </button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </article>
                @endcan
            </div>
        </div>
        <form class="uk-form-stacked uk-hidden" id="edit-comment-form" method="POST">
            {{ csrf_field() }}
            {{ method_field('PATCH') }}
            <fieldset class="uk-fieldset">
                <div class="uk-margin">
                    <textarea name="comment" class="uk-textarea" id="comment-textarea" rows="8" placeholder="{{ __('laralum_forum::general.edit_a_comment') }}">{{ old('comment') }}</textarea>
                </div>
                <div class="uk-margin">
                    <button type="submit" class="uk-button uk-button-primary">
                        <span class="ion-forward"></span>&nbsp; @lang('laralum_forum::general.submit')
                    </button>
                </div>
            </fieldset>
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
                $('.comment').removeClass("uk-hidden"); {{-- Show all comments --}}
                $(this).next().html('<form class="uk-form-stacked edit-comment-form uk-animation-scale-up" id="edit-comment-form" action="' + url + '" method="POST">' + form + '</form><p class="comment uk-hidden">'+comment+'</p>');
            });
        });
    </script>
@endsection
