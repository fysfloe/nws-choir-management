<?php

namespace App\Http\Controllers;

use App\Concert;
use App\Http\Requests\StoreComment;
use App\Http\Requests\StoreConcert;
use App\Http\Resources\AuthUserResource;
use App\Http\Resources\ConcertResource;
use App\Http\Resources\UserResource;
use App\Project;
use App\Semester;
use App\Services\GetFilteredUsersService;
use App\User;
use App\Voice;
use Auth;
use DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ConcertController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        //$this->breadcrumbs->addCrumb(__('Concerts'), 'concerts');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort = $request->get('sort');

        if (!$sort) $sort = 'date';

        $dir = $request->get('dir');
        $search = $request->get('search');

        $query = "SELECT concerts.* "
            . "FROM concerts ";

        $query .= "WHERE concerts.deleted_at IS NULL AND concerts.title LIKE '%$search%' ";

        $query .= "GROUP BY concerts.id ORDER BY $sort $dir";

        $concerts = Concert::fromQuery($query);

        $activeFilters = [];
        foreach ($request->all() as $key => $val) {
            if ($val) $activeFilters[$key] = $val;
        }

        return response()->json(ConcertResource::collection($concerts));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->breadcrumbs->addCrumb(__('New Concert'), 'create');

        $voices = Voice::getListForSelect();
        $semesters = Semester::getListForSelect();
        $projects = Project::getListForSelect();

        $nullOption = [null => __('--- Please choose ---')];
        $semesters = $nullOption + $semesters;
        $projects = $nullOption + $projects;

        return view('concert.create')->with([
            'voices' => $voices,
            'semesters' => $semesters,
            'projects' => $projects,
            'breadcrumbs' => $this->breadcrumbs
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreConcert $request)
    {
        $concert = $request->all();
        $voices = $concert['voices'];
        $voiceNumbers = $concert['voiceNumbers'];

        $concert['slug'] = str_slug($concert['title'], '-');

        $concert = Auth::user()->concertsCreated()->create($concert);

        foreach ($voices as $key => $voice) {
            if ($voice !== null && $voiceNumbers[$key] !== null) {
                $concert->voices()->syncWithoutDetaching([$voice => ['number' => $voiceNumbers[$key]]]);
            }
        }

        $semester = Semester::find($concert->semester_id);

        if ($semester) {
            foreach ($semester->participants as $user) {
                $concert->participants()->syncWithoutDetaching([$user->id => ['voice_id' => $user->voice ? $user->voice->id : null, 'accepted' => $user->pivot->accepted]]);
            }
        }

        return redirect('concerts');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Concert $concert)
    {
        if ($concert->project) {
            $this->breadcrumbs->addCrumb($concert->project->title, 'project/' . $concert->project->id);
        }
        
        $this->breadcrumbs->addCrumb($concert->title, $concert->slug);

        return response()->json(new ConcertResource($concert));
    }

    public function participants(Request $request, Concert $concert)
    {
        $this->breadcrumbs->addCrumb($concert->title, $concert->slug);

        $filters = $request->all();

        $users = (new GetFilteredUsersService())->concertParticipants($concert, $filters, $request->get('search'), $request->get('sort'), $request->get('dir'));

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

        return view('concert.participants')->with([
            'tab' => 'participants',
            'concert' => $concert,
            'participants' => UserResource::collection($users),
            'activeFilters' => $activeFilters,
            'voices' => $voices,
            'route' => ['concert.participants', $concert],
            'breadcrumbs' => $this->breadcrumbs
        ]);
    }

    public function loadParticipants(Request $request, Concert $concert)
    {
        $filters = $request->all();

        $users = (new GetFilteredUsersService())->concertParticipants($concert, $filters, $request->get('search'), $request->get('sort'), $request->get('dir'));

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

    public function voices(Request $request, Concert $concert)
    {
        $this->breadcrumbs->addCrumb($concert->title, $concert->slug);

        return view('concert.voices')->with([
            'tab' => 'voices',
            'concert' => $concert,
            'breadcrumbs' => $this->breadcrumbs
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Concert $concert)
    {
        $this->breadcrumbs->addCrumb($concert->title, $concert->slug);

        $semesters = Semester::getListForSelect();
        $voices = Voice::getListForSelect();
        $projects = Project::getListForSelect();

        $nullOption = [null => __('--- Please choose ---')];
        $semesters = $nullOption + $semesters;
        $projects = $nullOption + $projects;

        return view('concert.edit')->with([
            'concert' => $concert,
            'semesters' => $semesters,
            'projects' => $projects,
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
    public function update(StoreConcert $request, $id)
    {
        $concert = Concert::find($id);
        $input = $request->all();
        $voices = $concert['voices'];
        $voiceNumbers = $concert['voiceNumbers'];

        $input['slug'] = str_slug($input['title'], '-');

        $concert = $concert->update($input);

        foreach ($voices as $key => $voice) {
            if ($voice !== null && $voiceNumbers[$key] !== null) {
                $concert->voices()->syncWithoutDetaching([$voice => ['number' => $voiceNumbers[$key]]]);
            }
        }

        return redirect('concerts');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $concert = Concert::find($id);

        $concert->delete();

        return redirect()->back();
    }

    public function accept(Request $request, Concert $concert)
    {
        $user = Auth::user();
        if (!$user->voice) {
            $request->session()->flash('alert-danger', __('You need to set your primary voice before you can participate in a concert!'));
            return redirect()->route('dashboard');
        }

        $concert->participants()->syncWithoutDetaching([$user->id => ['accepted' => true, 'voice_id' => $user->voice->id]]);

        return redirect()->back();
    }

    public function decline(Concert $concert)
    {
        $user = Auth::user();

        $concert->participants()->syncWithoutDetaching([$user->id => ['accepted' => false]]);

        return redirect()->back();
    }

    public function addVoices(Request $request, Concert $concert)
    {
        $voices = Voice::getListForSelect();

        return view('concert.addVoices')->with([
            'concert' => $concert,
            'voices' => $voices
        ]);
    }

    public function editVoices(Request $request, Concert $concert)
    {
        $voices = Voice::all();

        return view('concert.editVoices')->with([
            'concert' => $concert,
            'voices' => $voices
        ]);
    }

    public function saveVoices(Request $request, Concert $concert)
    {
        $voices = $request->get('voices');
        $voiceNumbers = $request->get('voiceNumbers');

        foreach ($voices as $key => $voice) {
            $number = $voiceNumbers[$key];

            if (!$number) {
                $number = 1;
            }

            if ($voice !== null) {
                $concert->voices()->syncWithoutDetaching([$voice => ['number' => $number]]);
            }
        }

        $request->session()->flash('alert-success', __('Voices successfully added.'));

        return redirect()->route('concert.participants', $concert);
    }

    public function removeVoice(Request $request, Concert $concert, Voice $voice)
    {
        $concert->voices()->detach($voice);

        // $promisesWithVoice = $concert->promises()->wherePivot('voice_id', $voice->id)->get();
        // $concert->promises()->detach($promisesWithVoice);

        $request->session()->flash('alert-success', __('Voice successfully removed.'));

        return redirect()->route('concert.participants', $concert);
    }

    public function showSetUserVoice(Request $request, Concert $concert, User $user)
    {
        $voices = Voice::getListForSelect();

        $voice_id = $concert->participants()->find($user->id)->pivot->voice_id;

        return view('concert.setUserVoice')->with([
            'concert' => $concert,
            'user' => $user,
            'voices' => $voices,
            'voice_id' => $voice_id
        ]);
    }

    public function setUserVoice(Request $request, Concert $concert, User $user)
    {
        $voice_id = $request->get('voice');

        $concert->participants()->syncWithoutDetaching([$user->id => ['voice_id' => $voice_id]]);
        $user->voices()->syncWithoutDetaching([$voice_id]);

        $request->session()->flash('alert-success', __('Voice successfully set.'));

        return redirect()->route('concert.participants', $concert);
    }

    public function showSetUserVoices(Request $request, Concert $concert)
    {
        $users = $request->get('users');

        return view('concert.setUserVoices')->with([
            'concert' => $concert,
            'users' => $users,
            'voices' => Voice::getListForSelect()
        ]);
    }

    public function setUserVoices(Request $request, Concert $concert)
    {
        $users = json_decode($request->get('users'));
        $voice_id = $request->get('voice');

        $users = User::find($users);

        foreach ($users as $user) {
            $concert->participants()->syncWithoutDetaching([$user->id => ['voice_id' => $voice_id]]);
            $user->voices()->syncWithoutDetaching([$voice_id]);
        }

        $request->session()->flash('alert-success', __('Voice successfully set.'));

        return redirect()->route('concert.participants', $concert);
    }

    public function showAddUser(Request $request, Concert $concert)
    {
        $concertUsers = $concert->promises()->pluck('id')->toArray();
        $users = User::whereNotIn('id', $concertUsers)->get();

        $usersForSelect = [];

        foreach ($users as $user) {
            $usersForSelect[$user->id] = "$user->firstname $user->surname";
        }

        return view('concert.addUser')->with([
            'concert' => $concert,
            'users' => $usersForSelect
        ]);
    }

    public function addUser(Request $request, Concert $concert)
    {
        $usersToAdd = $request->get('users');

        foreach ($usersToAdd as $user_id) {
            $user = User::find($user_id);

            $concert->participants()->syncWithoutDetaching([$user_id => ['accepted' => true, 'voice_id' => ($user->voice ? $user->voice->id : null)]]);
        }

        return redirect()->route('concert.participants', $concert);
    }

    public function exportParticipants(Request $request, Concert $concert)
    {
        $filters = $request->all();

        $users = (new GetFilteredUsersService())->concertParticipants($concert, $filters, $request->get('search'), $request->get('sort'), $request->get('dir'))->toArray();

        Excel::create('user_export', function ($excel) use ($users) {
            $excel->sheet('users', function($sheet) use ($users) {

                $sheet->fromArray($users);

            });
        })->download('csv');
    }

    public function comments(Concert $concert)
    {
        if ($concert->project) {
            $this->breadcrumbs->addCrumb($concert->project->title, 'project/' . $concert->project->slug);
        }
        
        $this->breadcrumbs->addCrumb($concert->title, $concert->slug);
        
        return view('concert.comments')->with([
            'tab' => 'comments',
            'concert' => $concert,
            'breadcrumbs' => $this->breadcrumbs
        ]);
    }

    public function createComment(StoreComment $request, Concert $concert)
    {
        $input = $request->all();
        $input['user_id'] = Auth::user()->id;
        $comment = $concert->comments()->create($input);

        return redirect()->back();
    }

    public function removeParticipant(Concert $concert, Request $request)
    {
        $user = User::find($request->get('user_id'));
        
        if ($user !== null) {
            $concert->participants()->detach($user);

            $request->session()->flash('alert-success', __('Participant successfully removed from the concert.'));
        } else {
            $request->session()->flash('alert-danger', __('Participant could not be found.'));
        }

        return redirect()->back();
    }

    public function removeParticipants(Concert $concert, Request $request)
    {
        $users = $request->get('users');

        foreach ($users as $userId) {
            $user = User::find($userId);

            $concert->participants()->detach($user);
        }

        return response()->json([
            'message' => __('Participants successfully removed from the concert.')
        ]);
    }

    public function confirm(Request $request, Concert $concert, User $user)
    {
        $concert->promises()->syncWithoutDetaching([$user->id => ['confirmed' => true, 'excused' => false]]);

        return response('', 200);
    }

    public function excuse(Request $request, Concert $concert, User $user)
    {
        $concert->promises()->syncWithoutDetaching([$user->id => ['confirmed' => false, 'excused' => true]]);

        return response('', 200);
    }

    public function setUnexcused(Request $request, Concert $concert, User $user)
    {
        $concert->promises()->syncWithoutDetaching([$user->id => ['confirmed' => false, 'excused' => false]]);

        return redirect()->back();
    }
}
