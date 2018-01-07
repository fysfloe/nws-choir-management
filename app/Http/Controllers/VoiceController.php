<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Voice;
use App\User;

use App\Http\Requests\StoreVoice;

use Auth;

class VoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $voices = Voice::all();

        return view('voice.index')->with(['voices' => $voices]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('voice.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVoice $request)
    {
        $voice = $request->all();

        $voice['slug'] = str_slug($voice['name'], '-');

        $voice = Voice::create($voice);

        return redirect('admin/voices');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Voice $voice)
    {
        return view('voice.show')->with(['voice' => $voice]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Voice $voice)
    {
        return view('voice.edit')->with(['voice' => $voice]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreVoice $request, Voice $voice)
    {
        $voice->update($request->all());

        return redirect('admin/voices');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $voice = Voice::find($id);

        $voice->delete();

        return redirect()->back();
    }

    public function showSet(User $user)
    {
        return view('voice.set')->with([
            'user' => $user,
            'voices' => Voice::getListForSelect()
        ]);
    }

    public function set(Request $request, User $user)
    {
        $voice_id = $request->get('voice');

        $user->voice_id = $voice_id;
        $user->save();

        $request->session()->flash('alert-success', __('Voice successfully set.'));

        return redirect()->back();
    }
}
