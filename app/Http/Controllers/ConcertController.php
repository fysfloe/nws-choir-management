<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Concert;
use App\Http\Requests\StoreConcert;

use Auth;
use DB;

class ConcertController extends Controller
{
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
            . "FROM concerts JOIN concert_dates ON concerts.id = concert_dates.concert_id ";

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
            'activeFilters' => $activeFilters
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('concert.create');
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

        $concert['slug'] = str_slug($concert['title'], '-');

        $concert = Auth::user()->concertsCreated()->create($concert);

        foreach ($dates as $date) {
            if ($date !== null) {
                $date = ['date' => $date];
                $concert->dates()->create($date);
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
        return view('concert.show')->with([
            'concert' => $concert
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
        return view('concert.edit')->with([
            'concert' => $concert
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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

    public function addDate($id)
    {
        return view('concert.addDate')->with(['concertId' => $id]);
    }

    public function saveDate(Request $request)
    {
        $concert = Concert::find($request->get('concertId'));
        $dates = $request->get('dates');

        foreach ($dates as $date) {
            if ($date !== null) {
                $date = ['date' => $date];
                $concert->dates()->create($date);
            }
        }

        return redirect('concerts');
    }
}
