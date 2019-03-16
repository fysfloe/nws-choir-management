<?php

namespace App\Http\Controllers;

use App\Events\SemesterAnsweredEvent;
use App\Http\Requests\StoreSemester;
use App\Http\Resources\SemesterResource;
use App\Http\Resources\UserResource;
use App\Semester;
use App\Services\GetFilteredUsersService;
use App\User;
use App\Voice;
use Auth;
use Illuminate\Http\Request;
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

        return response()->json(SemesterResource::collection($semesters));
    }

    public function loadItems(Request $request)
    {
        $semesters = Semester::where('deleted_at', '=', null)
            ->orderBy($request->get('sort'), $request->get('dir'))
            ->get();

        return json_encode(SemesterResource::collection($semesters));
    }

    public function options()
    {
        $semesters = Semester::where('deleted_at', '=', null)
            ->orderBy('start_date')
            ->get();

        $semesterOptions = [];

        foreach ($semesters as $semester) {
            $semesterOptions[$semester->id] = $semester->name;
        }

        return response()->json($semesterOptions);
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

        $semester = Auth::user()->semestersCreated()->create($semester);

        return response()->json(new SemesterResource($semester));
    }

    /**
     * Display the specified resource.
     *
     * @param Semester $semester
     * @return \Illuminate\Http\Response
     */
    public function show(Semester $semester)
    {
        $this->breadcrumbs->addCrumb($semester->__toString(), URL::action('SemesterController@edit', ['semester' => $semester]));

        return response()->json(new SemesterResource($semester));
    }

    public function participants(Request $request, Semester $semester)
    {
        $this->breadcrumbs->addCrumb($semester->__toString(), URL::action('SemesterController@participants', ['semester' => $semester]));

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

        $voices = Voice::getListForSelect();

        return view('semester.participants')->with([
            'semester' => $semester,
            'participants' => UserResource::collection($users),
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
        $semester = Semester::find($id);

        foreach ($semester->projects as $project) {
            $project->delete();
        }

        foreach ($semester->rehearsals as $rehearsal) {
            $rehearsal->delete();
        }

        foreach ($semester->concerts as $concert) {
            $concert->delete();
        }

        $semester->delete();

        return redirect()->route('semesters');
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

    public function accept(Semester $semester)
    {
        $user = Auth::user();

        $semester->participants()->syncWithoutDetaching([$user->id => ['accepted' => true]]);

        event(new SemesterAnsweredEvent($semester, $user, true));
        
        return response()->json(new SemesterResource($semester));
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

        return response()->json(new SemesterResource($semester));
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
