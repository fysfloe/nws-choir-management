<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Rehearsal;
use App\Semester;
use App\User;
use App\Voice;

use App\Http\Requests\StoreRehearsal;
use Auth;

class RehearsalController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->breadcrumbs->addCrumb(__('Rehearsals'), 'rehearsals');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rehearsals = Rehearsal::where('deleted_at', '=', null)->get();

        return view('rehearsal.index')->with([
            'rehearsals' => $rehearsals,
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
        $this->breadcrumbs->addCrumb(__('New Rehearsal'), 'create');

        $semesters = Semester::getListForSelect();

        $nullOption = [null => __('--- Please choose ---')];
        $semesters = $nullOption + $semesters;

        return view('rehearsal.create')->with([
            'breadcrumbs' => $this->breadcrumbs,
            'semesters' => $semesters
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRehearsal $request)
    {
        $rehearsal = $request->all();

        $rehearsal = Auth::user()->rehearsalsCreated()->create($rehearsal);

        return redirect('rehearsals');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Rehearsal $rehearsal)
    {
        $this->breadcrumbs->addCrumb($rehearsal->__toString(), $rehearsal);

        return view('rehearsal.show')->with([
            'tab' => 'show',
            'rehearsal' => $rehearsal,
            'breadcrumbs' => $this->breadcrumbs
        ]);
    }

    public function participants(Request $request, Rehearsal $rehearsal)
    {
        $this->breadcrumbs->addCrumb($rehearsal->__toString(), $rehearsal);

        $sort = $request->get('sort');

        if (!$sort) $sort = 'id';

        $dir = $request->get('dir');
        $search = $request->get('search');
        $voices = $request->get('voices');

        $query = "SELECT users.* "
            . "FROM users
            LEFT OUTER JOIN user_rehearsal ON users.id = user_rehearsal.user_id ";

        $query .= "WHERE users.deleted_at IS NULL
        AND user_rehearsal.rehearsal_id = $rehearsal->id
        AND user_rehearsal.accepted = 1
        AND (users.firstname LIKE '%$search%' OR users.surname LIKE '%$search%' OR users.email LIKE '%$search%') ";

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
        if (count($voices) > 0) {
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

        return view('rehearsal.participants')->with([
            'tab' => 'participants',
            'rehearsal' => $rehearsal,
            'participants' => $users,
            'activeFilters' => $activeFilters,
            'voices' => $voices,
            'route' => ['rehearsal.participants', $rehearsal],
            'breadcrumbs' => $this->breadcrumbs
        ]);
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
        $rehearsal = Rehearsal::find($id);

        $rehearsal->delete();

        return redirect()->back();
    }

    public function accept(Rehearsal $rehearsal)
    {
        $user = Auth::user();

        $rehearsal->users()->syncWithoutDetaching([$user->id => ['accepted' => true]]);

        return redirect()->back();
    }

    public function decline(Rehearsal $rehearsal)
    {
        $user = Auth::user();

        $rehearsal->users()->syncWithoutDetaching([$user->id => ['accepted' => false]]);

        return redirect()->back();
    }

    public function showAddUser(Request $request, Rehearsal $rehearsal)
    {
        $rehearsalUsers = $rehearsal->promises()->pluck('id')->toArray();
        $users = User::whereNotIn('id', $rehearsalUsers)->get();

        $usersForSelect = [];

        foreach ($users as $user) {
            $usersForSelect[$user->id] = "$user->firstname $user->surname";
        }

        return view('rehearsal.addUser')->with([
            'rehearsal' => $rehearsal,
            'users' => $usersForSelect
        ]);
    }

    public function addUser(Request $request, Rehearsal $rehearsal)
    {
        $usersToAdd = $request->get('users');

        foreach ($usersToAdd as $user_id) {
            $user = User::find($user_id);

            $rehearsal->participants()->syncWithoutDetaching([$user_id => ['accepted' => true]]);
        }

        return redirect()->route('rehearsal.participants', $rehearsal);
    }
}