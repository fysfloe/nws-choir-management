<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Events\ProjectAnsweredEvent;
use App\Exports\ProjectUsersExport;
use App\Http\Requests\StoreComment;
use App\Http\Requests\StoreProject;
use App\Http\Resources\AuthUserResource;
use App\Http\Resources\ProjectListResource;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\UserResource;
use App\Project;
use App\Semester;
use App\Services\GetFilteredProjectsService;
use App\Services\GetFilteredUsersService;
use App\User;
use App\Voice;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

        return view('project.index')->with([
            'projects' => $projects,
            'activeFilters' => $activeFilters,
            'breadcrumbs' => $this->breadcrumbs
        ]);
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
        $voices = $project['voices'];
        $voiceNumbers = $project['voiceNumbers'];

        $project['slug'] = str_slug($project['title'], '-');
        $input['is_main'] = $request->has('is_main');

        $project = Auth::user()->projectsCreated()->create($project);

        foreach ($voices as $key => $voice) {
            if ($voice !== null && $voiceNumbers[$key] !== null) {
                $project->voices()->syncWithoutDetaching([$voice => ['number' => $voiceNumbers[$key]]]);
            }
        }

        $semester = Semester::find($project->semester_id);

        if ($semester) {
            foreach ($semester->participants as $user) {
                $project->participants()->syncWithoutDetaching([$user->id => ['voice_id' => $user->voice ? $user->voice->id : null, 'accepted' => $user->pivot->accepted]]);
                event(new ProjectAnsweredEvent($project, $user, true));
            }
        }

        return redirect('projects');
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

        return view('project.show')->with([
            'tab' => 'show',
            'project' => $project,
            'projectJson' => json_encode(new ProjectResource($project)),
            'user' => json_encode(new AuthUserResource(Auth::user())),
            'breadcrumbs' => $this->breadcrumbs
        ]);
    }

    public function comments(Project $project)
    {
        $this->breadcrumbs->addCrumb($project->title, $project->slug);

        return view('project.comments')->with([
            'tab' => 'comments',
            'project' => $project,
            'breadcrumbs' => $this->breadcrumbs
        ]);
    }

    public function createComment(StoreComment $request, Project $project)
    {
        $input = $request->all();
        $input['user_id'] = Auth::user()->id;
        $comment = $project->comments()->create($input);

        return redirect()->back();
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
        $input['is_main'] = $request->has('is_main');

        $project = $project->update($input);

        foreach ($voices as $key => $voice) {
            if ($voice !== null && $voiceNumbers[$key] !== null) {
                $project->voices()->syncWithoutDetaching([$voice => ['number' => $voiceNumbers[$key]]]);
            }
        }

        return redirect('projects');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::find($id);

        $project->delete();

        return redirect()->to('projects');
    }

    public function participants(Request $request, Project $project)
    {
        $this->breadcrumbs->addCrumb($project->title, $project->slug);

        $filters = $request->all();

        $users = (new GetFilteredUsersService())->projectParticipants($project, $filters, $request->get('search'), $request->get('sort'), $request->get('dir'));

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

        return view('project.participants')->with([
            'tab' => 'participants',
            'project' => $project,
            'participants' => UserResource::collection($users),
            'activeFilters' => $activeFilters,
            'voices' => $voices,
            'route' => ['project.participants', $project],
            'breadcrumbs' => $this->breadcrumbs
        ]);
    }

    public function rehearsals(Request $request, Project $project)
    {
        $this->breadcrumbs->addCrumb($project->title, $project->slug);
 
        return view('project.rehearsals')->with([
            'tab' => 'rehearsals',
            'project' => $project,
            'route' => ['project.rehearsals', $project],
            'breadcrumbs' => $this->breadcrumbs
        ]);
    }

    public function loadParticipants(Request $request, Project $project)
    {
        $filters = $request->all();

        $users = (new GetFilteredUsersService())->projectParticipants($project, $filters, $request->get('search'), $request->get('sort'), $request->get('dir'));

        return json_encode(UserResource::collection($users));
    }

    public function voices(Request $request, Project $project)
    {
        $this->breadcrumbs->addCrumb($project->title, $project->slug);

        return view('project.voices')->with([
            'tab' => 'voices',
            'project' => $project,
            'breadcrumbs' => $this->breadcrumbs
        ]);
    }

    public function accept(Request $request, Project $project)
    {
        $user = Auth::user();
        if (!$user->voice) {
            $request->session()->flash('alert-danger', __('You need to set your primary voice before you can participate in a project!'));

            return new Response(__('You need to set your primary voice before you can participate in a project!'), 400);
        }

        $project->participants()->syncWithoutDetaching([$user->id => ['accepted' => true, 'voice_id' => $user->voice->id]]);

        event(new ProjectAnsweredEvent($project, $user, true));

        return redirect()->back();
    }

    public function decline(Project $project)
    {
        $user = Auth::user();

        $project->participants()->syncWithoutDetaching([$user->id => ['accepted' => false]]);

        event(new ProjectAnsweredEvent($project, $user, false));

        return redirect()->back();
    }

    public function addVoices(Request $request, Project $project)
    {
        $voices = Voice::getListForSelect();

        return view('project.addVoices')->with([
            'project' => $project,
            'voices' => $voices
        ]);
    }

    public function editVoices(Request $request, Project $project)
    {
        $voices = Voice::all();

        return view('project.editVoices')->with([
            'project' => $project,
            'voices' => $voices
        ]);
    }

    public function saveVoices(Request $request, Project $project)
    {
        $voices = $request->get('voices');
        $voiceNumbers = $request->get('voiceNumbers');

        foreach ($voices as $key => $voice) {
            $number = $voiceNumbers[$key];

            if (!$number) {
                $number = 1;
            }

            if ($voice !== null) {
                $project->voices()->syncWithoutDetaching([$voice => ['number' => $number]]);
            }
        }

        $request->session()->flash('alert-success', __('Voices successfully added.'));

        return redirect()->route('project.participants', $project);
    }

    public function removeVoice(Request $request, Project $project, Voice $voice)
    {
        $project->voices()->detach($voice);

        // $promisesWithVoice = $concert->promises()->wherePivot('voice_id', $voice->id)->get();
        // $concert->promises()->detach($promisesWithVoice);

        $request->session()->flash('alert-success', __('Voice successfully removed.'));

        return redirect()->route('project.participants', $project);
    }

    public function showSetUserVoice(Request $request, Project $project, User $user)
    {
        $voices = Voice::getListForSelect();

        $voice_id = $project->participants()->find($user->id)->pivot->voice_id;

        return view('project.setUserVoice')->with([
            'project' => $project,
            'user' => $user,
            'voices' => $voices,
            'voice_id' => $voice_id
        ]);
    }

    public function setUserVoice(Request $request, Project $project, User $user)
    {
        $voice_id = $request->get('voice');

        $project->participants()->syncWithoutDetaching([$user->id => ['voice_id' => $voice_id]]);
        $user->voices()->syncWithoutDetaching([$voice_id]);

        $request->session()->flash('alert-success', __('Voice successfully set.'));

        return redirect()->route('project.participants', $project);
    }

    public function showSetUserVoices(Request $request, Project $project)
    {
        $users = $request->get('users');

        return view('project.setUserVoices')->with([
            'project' => $project,
            'users' => $users,
            'voices' => Voice::getListForSelect()
        ]);
    }

    public function setUserVoices(Request $request, Project $project)
    {
        $users = json_decode($request->get('users'));
        $voice_id = $request->get('voice');

        $users = User::find($users);

        foreach ($users as $user) {
            $project->participants()->syncWithoutDetaching([$user->id => ['voice_id' => $voice_id]]);
            $user->voices()->syncWithoutDetaching([$voice_id]);
        }

        $request->session()->flash('alert-success', __('Voice successfully set.'));

        return redirect()->route('project.participants', $project);
    }

    public function showAddUser(Request $request, Project $project)
    {
        $projectUsers = $project->promises()->pluck('id')->toArray();
        $users = User::whereNotIn('id', $projectUsers)->orderBy('surname')->get();

        $usersForSelect = [];

        foreach ($users as $user) {
            $usersForSelect[$user->id] = "$user->firstname $user->surname";
        }

        return view('project.addUser')->with([
            'project' => $project,
            'users' => $usersForSelect
        ]);
    }

    public function addUser(Request $request, Project $project)
    {
        $usersToAdd = $request->get('users');

        foreach ($usersToAdd as $user_id) {
            $user = User::find($user_id);

            $project->participants()->syncWithoutDetaching([$user_id => ['accepted' => true, 'voice_id' => ($user->voice ? $user->voice->id : null)]]);

            event(new ProjectAnsweredEvent($project, $user, true));
        }

        return redirect()->route('project.participants', $project);
    }

    public function exportParticipants(Request $request, Project $project)
    {
        return (new ProjectUsersExport($project))->download('project_participants.xlsx');

        $filters = $request->all();

        $users = (new GetFilteredUsersService())->projectParticipants($project, $filters, $request->get('search'), $request->get('sort'), $request->get('dir'))->toArray();

        Excel::create('user_export', function ($excel) use ($users) {
            $excel->sheet('users', function($sheet) use ($users) {

                $sheet->fromArray($users);

            });
        })->download('csv');
    }

    public function removeParticipant(Project $project, Request $request)
    {
        $user = User::find($request->get('user_id'));
        
        if ($user !== null) {
            $project->participants()->detach($user);

            $request->session()->flash('alert-success', __('Participant successfully removed from the project.'));
        } else {
            $request->session()->flash('alert-danger', __('Participant could not be found.'));
        }

        return redirect()->back();
    }

    public function removeParticipants(Project $project, Request $request)
    {
        $users = $request->get('users');

        foreach ($users as $userId) {
            $user = User::find($userId);

            $project->participants()->detach($user);
        }

        return response()->json([
            'message' => __('Participants successfully removed from the project.')
        ]);
    }
}
