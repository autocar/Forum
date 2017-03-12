@extends('laralum::layouts.master')
@section('icon', 'ion-edit')
@section('title', __('laralum_forum::general.edit_thread'))
@section('subtitle', __('laralum_forum::general.edit_thread_desc', ['id' => $thread->id, 'time_ago' => $thread->created_at->diffForHumans()]))
@section('breadcrumb')
    <ul class="uk-breadcrumb">
        <li><a href="{{ route('laralum::index') }}">@lang('laralum_forum::general.home')</a></li>
        <li><a href="{{ route('laralum::forum.categories.index') }}">@lang('laralum_forum::general.category_list')</a></li>
        <li><span>@lang('laralum_forum::general.edit_thread')</span></li>
    </ul>
@endsection
@section('content')
    @include('laralum_forum::threads.form', [
        'action' => route('laralum::forum.categories.threads.update', ['category' => $thread->category->id, 'thread' => $thread->id]),
        'button' => __('laralum_forum::general.edit_thread'),
        'title' => __('laralum_forum::general.edit_thread'),
        'method' => 'PATCH',
        'thread' => $thread,
        'category' => $thread->category,
        'cancel' => route('laralum::forum.categories.threads.show', ['category' => $thread->category->id, 'thread' => $thread->id])
    ])
@endsection
