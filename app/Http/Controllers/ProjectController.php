<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\StoreProject;

use App\Project;
use App\Voice;
use App\Semester;
use App\User;

use App\Services\GetFilteredUsersService;
use App\Http\Resources\UserResource;

use Auth;

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
            }
        }

        return redirect('projects');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        $this->breadcrumbs->addCrumb($project->title, $project->slug);

        return view('project.show')->with([
            'tab' => 'show',
            'project' => $project,
            'breadcrumbs' => $this->breadcrumbs
        ]);
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

        return redirect()->back();
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

    public function loadParticipants(Request $request, Project $project)
    {
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
            return redirect()->route('dashboard');
        }

        $project->participants()->syncWithoutDetaching([$user->id => ['accepted' => true, 'voice_id' => $user->voice->id]]);

        if (count($project->concerts) > 0) {
            foreach ($project->concerts as $concert) {
                $concert->participants()->syncWithoutDetaching([$user->id => ['accepted' => true, 'voice_id' => $user->voice ? $user->voice->id : null]]);
            }
        }

        return redirect()->back();
    }

    public function decline(Project $project)
    {
        $user = Auth::user();

        $project->participants()->syncWithoutDetaching([$user->id => ['accepted' => false]]);

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
        }

        return redirect()->route('project.participants', $project);
    }

    public function exportParticipants(Request $request, Project $project)
    {
        $filters = $request->all();

        $users = (new GetFilteredUsersService())->projectParticipants($project, $filters, $request->get('search'), $request->get('sort'), $request->get('dir'))->toArray();

        Excel::create('user_export', function ($excel) use ($users) {
            $excel->sheet('users', function($sheet) use ($users) {

                $sheet->fromArray($users);

            });
        })->download('csv');
    }
}
