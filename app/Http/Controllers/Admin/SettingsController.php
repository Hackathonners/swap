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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Settings\UpdateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request)
    {
        $settings = DB::transaction(function () use ($request) {
            $settings = Settings::first();
            $settings->update($request->input());

            return $settings;
        });

        return $settings;
    }
}
