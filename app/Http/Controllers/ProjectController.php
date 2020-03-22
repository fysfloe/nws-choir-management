<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Events\ProjectAnsweredEvent;
use App\Exports\ProjectUsersExport;
use App\Http\Requests\StoreProject;
use App\Http\Resources\ProjectGridResource;
use App\Http\Resources\ProjectListResource;
use App\Http\Resources\ProjectParticipantResource;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\UserResource;
use App\Project;
use App\Semester;
use App\Services\GetFilteredProjectsService;
use App\Services\GetFilteredUsersService;
use App\User;
use App\Voice;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->breadcrumbs->addCrumb(__('Projects'), 'projects');
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

        $query = "SELECT projects.* "
            . "FROM projects ";

        $query .= "WHERE projects.deleted_at IS NULL AND projects.title LIKE '%$search%' ";

        $query .= "GROUP BY projects.id ORDER BY $sort $dir";

        $projects = Project::fromQuery($query);

        $activeFilters = [];
        foreach ($request->all() as $key => $val) {
            if ($val) $activeFilters[$key] = $val;
        }

        return response()->json(ProjectListResource::collection($projects));
    }

    public function loadItems(Request $request)
    {
        $projects = (new GetFilteredProjectsService())
            ->handle(Auth::user(), $request->get('search'), $request->get('sort'), $request->get('dir'));

        return json_encode(ProjectListResource::collection($projects));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->breadcrumbs->addCrumb(__('New Project'), 'create');

        $voices = Voice::getListForSelect();
        $semesters = Semester::getListForSelect();

        $nullOption = [null => __('--- Please choose ---')];
        $semesters = $nullOption + $semesters;

        return view('project.create')->with([
            'voices' => $voices,
            'semesters' => $semesters,
            'breadcrumbs' => $this->breadcrumbs
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProject $request)
    {
        $project = $request->all();
        $project['slug'] = str_slug($project['title'], '-');

        $project = Auth::user()->projectsCreated()->create($project);

        return response()->json(new ProjectResource($project));
    }

    /**
     * Display the specified resource.
     *
     * @param Project $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        $this->breadcrumbs->addCrumb($project->title, $project->slug);

        return response()->json(new ProjectResource($project));
    }

    public function removeComment(Project $project, Comment $comment, Request $request)
    {
        if ($comment->user == Auth::user()) {
            $comment->delete();

            $request->session()->flash('alert-success', __('Comment successfully removed.'));
        } else {
            $request->session()->flash('alert-danger', __('You cannot remove other peoples comments.'));
        }

        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        $this->breadcrumbs->addCrumb($project->title, $project->slug);

        $semesters = Semester::getListForSelect();
        $voices = Voice::getListForSelect();

        $nullOption = [null => __('--- Please choose ---')];
        $semesters = $nullOption + $semesters;

        return view('project.edit')->with([
            'project' => $project,
            'semesters' => $semesters,
            'voices' => $voices,
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
    public function update(StoreProject $request, $id)
    {
        $project = Project::find($id);
        $input = $request->all();
        $voices = $project['voices'];
        $voiceNumbers = $project['voiceNumbers'];

        $input['slug'] = str_slug($input['title'], '-');

        $project = $project->update($input);

        foreach ($voices as $key => $voice) {
            if ($voice !== null && $voiceNumbers[$key] !== null) {
                $project->voices()->syncWithoutDetaching([$voice => ['number' => $voiceNumbers[$key]]]);
            }
        }

        return response()->json();
    }

    /**
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::find($id);

        $project->delete();

        return response()->json();
    }

    /**
     * @param Request $request
     * @param Project $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function participants(Request $request, Project $project)
    {
        $filters = $request->all();

        $users = (new GetFilteredUsersService())->projectParticipants($project, $filters, $request->get('search'), $request->get('sort'), $request->get('dir'));

        $users = $users->map(function (User $user) use ($project) {
            $user->missed_rehearsals_count = DB::table('rehearsals')
               ->distinct()
               ->leftJoin('user_rehearsal', function ($join) use ($user) {
                   $join->on('user_rehearsal.rehearsal_id', '=', 'rehearsals.id')
                       ->where('user_rehearsal.user_id', $user->id);
               })
               ->where(function ($query) {
                   $query->where('user_rehearsal.confirmed', false)
                       ->orWhereNull('user_rehearsal.user_id');
               })
               ->where('rehearsals.date', '<', (new \DateTime())->format('Y-m-d'))
               ->where('rehearsals.project_id', $project->id)
               ->count();

           return $user;
        });

        return response()->json(ProjectParticipantResource::collection($users));
    }

    /**
     * @param Project $project
     * @return \Illuminate\Http\JsonResponse|Response
     */
    public function accept(Project $project)
    {
        $user = Auth::user();
        if (!$user->voice) {
            return new Response(__('You need to set your primary voice before you can participate in a project!'), 400);
        }

        $project->participants()->syncWithoutDetaching([$user->id => ['accepted' => true, 'voice_id' => $user->voice->id]]);

        event(new ProjectAnsweredEvent($project, $user, true));

        return response()->json(new ProjectResource($project));
    }

    /**
     * @param Project $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function decline(Project $project)
    {
        $user = Auth::user();

        $project->participants()->syncWithoutDetaching([$user->id => ['accepted' => false]]);

        event(new ProjectAnsweredEvent($project, $user, false));

        return response()->json(new ProjectResource($project));
    }

    /**
     * @param Request $request
     * @param Project $project
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function setVoice(Request $request, Project $project)
    {
        $voice_id = $request->get('voice');

        $users = User::find($request->get('users'));

        foreach ($users as $user) {
            $project->participants()->syncWithoutDetaching([$user->id => ['voice_id' => $voice_id]]);
            $user->voices()->syncWithoutDetaching([$voice_id]);
        }

        return response()->json();
    }

    /**
     * @param Request $request
     * @param Project $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function setVoices(Request $request, Project $project)
    {
        $users = json_decode($request->get('users'));
        $voice_id = $request->get('voice');

        $users = User::find($users);

        foreach ($users as $user) {
            $project->participants()->syncWithoutDetaching([$user->id => ['voice_id' => $voice_id]]);
            $user->voices()->syncWithoutDetaching([$voice_id]);
        }

        $request->session()->flash('alert-success', __('Voice successfully set.'));

        return response()->json();
    }

    /**
     * @param Project $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function otherUsers(Project $project)
    {
        $projectUsers = $project->promises()->pluck('id')->toArray();
        $users = User::whereNotIn('id', $projectUsers)->orderBy('surname')->get();

        return response()->json(UserResource::collection($users));
    }

    /**
     * @param Request $request
     * @param Project $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function addParticipants(Request $request, Project $project)
    {
        $usersToAdd = $request->get('users');

        foreach ($usersToAdd as $user_id) {
            $user = User::find($user_id);

            $project->participants()->syncWithoutDetaching([$user_id => ['accepted' => true, 'voice_id' => ($user->voice ? $user->voice->id : null)]]);

            event(new ProjectAnsweredEvent($project, $user, true));
        }

        return response()->json();
    }

    /**
     * @param Request $request
     * @param Project $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeParticipants(Request $request, Project $project)
    {
        $users = $request->get('users');

        foreach ($users as $userId) {
            $user = User::find($userId);

            $project->participants()->detach($user);
        }

        return response()->json();
    }

    /**
     * @param Project $project
     * @param Request $request
     * @return Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportParticipants(Project $project, Request $request)
    {
        $filters = $request->all();

        $users = (new GetFilteredUsersService())->projectParticipants($project, $filters, $request->get('search'), $request->get('sort'), $request->get('dir'));

        return (new ProjectUsersExport($project, $users))->download($project->title . '_participants.xlsx');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function options()
    {
        $projects = Project::where('deleted_at', '=', null)
            ->orderBy('title')
            ->get();

        $projectOptions = [];

        foreach ($projects as $project) {
            $projectOptions[$project->id] = $project->title;
        }

        return response()->json($projectOptions);
    }

    /**
     * @param Project $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function grid(Project $project)
    {
        return response()->json(new ProjectGridResource($project));
    }
}
