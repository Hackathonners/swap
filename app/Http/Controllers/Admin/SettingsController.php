<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\Judite\Models\Settings;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\UpdateRequest;

class SettingsController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return view('settings.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Settings\UpdateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request)
    {
        DB::transaction(function () use ($request) {
            $settings = Settings::first();
            $settings->update($request->input());
        });
        flash('Settings were successfully updated.')->success();

        return redirect()->route('settings.edit');
    }
}
