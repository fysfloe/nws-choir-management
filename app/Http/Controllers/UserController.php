<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Role;
use App\Voice;

use Auth;

use App\Http\Requests\StoreUser;

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
        $sort = $request->get('sort');

        if (!$sort) $sort = 'id';

        $dir = $request->get('dir');
        $search = $request->get('search');
        $voices = $request->get('voices');

        $query = "SELECT users.* "
            . "FROM users ";

        $query .= "WHERE users.deleted_at IS NULL AND (users.firstname LIKE '%$search%' OR users.surname LIKE '%$search%' OR users.email LIKE '%$search%') ";

        $ageFrom = $request->get('age-from');
        if ($ageFrom) {
            $minDate = (new \DateTime("- $ageFrom years"))->format('Y-m-d');
            $query .= "AND users.birthdate < '$minDate' ";
        }

        $ageTo = $request->get('age-to');
        if ($ageTo) {
            $ageTo += 1;
            $maxDate = (new \DateTime("- $ageTo years"))->format('Y-m-d');
            $query .= "AND users.birthdate >= '$maxDate' ";
        }
        if ($voices !== null && count($voices) > 0) {
            $voices = implode(',', $voices);
            $query .= "AND users.voice_id IN ($voices) ";
        }

        $query .= "GROUP BY users.id ORDER BY $sort $dir";

        $users = User::fromQuery($query);

        $activeFilters = [];
        foreach ($request->all() as $key => $val) {
            if ($val) {
                if ($key === 'voices') {
                    $voices = [];
                    foreach ($val as $voice_id) {
                        $voices[] = Voice::find($voice_id)->name;
                    }

                    $activeFilters['voices'] = implode(', ', $voices);
                } else {
                    $activeFilters[$key] = $val;
                }
            }
        }

        $voices = Voice::getListForSelect();

        return view('user.index')->with([
            'users' => $users,
            'activeFilters' => $activeFilters,
            'voices' => $voices,
            'route' => 'users.index',
            'breadcrumbs' => $this->breadcrumbs
        ]);
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
            'breadcrumbs' => $this->breadcrumbs
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
    public function destroy($id)
    {
        //
    }
}
