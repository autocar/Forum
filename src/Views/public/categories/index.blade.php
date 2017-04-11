<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>@lang('laralum_forum::general.category_list') - {{ Laralum\Settings\Models\Settings::first()->appname }}</title>
    </head>
    <body>
        <h1>@lang('laralum_forum::general.category_list')</h1>
        @can('publicCreate', \Laralum\Forum\Models\Thread::class)
            <a href="{{ route('laralum_public::forum.threads.create') }}">@lang('laralum_forum::general.create_thread')</a>
        @endcan
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>@lang('laralum_forum::general.name')</th>
                    <th>@lang('laralum_forum::general.actions')</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td>
                                <a href="{{ route('laralum_public::forum.categories.show', ['category' => $category->id]) }}">
                                    @lang('laralum_forum::general.view')
                                </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </body>
</html>
