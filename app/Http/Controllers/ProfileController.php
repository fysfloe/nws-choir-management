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
use URL;

class ProfileController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

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
    public function edit(Request $request, User $user)
    {
        $this->breadcrumbs->addCrumb(__('Edit Profile'), URL::action('ProfileController@edit', ['user' => $user]));

        if (($user->id !== Auth::user()->id && !Auth::user()->can('manageUsers')) || !$user) {
            $request->session()->flash('alert-danger', __('You are not allowed to do this.'));

            return redirect()->back();
        }

        $nullOption = [null => __('--- Please choose ---')];

        $countries = (new Countries())->getListForSelect();
        $countries = $nullOption + $countries;
        $voices = Voice::getListForSelect();
        $voices = $nullOption + $voices;

        return view('profile.edit')->with([
            'countries' => $countries,
            'voices' => $voices,
            'user' => $user,
            'breadcrumbs' => $this->breadcrumbs
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreProfile $request, User $user)
    {
        $input = $request->all();

        $addressFields = ['street' => $input['street'], 'zip' => $input['zip'], 'city' => $input['city'], 'country_id' => $input['country_id']];
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

        $input['country_id'] = $input['citizenship'];

        $user->update($input);

        $request->session()->flash('alert-success', __('Profile saved.'));

        return redirect()->back();
    }

    public function changePassword()
    {
        $this->breadcrumbs->addCrumb(__('Change Password'), URL::action('ProfileController@changePassword'));

        return view('profile.changePassword')->with([
            'breadcrumbs' => $this->breadcrumbs
        ]);
    }

    public function updatePassword(ChangePassword $request)
    {
        if (Hash::check($request->get('old-password'), Auth::user()->password)) {
            Auth::user()->update([
                'password' => bcrypt($request->get('password'))
            ]);

            $request->session()->flash('alert-success', __('Password updated.'));
            return redirect()->route('profile.edit', Auth::user());
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
