<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Concert;
use App\User;
use App\Voice;
use App\Semester;

use App\Http\Requests\StoreConcert;

use Auth;
use DB;

class ConcertController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->breadcrumbs->addCrumb(__('Concerts'), 'concerts');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort = $request->get('sort');

        if (!$sort) $sort = 'nextDate';

        $dir = $request->get('dir');
        $search = $request->get('search');

        $query = "SELECT concerts.*, MIN(concert_dates.date) AS nextDate "
            . "FROM concerts LEFT JOIN concert_dates ON concerts.id = concert_dates.concert_id ";

        $query .= "WHERE concerts.deleted_at IS NULL AND concerts.title LIKE '%$search%' ";

        if ($request->get('show') === 'old') {
            $query .= "AND concert_dates.date < NOW() ";
        } else if ($request->get('show') === 'new' || !$request->get('show')) {
            $query .= "AND concert_dates.date >= NOW() ";
        }

        $query .= "GROUP BY concerts.id ORDER BY $sort $dir";

        $concerts = Concert::fromQuery($query);

        $activeFilters = [];
        foreach ($request->all() as $key => $val) {
            if ($val) $activeFilters[$key] = $val;
        }

        return view('concert.index')->with([
            'concerts' => $concerts,
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
        $this->breadcrumbs->addCrumb(__('New Concert'), 'create');

        $voices = Voice::getListForSelect();
        $semesters = Semester::getListForSelect();

        $nullOption = [null => __('--- Please choose ---')];
        $semesters = $nullOption + $semesters;

        return view('concert.create')->with([
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
    public function store(StoreConcert $request)
    {
        $concert = $request->all();
        $dates = $concert['dates'];
        $voices = $concert['voices'];
        $voiceNumbers = $concert['voiceNumbers'];

        $concert['slug'] = str_slug($concert['title'], '-');

        $concert = Auth::user()->concertsCreated()->create($concert);

        foreach ($dates as $date) {
            if ($date !== null) {
                $date = ['date' => $date];
                $concert->dates()->create($date);
            }
        }

        foreach ($voices as $key => $voice) {
            if ($voice !== null && $voiceNumbers[$key] !== null) {
                $concert->voices()->syncWithoutDetaching([$voice => ['number' => $voiceNumbers[$key]]]);
            }
        }

        $semester = Semester::find($concert->semester_id);

        if ($semester) {
            foreach ($semester->participants as $user) {
                $concert->participants()->syncWithoutDetaching([$user->id => ['voice_id' => $user->voice ? $user->voice->id : null, 'accepted' => $user->accepted]]);
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
        $this->breadcrumbs->addCrumb($concert->title, $concert->slug);

        return view('concert.show')->with([
            'tab' => 'show',
            'concert' => $concert,
            'breadcrumbs' => $this->breadcrumbs
        ]);
    }

    public function participants(Request $request, Concert $concert)
    {
        $this->breadcrumbs->addCrumb($concert->title, $concert->slug);

        $sort = $request->get('sort');

        if (!$sort) $sort = 'id';

        $dir = $request->get('dir');
        $search = $request->get('search');
        $voices = $request->get('voices');

        $query = "SELECT users.*, voices.name as voiceName "
            . "FROM users
            LEFT OUTER JOIN user_concert ON users.id = user_concert.user_id
            LEFT OUTER JOIN voices ON voices.id = user_concert.voice_id ";

        $query .= "WHERE users.deleted_at IS NULL
        AND user_concert.concert_id = $concert->id
        AND user_concert.accepted = 1
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
            $query .= "AND voices.id IN ($voices) ";
        }

        $query .= "GROUP BY users.id, voices.id ORDER BY $sort $dir";

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

        return view('concert.participants')->with([
            'tab' => 'participants',
            'concert' => $concert,
            'participants' => $users,
            'activeFilters' => $activeFilters,
            'voices' => $voices,
            'route' => ['concert.participants', $concert],
            'breadcrumbs' => $this->breadcrumbs
        ]);
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

        $nullOption = [null => __('--- Please choose ---')];
        $semesters = $nullOption + $semesters;

        return view('concert.edit')->with([
            'concert' => $concert,
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
    public function update(StoreConcert $request, $id)
    {
        $concert = Concert::find($id);
        $input = $request->all();
        $dates = $input['dates'];
        $voices = $concert['voices'];
        $voiceNumbers = $concert['voiceNumbers'];

        $input['slug'] = str_slug($input['title'], '-');

        $concert = $concert->update($input);

        foreach ($dates as $date) {
            if ($date !== null) {
                $date = ['date' => $date];
                $concert->dates()->syncWithoutDetaching($date);
            }
        }

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

    public function addDate(Concert $concert)
    {
        return view('concert.addDate')->with(['concert' => $concert]);
    }

    public function saveDate(Request $request, Concert $concert)
    {
        $dates = $request->get('dates');

        foreach ($dates as $date) {
            if ($date !== null) {
                $date = ['date' => $date];
                $concert->dates()->create($date);
            }
        }

        return redirect('concerts');
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
}
