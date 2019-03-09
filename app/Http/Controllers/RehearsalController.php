<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComment;
use App\Http\Requests\StoreRehearsal;
use App\Http\Resources\AuthUserResource;
use App\Http\Resources\RehearsalDetailResource;
use App\Http\Resources\RehearsalResource;
use App\Http\Resources\UserResource;
use App\Project;
use App\Rehearsal;
use App\Semester;
use App\Services\GetFilteredUsersService;
use App\User;
use App\Voice;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RehearsalController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        // $this->breadcrumbs->addCrumb(__('Rehearsals'), 'rehearsals');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $where = [['deleted_at', '=', null]];

        if ($request->get('project_id')) {
            $where[] = ['project_id', '=', $request->get('project_id')];
        }

        $rehearsals = Rehearsal::where($where)
            ->orderBy($request->get('sort'), $request->get('dir'))
            ->get();

        return response()->json(RehearsalResource::collection($rehearsals));
    }

    public function loadItems(Request $request)
    {
        $where = [['deleted_at', '=', null]];

        if ($request->get('project_id')) {
            $where[] = ['project_id', '=', $request->get('project_id')];
        }

        $rehearsals = Rehearsal::where($where)
            ->orderBy($request->get('sort'), $request->get('dir'))
            ->get();

        return new JsonResponse(RehearsalResource::collection($rehearsals));
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

        $projects = Project::getListForSelect();

        return view('rehearsal.create')->with([
            'breadcrumbs' => $this->breadcrumbs,
            'projects' => $projects,
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

        return redirect()->route('project.show', $rehearsal->project);
    }

    /**
     * Display the specified resource.
     *
     * @param  Rehearsal  $rehearsal
     * @return \Illuminate\Http\Response
     */
    public function show(Rehearsal $rehearsal)
    {
        if ($rehearsal->project) {
            $this->breadcrumbs->addCrumb($rehearsal->project->title, 'project/' . $rehearsal->project->id);
        }

        $this->breadcrumbs->addCrumb($rehearsal->title(), $rehearsal);

        return view('rehearsal.show')->with([
            'tab' => 'show',
            'rehearsal' => $rehearsal,
            'rehearsalJson' => json_encode(new RehearsalDetailResource($rehearsal)),
            'user' => json_encode(new AuthUserResource(Auth::user())),
            'breadcrumbs' => $this->breadcrumbs
        ]);
    }

    public function loadParticipants(Request $request, Rehearsal $rehearsal)
    {
        $filters = $request->all();

        $users = (new GetFilteredUsersService())->rehearsalParticipants($rehearsal, $filters, $request->get('search'), $request->get('sort'), $request->get('dir'));

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
     * Display the rehearsals participants.
     *
     * @param  Request   $request   [description]
     * @param  Rehearsal $rehearsal [description]
     * @return [type]               [description]
     */
    public function participants(Request $request, Rehearsal $rehearsal)
    {
        if ($rehearsal->project) {
            $this->breadcrumbs->addCrumb($rehearsal->project->title, 'project/' . $rehearsal->project->slug);
        }

        $filters = $request->all();

        $users = (new GetFilteredUsersService())->rehearsalParticipants($rehearsal, $filters, $request->get('search'), $request->get('sort'), $request->get('dir'));

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
            'participants' => UserResource::collection($users),
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
    public function edit(Rehearsal $rehearsal)
    {
        $this->breadcrumbs->addCrumb(__('Edit Rehearsal'), null);

        $semesters = Semester::getListForSelect();

        $nullOption = [null => __('--- Please choose ---')];
        $semesters = $nullOption + $semesters;

        $projects = Project::getListForSelect();

        return view('rehearsal.edit')->with([
            'breadcrumbs' => $this->breadcrumbs,
            'rehearsal' => $rehearsal,
            'projects' => $projects,
            'semesters' => $semesters
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rehearsal $rehearsal)
    {
        $rehearsalInput = $request->all();

        $rehearsal->update($rehearsalInput);

        return redirect()->route('rehearsal.show', $rehearsal);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $rehearsal = Rehearsal::find($id);
        $project = $rehearsal->project;

        $rehearsal->delete();

        return redirect()->route('project.show', $project);
    }

    public function accept(Request $request, Rehearsal $rehearsal, $user_id = null)
    {
        if ($user_id) {
            $user = User::find($user_id);
        } else {
            $user = Auth::user();
        }

        $rehearsal->users()->syncWithoutDetaching([$user->id => ['accepted' => true]]);

        return new Response(__('Successfully accepted the rehearsal.'), 200);
    }

    public function decline(Request $request, Rehearsal $rehearsal, $user_id = null)
    {
        if ($user_id) {
            $user = User::find($user_id);
        } else {
            $user = Auth::user();
        }

        $rehearsal->users()->syncWithoutDetaching([$user->id => ['accepted' => false]]);

        return new Response(__('Successfully accepted the rehearsal.'), 200);
    }

    public function ajaxConfirm(Request $request, Rehearsal $rehearsal, User $user)
    {
        $rehearsal->promises()->syncWithoutDetaching([$user->id => ['confirmed' => true, 'excused' => false]]);

        return response('', 200);
    }

    public function ajaxExcuse(Request $request, Rehearsal $rehearsal, User $user)
    {
        $rehearsal->promises()->syncWithoutDetaching([$user->id => ['confirmed' => false, 'excused' => true]]);

        return response('', 200);
    }

    public function ajaxSetUnexcused(Request $request, Rehearsal $rehearsal, User $user)
    {
        $rehearsal->promises()->syncWithoutDetaching([$user->id => ['confirmed' => false, 'excused' => false]]);

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

    public function comments(Rehearsal $rehearsal)
    {
        if ($rehearsal->project) {
            $this->breadcrumbs->addCrumb($rehearsal->project->title, 'project/' . $rehearsal->project->slug);
        }

        $this->breadcrumbs->addCrumb($rehearsal->title(), $rehearsal);

        return view('rehearsal.comments')->with([
            'tab' => 'comments',
            'rehearsal' => $rehearsal,
            'breadcrumbs' => $this->breadcrumbs
        ]);
    }

    public function createComment(StoreComment $request, Rehearsal $rehearsal)
    {
        $input = $request->all();
        $input['user_id'] = Auth::user()->id;
        $comment = $rehearsal->comments()->create($input);

        return redirect()->back();
    }

    public function addProjectParticipants(Rehearsal $rehearsal)
    {
        $project = $rehearsal->project;

        foreach ($project->participants as $participant) {
            $rehearsal->participants()->syncWithoutDetaching([$participant->id => ['accepted' => true]]);
        }

        return redirect()->back();
    }

    public function removeParticipant(Rehearsal $rehearsal, Request $request)
    {
        $user = User::find($request->get('user_id'));

        if ($user !== null) {
            $rehearsal->participants()->detach($user);

            $request->session()->flash('alert-success', __('Participant successfully removed from the rehearsal.'));
        } else {
            $request->session()->flash('alert-danger', __('Participant could not be found.'));
        }

        return redirect()->back();
    }

    public function removeParticipants(Rehearsal $rehearsal, Request $request)
    {
        $users = $request->get('users');

        foreach ($users as $userId) {
            $user = User::find($userId);

            $rehearsal->participants()->detach($user);
        }

        return response()->json([
            'message' => __('Participants successfully removed from the rehearsal.')
        ]);
    }
}
