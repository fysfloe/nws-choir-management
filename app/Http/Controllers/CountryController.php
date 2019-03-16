<?php

namespace App\Http\Controllers;

use Webpatser\Countries\Countries;

class CountryController extends Controller
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function options()
    {
        $nullOption = [null => __('--- Please choose ---')];
        $countries = (new Countries())->getListForSelect();
        $countries = $nullOption + $countries;

        return response()->json($countries);
    }
}
