<?php

namespace App\Http\Controllers;

use App\Http\Resources\DashboardResource;

use App\Semester;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currentSemester = Semester::current();

        if ($currentSemester) {
            return response()->json(new DashboardResource($currentSemester));
        } else {
            return response()->json([]);
        }
    }
}
