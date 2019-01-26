<?php

namespace App\Http\Controllers;

use App\Concert;
use App\Http\Requests\StoreUser;
use App\Http\Resources\UserResource;
use App\Role;
use App\Services\GetFilteredUsersService;
use App\User;
use App\Voice;
use Auth;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->breadcrumbs->addCrumb(__('Users'), 'users');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $request->all();

        $users = (new GetFilteredUsersService())
            ->handle($filters, $request->get('search'), $request->get('sort'), $request->get('dir'));

        $activeFilters = [];
        foreach ($request->all() as $key => $val) {
            if ($val) {
                if ($key === 'voices') {
                    $voices = [];
                    foreach ($val as $voice_id) {
                        $voices[] = Voice::find($voice_id)->name;
                    }

                    $activeFilters['voices'] = implode(', ', $voices);
                } else if ($key === 'concerts') {
                    $concerts = [];
                    foreach ($val as $concert_id) {
                        $concerts[] = Concert::find($concert_id)->title;
                    }

                    $activeFilters['concerts'] = implode(', ', $concerts);
                } else {
                    $activeFilters[$key] = $val;
                }
            }
        }

        $voices = Voice::getListForSelect();
        $concerts = Concert::getListForSelect();
        $roles = Role::getListForSelect();

        return view('user.index')->with([
            'users' => UserResource::collection($users),
            'activeFilters' => $activeFilters,
            'voices' => $voices,
            'roles' => $roles,
            'concerts' => $concerts,
            'route' => 'users.index',
            'breadcrumbs' => $this->breadcrumbs
        ]);
    }

    public function loadUsers(Request $request)
    {
        $filters = $request->all();

        $users = (new GetFilteredUsersService())->handle($filters, $request->get('search'), $request->get('sort'), $request->get('dir'));

        $activeFilters = [];
        foreach ($request->all() as $key => $val) {
            if ($val) {
                if ($key === 'voices') {
                    $voices = [];
                    foreach ($val as $voice_id) {
                        $voices[] = Voice::find($voice_id)->name;
                    }

                    $activeFilters['voices'] = implode(', ', $voices);
                } else if ($key === 'concerts') {
                    $concerts = [];
                    foreach ($val as $concert_id) {
                        $concerts[] = Concert::find($concert_id)->title;
                    }

                    $activeFilters['concerts'] = implode(', ', $concerts);
                } else {
                    $activeFilters[$key] = $val;
                }
            }
        }

        return json_encode(UserResource::collection($users));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->breadcrumbs->addCrumb(__('New User'), 'create');

        return view('user.create')->with([
            'breadcrumbs' => $this->breadcrumbs,
            'voices' => Voice::getListForSelect()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUser $request)
    {
        $data = $request->all();

        $user  = User::create([
            'firstname' => $data['firstname'],
            'surname' => $data['surname'],
            'email' => $data['email'],
            'gender' => $data['gender'],
            'password' => bcrypt($data['password']),
            'username' => $data['firstname'] . ' ' . $data['surname'],
            'voice_id' => $data['voice_id']
        ]);

        $memberRole = Role::where('name', '=', 'member')->first();

        $user->roles()->attach($memberRole->id);;

        return redirect('/admin/users');
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
        $user->delete();

        $request->session()->flash('alert-success', __('User successfully archived.'));

        return redirect()->back();
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
