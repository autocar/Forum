@extends('laralum::layouts.master')
@php
    $settings = \Laralum\Forum\Models\Settings::first();
@endphp
@section('icon', 'ion-edit')
@section('title', __('laralum_forum::general.edit_thread'))
@section('subtitle', __('laralum_forum::general.edit_thread_desc', ['id' => $thread->id, 'time_ago' => $thread->created_at->diffForHumans()]))
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
        <li><span>@lang('laralum_forum::general.edit_thread')</span></li>
    </ul>
@endsection
@section('content')
    @include('laralum_forum::laralum.threads.form', [
        'action' => route('laralum::forum.categories.threads.update', ['category' => $thread->category->id, 'thread' => $thread->id]),
        'button' => __('laralum_forum::general.edit_thread'),
        'title' => __('laralum_forum::general.edit_thread'),
        'method' => 'PATCH',
        'thread' => $thread,
        'category' => $thread->category,
        'cancel' => route('laralum::forum.categories.threads.show', ['category' => $thread->category->id, 'thread' => $thread->id])
    ])
@endsection
