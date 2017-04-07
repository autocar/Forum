@extends('laralum::layouts.master')
@php
    $settings = \Laralum\Forum\Models\Settings::first();
@endphp
@section('icon', 'ion-plus-round')
@section('title', __('laralum_forum::general.create_thread'))
@section('subtitle', __('laralum_forum::general.create_thread_desc'))
@section('css')
    @if ($settings->text_editor == 'wysiwyg')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.5.5/tinymce.min.js"></script>
        <script>
            tinymce.init({ selector:'textarea',   plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table contextmenu paste code'
            ] });
        </script>
    @endif
@endsection
@section('breadcrumb')
    <ul class="uk-breadcrumb">
        <li><a href="{{ route('laralum::index') }}">@lang('laralum_forum::general.home')</a></li>
        <li><a href="{{ route('laralum::forum.categories.index') }}">@lang('laralum_forum::general.category_list')</a></li>
        <li><a href="{{ route('laralum::forum.categories.show', ['category' => $category->id]) }}">@lang('laralum_forum::general.category_threads')</a></li>
        <li><span>@lang('laralum_forum::general.create_thread')</span></li>
    </ul>
@endsection
@section('content')
    @include('laralum_forum::laralum.threads.form', [
        'action' => route('laralum::forum.categories.threads.store', ['category' => $category->id]),
        'button' => __('laralum_forum::general.create_thread'),
        'title' => __('laralum_forum::general.create_thread'),
        'cancel' => route('laralum::forum.categories.index')
    ])
@endsection
