<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Role;

class RoleController extends Controller
{
    public function showSet(User $user)
    {
        $role = $user->roles()->first();

        return view('role.set')->with([
            'user' => $user,
            'roles' => Role::getListForSelect(),
            'role' => $role
        ]);
    }

    public function set(Request $request)
    {
        $user = User::find($request->get('user'));
        $role_id = $request->get('role');

        $currentRole = $user->roles()->first();

        if ($currentRole && $currentRole->id !== $role_id) {
            $user->roles()->detach($currentRole);
        }

        $user->roles()->sync($role_id, false);

        $request->session()->flash('alert-success', __('Role successfully set.'));

        return redirect()->back();
    }
}
