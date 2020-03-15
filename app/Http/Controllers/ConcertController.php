<?php

namespace App\Http\Controllers;

use App\Concert;
use App\Http\Requests\StoreConcert;
use App\Http\Resources\ConcertListResource;
use App\Http\Resources\ConcertResource;
use App\Http\Resources\UserResource;
use App\Semester;
use App\Services\GetFilteredUsersService;
use App\User;
use App\Voice;
use Auth;
use DB;
use App\Exports\ConcertUsersExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ConcertController extends Controller
{
    /**
     * @param Request $request
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

        return response()->json(ConcertListResource::collection($concerts));
    }

    /**
     * @param StoreConcert $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(StoreConcert $request)
    {
        $concert = $request->all();
        $concert['slug'] = str_slug($concert['title'], '-');
        $concert['start_time'] = (new \DateTime($concert['start_time']))->format('H:i');
        $concert['end_time'] = (new \DateTime($concert['end_time']))->format('H:i');

        $concert = Auth::user()->concertsCreated()->create($concert);
        $semester = Semester::find($concert->semester_id);

        if ($semester) {
            foreach ($semester->participants as $user) {
                $concert->participants()->syncWithoutDetaching([$user->id => ['voice_id' => $user->voice ? $user->voice->id : null, 'accepted' => $user->pivot->accepted]]);
            }
        }

        return response()->json(new ConcertResource($concert));
    }

    /**
     * @param Concert $concert
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

    /**
     * @param Request $request
     * @param Concert $concert
     * @return \Illuminate\Http\JsonResponse
     */
    public function participants(Request $request, Concert $concert)
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

        return response()->json(UserResource::collection($users));
    }

    /**
     * @param Concert $concert
     * @return \Illuminate\Http\JsonResponse
     */
    public function otherUsers(Concert $concert)
    {
        $concertUsers = $concert->promises()->pluck('id')->toArray();
        $users = $concert->project->promises()->whereNotIn('id', $concertUsers)->orderBy('surname')->get();

        return response()->json(UserResource::collection($users));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function options()
    {
        $concerts = Concert::where('deleted_at', '=', null)
            ->orderBy('title')
            ->get();

        $concertOptions = [];

        foreach ($concerts as $concert) {
            $concertOptions[$concert->id] = $concert->title;
        }

        return response()->json($concertOptions);
    }

    /**
     * @param StoreConcert $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function update(StoreConcert $request, $id)
    {
        $concert = Concert::find($id);
        $input = $request->except(['semester_id']);

        $input['start_time'] = (new \DateTime($input['start_time']))->format('H:i');
        $input['end_time'] = (new \DateTime($input['end_time']))->format('H:i');

        $concert->update($input);

        return response()->json(new ConcertResource($concert));
    }

    /**
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $concert = Concert::find($id);

        $concert->delete();

        return response()->json();
    }

    /**
     * @param Concert $concert
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function accept(Concert $concert)
    {
        $user = Auth::user();
        if (!$user->voice) {
            return redirect()->route('dashboard');
        }

        $concert->participants()->syncWithoutDetaching([$user->id => ['accepted' => true, 'voice_id' => $user->voice->id]]);

        return response()->json(new ConcertResource($concert));
    }

    /**
     * @param Concert $concert
     * @return \Illuminate\Http\JsonResponse
     */
    public function decline(Concert $concert)
    {
        $user = Auth::user();

        $concert->participants()->syncWithoutDetaching([$user->id => ['accepted' => false]]);

        return response()->json(new ConcertResource($concert));
    }

    /**
     * @param  Request $request
     * @param  Concert $concert
     * @return [type]           [description]
     */
    public function exportParticipants(Request $request, Concert $concert)
    {
        $filters = $request->all();

        $users = (new GetFilteredUsersService())->concertParticipants($concert, $filters, $request->get('search'), $request->get('sort'), $request->get('dir'));

        return (new ConcertUsersExport($concert, $users))->download(__('Concert') . ': ' . $concert->title . '_participants.xlsx');
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

    /**
     * @param Request $request
     * @param Concert $concert
     * @return \Illuminate\Http\JsonResponse
     */
    public function addParticipants(Request $request, Concert $concert)
    {
        $usersToAdd = $request->get('users');

        foreach ($usersToAdd as $user_id) {
            $concert->participants()->syncWithoutDetaching([$user_id => ['accepted' => true]]);
        }

        return response()->json();
    }

    /**
     * @param Concert $concert
     * @param User $user
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function confirm(Concert $concert, User $user)
    {
        if (!$concert->participants->find($user)->pivot->confirmed) {
            $concert->promises()->syncWithoutDetaching([$user->id => ['confirmed' => true, 'excused' => false]]);
        } else {
            $concert->promises()->syncWithoutDetaching([$user->id => ['confirmed' => null, 'excused' => null]]);
        }

        return response()->json(new UserResource($user));
    }

    /**
     * @param Concert $concert
     * @param User $user
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function excuse(Concert $concert, User $user)
    {
        if (!$concert->participants->find($user)->pivot->excused) {
            $concert->promises()->syncWithoutDetaching([$user->id => ['confirmed' => false, 'excused' => true]]);
        } else {
            $concert->promises()->syncWithoutDetaching([$user->id => ['confirmed' => null, 'excused' => null]]);
        }

        return response()->json(new UserResource($user));
    }

    /**
     * @param Concert $concert
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setUnexcused(Concert $concert, User $user)
    {
        $confirmed = $concert->participants->find($user)->pivot->confirmed;

        if (null === $confirmed || true === (bool)$confirmed) {
            $concert->promises()->syncWithoutDetaching([$user->id => ['confirmed' => false, 'excused' => false]]);
        } else {
            $concert->promises()->syncWithoutDetaching([$user->id => ['confirmed' => null, 'excused' => null]]);
        }

        return response()->json(new UserResource($user));
    }
}
