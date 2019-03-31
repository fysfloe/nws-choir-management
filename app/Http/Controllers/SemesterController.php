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

class SemesterController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $semesters = Semester::where('deleted_at', '=', null)
            ->orderBy($request->get('sort', 'start_date'), $request->get('dir', 'ASC'))
            ->get();

        return response()->json(SemesterResource::collection($semesters));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
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
     * @param StoreSemester $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSemester $request)
    {
        $semester = $request->all();

        if ($semester['start_date'] >= $semester['end_date']) {
            return response()->json(['end_date' => __('The end date must be greater than the start date.')], 400);
        }

        $semester = Auth::user()->semestersCreated()->create($semester);

        return response()->json(new SemesterResource($semester));
    }

    /**
     * @param Semester $semester
     * @return \Illuminate\Http\Response
     */
    public function show(Semester $semester)
    {
        return response()->json(new SemesterResource($semester));
    }

    /**
     * @param Request $request
     * @param Semester $semester
     * @return false|string
     */
    public function participants(Request $request, Semester $semester)
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

        return response()->json(UserResource::collection($users));
    }

    /**
     * @param Semester $semester
     * @return \Illuminate\Http\JsonResponse
     */
    public function otherUsers(Semester $semester)
    {
        $semesterUsers = $semester->promises()->pluck('id')->toArray();
        $users = User::whereNotIn('id', $semesterUsers)->orderBy('surname')->get();

        return response()->json(UserResource::collection($users));
    }

    /**
     * @param StoreSemester $request
     * @param Semester $semester
     * @return \Illuminate\Http\Response
     */
    public function update(StoreSemester $request, Semester $semester)
    {
        $semesterInput = $request->all();

        if ($semesterInput['start_date'] >= $semesterInput['end_date']) {
            return response()->json(['end_date' => __('The end date must be greater than the start date.')], 400);
        }

        $semester->update($semesterInput);

        return response()->json(new SemesterResource($semester));
    }

    /**
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

        return response()->json();
    }

    /**
     * @param Semester $semester
     * @return \Illuminate\Http\JsonResponse
     */
    public function accept(Semester $semester)
    {
        $user = Auth::user();

        $semester->participants()->syncWithoutDetaching([$user->id => ['accepted' => true]]);

        event(new SemesterAnsweredEvent($semester, $user, true));
        
        return response()->json(new SemesterResource($semester));
    }

    /**
     * @param Semester $semester
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * @param Request $request
     * @param Semester $semester
     * @return \Illuminate\Http\JsonResponse
     */
    public function addParticipants(Request $request, Semester $semester)
    {
        $usersToAdd = $request->get('users');

        foreach ($usersToAdd as $user_id) {
            $semester->participants()->syncWithoutDetaching([$user_id => ['accepted' => true]]);
        }

        return response()->json();
    }

    /**
     * @param Semester $semester
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeParticipants(Semester $semester, Request $request)
    {
        $users = $request->get('users');

        foreach ($users as $userId) {
            $user = User::find($userId);

            $semester->participants()->detach($user);
        }

        return response()->json();
    }
}
