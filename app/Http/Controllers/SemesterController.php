<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Semester;
use App\User;
use App\Voice;

use App\Events\SemesterAnsweredEvent;
use App\Http\Requests\StoreSemester;
use App\Services\GetFilteredUsersService;
use App\Http\Resources\UserResource;

use Auth;
use URL;

class SemesterController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->breadcrumbs->addCrumb(__('Semesters'), 'semesters');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $semesters = Semester::all();

        return view('semester.index')->with([
            'semesters' => $semesters,
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
        $this->breadcrumbs->addCrumb(__('New Semester'), 'create');

        return view('semester.create')->with([
            'breadcrumbs' => $this->breadcrumbs
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSemester $request)
    {
        $semester = $request->all();

        if ($semester['start_date'] >= $semester['end_date']) {
            return redirect()->back()
                ->withInput($request->input)
                ->withErrors(['end_date' => __('The end date must be greater than the start date.')]);
        }

        Auth::user()->semestersCreated()->create($semester);

        $request->session()->flash('alert-success', __('Semester successfully created!'));

        return redirect()->route('semesters.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Semester $semester)
    {
        $this->breadcrumbs->addCrumb($semester->__toString(), URL::action('SemesterController@edit', ['semester' => $semester]));

        return view('semester.show')->with([
            'semester' => $semester,
            'tab' => 'show',
            'breadcrumbs' => $this->breadcrumbs
        ]);
    }

    public function participants(Request $request, Semester $semester)
    {
        $this->breadcrumbs->addCrumb($semester->__toString(), URL::action('SemesterController@participants', ['semester' => $semester]));

        $sort = $request->get('sort');

        if (!$sort) $sort = 'id';

        $dir = $request->get('dir');
        $search = $request->get('search');
        $voices = $request->get('voices');

        $query = "SELECT users.* "
            . "FROM users
            LEFT OUTER JOIN user_semester ON users.id = user_semester.user_id ";

        $query .= "WHERE users.deleted_at IS NULL
        AND user_semester.semester_id = $semester->id
        AND user_semester.accepted = 1
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
        if ($voices !== null && count($voices) > 0) {
            $voices = implode(',', $voices);
            $query .= "AND users.voice IN ($voices) ";
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

        return view('semester.participants')->with([
            'semester' => $semester,
            'participants' => $users,
            'activeFilters' => $activeFilters,
            'voices' => $voices,
            'route' => ['semester.participants', $semester],
            'tab' => 'participants',
            'breadcrumbs' => $this->breadcrumbs
        ]);
    }

    public function loadParticipants(Request $request, Semester $semester)
    {
        $filters = $request->all();

        $users = (new GetFilteredUsersService())->semesterParticipants($semester, $filters, $request->get('search'), $request->get('sort'), $request->get('dir'));

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

        return json_encode(UserResource::collection($users));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Semester $semester)
    {
        $this->breadcrumbs->addCrumb($semester->__toString(), URL::action('SemesterController@show', ['semester' => $semester]));
        $this->breadcrumbs->addCrumb(__('Edit'), URL::action('SemesterController@edit', ['semester' => $semester]));

        return view('semester.edit')->with([
            'semester' => $semester,
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
    public function update(StoreSemester $request, Semester $semester)
    {
        $semesterInput = $request->all();

        if ($semesterInput['start_date'] >= $semesterInput['end_date']) {
            return redirect()->back()
                ->withInput($request->input)
                ->withErrors(['end_date' => __('The end date must be greater than the start date.')]);
        }

        $semester->update($semesterInput);

        $request->session()->flash('alert-success', __('Semester successfully updated!'));

        return redirect()->route('semesters.index');
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

    public function showAddUser(Request $request, Semester $semester)
    {
        $semesterUsers = $semester->promises()->pluck('id')->toArray();
        $users = User::whereNotIn('id', $semesterUsers)->get();

        $usersForSelect = [];

        foreach ($users as $user) {
            $usersForSelect[$user->id] = "$user->firstname $user->surname";
        }

        return view('semester.addUser')->with([
            'semester' => $semester,
            'users' => $usersForSelect
        ]);
    }

    public function addUser(Request $request, Semester $semester)
    {
        $usersToAdd = $request->get('users');

        foreach ($usersToAdd as $user_id) {
            $user = User::find($user_id);

            $semester->participants()->syncWithoutDetaching([$user_id => ['accepted' => true]]);
        }

        return redirect()->route('semester.participants', $semester);
    }

    public function accept(Request $request, Semester $semester)
    {
        $user = Auth::user();

        $semester->participants()->syncWithoutDetaching([$user->id => ['accepted' => true]]);

        event(new SemesterAnsweredEvent($semester, $user, true));
        
        return redirect()->back();
    }

    public function decline(Semester $semester)
    {
        $user = Auth::user();

        $semester->participants()->syncWithoutDetaching([$user->id => ['accepted' => false]]);

        if (count($semester->concerts) > 0) {
            foreach ($semester->concerts as $concert) {
                $concert->participants()->syncWithoutDetaching([$user->id => ['accepted' => false]]);
            }
        }

        return redirect()->back();
    }

    public function removeParticipant(Semester $semester, Request $request)
    {
        $user = User::find($request->get('user_id'));
        
        if ($user !== null) {
            $semester->participants()->detach($user);

            $request->session()->flash('alert-success', __('Participant successfully removed from the semester.'));
        } else {
            $request->session()->flash('alert-danger', __('Participant could not be found.'));
        }

        return redirect()->back();
    }

    public function removeParticipants(Semester $semester, Request $request)
    {
        $users = $request->get('users');

        foreach ($users as $userId) {
            $user = User::find($userId);

            $semester->participants()->detach($user);
        }

        return response()->json([
            'message' => __('Participants successfully removed from the semester.')
        ]);
    }
}
