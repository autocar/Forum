@extends('laralum::layouts.master')
@section('icon', 'ion-plus-round')
@section('title', __('laralum_forum::general.create_thread'))
@section('subtitle', __('laralum_forum::general.create_thread_desc'))
@section('breadcrumb')
    <ul class="uk-breadcrumb">
        <li><a href="{{ route('laralum::index') }}">@lang('laralum_forum::general.home')</a></li>
        <li><a href="{{ route('laralum::forum.categories.index') }}">@lang('laralum_forum::general.category_list')</a></li>
        <li><a href="{{ route('laralum::forum.categories.show', ['category' => $category->id]) }}">@lang('laralum_forum::general.category_threads')</a></li>
        <li><span>@lang('laralum_forum::general.create_thread')</span></li>
    </ul>
@endsection
@section('content')
    @include('laralum_forum::threads.form', [
        'action' => route('laralum::forum.categories.threads.store', ['category' => $category->id]),
        'button' => __('laralum_forum::general.create_thread'),
        'title' => __('laralum_forum::general.create_thread'),
        'cancel' => route('laralum::forum.categories.index')
    ])
@endsection
