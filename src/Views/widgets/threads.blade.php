{!!
    \ConsoleTVs\Charts\Facades\Charts::multiDatabase('line', 'highcharts')
    ->title(__('laralum_forum::general.latest_forum_threads'))
    ->dataset(__('laralum_forum::general.new_threads'), \Laralum\Forum\Models\Thread::all())
    ->elementLabel(__('laralum_forum::general.new_threads'))->lastByDay(7, true)->render();
!!}
