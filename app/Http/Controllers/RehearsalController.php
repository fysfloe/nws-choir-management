<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRehearsal;
use App\Http\Resources\RehearsalListResource;
use App\Http\Resources\RehearsalResource;
use App\Http\Resources\UserResource;
use App\Rehearsal;
use App\Services\GetFilteredUsersService;
use App\User;
use App\Voice;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Exports\RehearsalUsersExport;

class RehearsalController extends Controller
{
    /**
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
            ->orderBy($request->get('sort', 'id'), $request->get('dir', 'ASC'))
            ->get();

        return response()->json(RehearsalListResource::collection($rehearsals));
    }

    /**
     * @param StoreRehearsal $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(StoreRehearsal $request)
    {
        $rehearsal = $request->all();

        $rehearsal['start_time'] = (new \DateTime($rehearsal['start_time']))->format('H:i');
        $rehearsal['end_time'] = (new \DateTime($rehearsal['end_time']))->format('H:i');

        $rehearsal = Auth::user()->rehearsalsCreated()->create($rehearsal);

        return response()->json(new RehearsalResource($rehearsal));
    }

    /**
     * @param  Rehearsal  $rehearsal
     * @return \Illuminate\Http\Response
     */
    public function show(Rehearsal $rehearsal)
    {
        return response()->json(new RehearsalResource($rehearsal));
    }

    /**
     * @param Request $request
     * @param Rehearsal $rehearsal
     * @return JsonResponse
     */
    public function participants(Request $request, Rehearsal $rehearsal)
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

        return response()->json(UserResource::collection($users));
    }

    /**
     * @param Rehearsal $rehearsal
     * @return \Illuminate\Http\JsonResponse
     */
    public function otherUsers(Rehearsal $rehearsal)
    {
        $rehearsalUsers = $rehearsal->promises()->pluck('id')->toArray();
        $users = $rehearsal->project->promises()->whereNotIn('id', $rehearsalUsers)->orderBy('surname')->get();

        return response()->json(UserResource::collection($users));
    }

    /**
     * @param  \Illuminate\Http\Request $request
     * @param Rehearsal $rehearsal
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function update(Request $request, Rehearsal $rehearsal)
    {
        $rehearsalInput = $request->all();

        $rehearsalInput['start_time'] = (new \DateTime($rehearsalInput['start_time']))->format('H:i');
        $rehearsalInput['end_time'] = (new \DateTime($rehearsalInput['end_time']))->format('H:i');

        $rehearsal->update($rehearsalInput);

        return response()->json(new RehearsalResource($rehearsal));
    }

    /**
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rehearsal = Rehearsal::find($id);
        $rehearsal->delete();

        return response()->json();
    }

    /**
     * @param Rehearsal $rehearsal
     * @param null $user_id
     * @return JsonResponse
     */
    public function accept(Rehearsal $rehearsal, $user_id = null)
    {
        if ($user_id) {
            $user = User::find($user_id);
        } else {
            $user = Auth::user();
        }

        $rehearsal->participants()->syncWithoutDetaching([$user->id => ['accepted' => true]]);

        return response()->json(new RehearsalResource($rehearsal));
    }

    /**
     * @param Rehearsal $rehearsal
     * @param null $user_id
     * @return JsonResponse
     */
    public function decline(Rehearsal $rehearsal, $user_id = null)
    {
        if ($user_id) {
            $user = User::find($user_id);
        } else {
            $user = Auth::user();
        }

        $rehearsal->participants()->syncWithoutDetaching([$user->id => ['accepted' => false]]);

        return response()->json(new RehearsalResource($rehearsal));
    }

    /**
     * @param Rehearsal $rehearsal
     * @param User $user
     * @return JsonResponse
     */
    public function confirm(Rehearsal $rehearsal, User $user)
    {
        $participant = $rehearsal->participants->find($user);
        if (!$participant || !$participant->pivot->confirmed) {
            $rehearsal->promises()->syncWithoutDetaching([$user->id => ['accepted' => true, 'confirmed' => true, 'excused' => false]]);
        } else {
            $rehearsal->promises()->syncWithoutDetaching([$user->id => ['confirmed' => null, 'excused' => null]]);
        }

        return response()->json(new UserResource($user));
    }

    /**
     * @param Rehearsal $rehearsal
     * @param User $user
     * @return JsonResponse
     */
    public function excuse(Rehearsal $rehearsal, User $user)
    {
        if (!$rehearsal->participants->find($user)->pivot->excused) {
            $rehearsal->promises()->syncWithoutDetaching([$user->id => ['confirmed' => false, 'excused' => true]]);
        } else {
            $rehearsal->promises()->syncWithoutDetaching([$user->id => ['confirmed' => null, 'excused' => null]]);
        }

        return response()->json(new UserResource($user));
    }

    /**
     * @param Rehearsal $rehearsal
     * @param User $user
     * @return JsonResponse
     */
    public function setUnexcused(Rehearsal $rehearsal, User $user)
    {
        $confirmed = $rehearsal->participants->find($user)->pivot->confirmed;

        if (null === $confirmed || true === (bool)$confirmed) {
            $rehearsal->promises()->syncWithoutDetaching([$user->id => ['confirmed' => false, 'excused' => false]]);
        } else {
            $rehearsal->promises()->syncWithoutDetaching([$user->id => ['confirmed' => null, 'excused' => null]]);
        }

        return response()->json(new UserResource($user));
    }

    /**
     * @param Request $request
     * @param Rehearsal $rehearsal
     * @return \Illuminate\Http\JsonResponse
     */
    public function addParticipants(Request $request, Rehearsal $rehearsal)
    {
        $usersToAdd = $request->get('users');

        foreach ($usersToAdd as $user_id) {
            $rehearsal->participants()->syncWithoutDetaching([$user_id => ['accepted' => true]]);
        }

        return response()->json();
    }

    /**
     * @param Request $request
     * @param Rehearsal $rehearsal
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeParticipants(Request $request, Rehearsal $rehearsal)
    {
        $users = $request->get('users');

        foreach ($users as $userId) {
            $user = User::find($userId);

            $rehearsal->participants()->detach($user);
        }

        return response()->json();
    }

    /**
     * @param Rehearsal $rehearsal
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addProjectParticipants(Rehearsal $rehearsal)
    {
        $project = $rehearsal->project;

        foreach ($project->participants as $participant) {
            $rehearsal->participants()->syncWithoutDetaching([$participant->id => ['accepted' => true]]);
        }

        return redirect()->back();
    }

    /**
     * @param Rehearsal $rehearsal
     * @param Request $request
     * @return Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportParticipants(Rehearsal $rehearsal, Request $request)
    {
        $filters = $request->all();

        $users = (new GetFilteredUsersService())->rehearsalParticipants($rehearsal, $filters, $request->get('search'), $request->get('sort'), $request->get('dir'));

        return (new RehearsalUsersExport($rehearsal, $users))->download(__('Rehearsal') . ': ' . $rehearsal->date->format('d.m.Y') . '_participants.xlsx');
    }
}
