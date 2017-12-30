<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Webpatser\Countries\Countries;
use App\Voice;
use App\Address;
use App\User;

use App\Http\Requests\StoreProfile;
use App\Http\Requests\ChangePassword;

use Auth;
use Hash;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $user = User::find($id);

        if (($id !== Auth::user()->id && !Auth::user()->can('manageUsers')) || !$user) {
            $request->session()->flash('alert-danger', trans('You are not allowed to do this.'));

            return redirect()->back();
        }

        $nullOption = [null => trans('--- Please choose ---')];

        $countries = (new Countries())->getListForSelect();
        $countries = $nullOption + $countries;
        $voices = Voice::getListForSelect();
        $voices = $nullOption + $voices;

        $user = User::find($id);

        return view('profile.edit')->with([
            'countries' => $countries,
            'voices' => $voices,
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreProfile $request)
    {
        $input = $request->all();
        $user = User::find($input['user_id']);

        if (isset($input['voice'])) {
            $primaryVoice = $user->voice();

            if ($primaryVoice && $primaryVoice->id !== $input['voice']) {
                $user->voices()->detach($primaryVoice);
            }

            $user->voices()->sync([$input['voice'] => ['primary' => true]], false);
        }

        $addressFields = ['street' => $input['street'], 'zip' => $input['zip'], 'city' => $input['city'], 'country_id' => $input['country']];
        if (
            $addressFields['street'] ||
            $addressFields['zip'] ||
            $addressFields['city'] ||
            $addressFields['country_id']
        ) {
            if ($user->address) {
                $user->address->update($addressFields);
            } else {
                $user->address()->associate(Address::create($addressFields));
            }
        }

        $user->update($input);

        $request->session()->flash('alert-success', trans('Profile saved.'));

        return redirect()->back();
    }

    public function changePassword()
    {
        return view('profile.changePassword');
    }

    public function updatePassword(ChangePassword $request)
    {
        if (Hash::check($request->get('old-password'), Auth::user()->password)) {
            Auth::user()->update([
                'password' => bcrypt($request->get('password'))
            ]);

            $request->session()->flash('alert-success', trans('Password updated.'));
            return redirect()->route('profile.edit');
        } else {
            return redirect()->back()->withErrors(['old-password' => 'Incorrect password.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
