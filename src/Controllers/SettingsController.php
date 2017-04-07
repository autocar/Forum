<?php

namespace Laralum\Forum\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laralum\Forum\Models\Settings;

class SettingsController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->authorize('update', Settings::class);

        $this->validate($request, [
            'text_editor' => 'required|in:plain-text,markdown,wysiwyg',
            'public_url' => 'required|max:255',
        ]);

        Settings::first()->update([
            'text_editor' => $request->input('text_editor'),
            'public_url' => $request->input('public_url'),
        ]);

        return redirect()->route('laralum::settings.index', ['p' => 'Forum'])->with('success', __('laralum_forum::general.forum_settings_updated'));
    }
}
