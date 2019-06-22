<?php

namespace App\Http\Controllers;

use App\Address;
use App\Http\Requests\StoreUser;
use App\Http\Resources\AuthUserResource;
use App\Http\Resources\UserResource;
use App\Mail\UserCreated;
use App\Role;
use App\Services\GetFilteredUsersService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurrent()
    {
        return response()->json(new AuthUserResource(Auth::user()));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $request->all();

        $users = (new GetFilteredUsersService())
            ->handle($filters, $request->get('search'), $request->get('sort'), $request->get('dir'));

        return response()->json(UserResource::collection($users));
    }

    /**
     * @param StoreUser $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUser $request)
    {
        $data = $request->all();
        $password = str_random(8);

        $data['password'] = bcrypt($password);

        $user  = User::create($data);

        $memberRole = Role::where('name', '=', 'member')->first();

        $user->roles()->attach($memberRole->id);

        Mail::to($user)->send(new UserCreated($user, $password, App::getLocale()));

        return response()->json(new UserResource($user));
    }

    /**
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(new UserResource(User::find($id)));
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        $updateArray = $request->all();

        $user->update($updateArray);

        if (!$user->address) {
            $address = $request->get('address');
            $address['user_id'] = $user->id;
            Address::create($address);
        } else {
            $user->address->update($request->get('address'));
        }

        return response()->json(new UserResource($user));
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json();
    }

    public function export(Request $request)
    {
        $filters = $request->all();

        $users = (new GetFilteredUsersService())->handle($filters, $request->get('search'), $request->get('sort'), $request->get('dir'))->toArray();

        Excel::create('user_export', function ($excel) use ($users) {
            $excel->sheet('users', function($sheet) use ($users) {

                $sheet->fromArray($users);

            });
        })->download('csv');
    }

    public function multiArchive(Request $request)
    {
        $users = User::find($request->get('users'));

        foreach ($users as $user) {
            $user->delete();
        }

        return response()->json([
            'message' => __('Users successfully archived.')
        ]);
    }
}
