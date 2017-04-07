@extends('laralum::layouts.master')
@section('icon', 'ion-plus-round')
@section('title', __('laralum_forum::general.create_category'))
@section('subtitle', __('laralum_forum::general.create_category_desc'))
@section('breadcrumb')
    <ul class="uk-breadcrumb">
        <li><a href="{{ route('laralum::index') }}">@lang('laralum_forum::general.home')</a></li>
        <li><a href="{{ route('laralum::forum.categories.index') }}">@lang('laralum_forum::general.category_list')</a></li>
        <li><span>@lang('laralum_forum::general.create_category')</span></li>
    </ul>
@endsection
@section('content')
    @include('laralum_forum::laralum.categories.form', [
        'action' => route('laralum::forum.categories.store'),
        'button' => __('laralum_forum::general.create_category'),
        'title' => __('laralum_forum::general.create_category'),
        'cancel' => route('laralum::forum.categories.index')
    ])
@endsection
