<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Concert;
use App\Http\Requests\StoreConcert;

use Auth;

class ConcertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $concerts = Concert::where('deleted_at', '=', null)->with('dates')->get();

        return view('concert.index')->with([
            'concerts' => $concerts
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
            $date = ['date' => $date];
            $concert->dates()->create($date);
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
        //
    }
}
