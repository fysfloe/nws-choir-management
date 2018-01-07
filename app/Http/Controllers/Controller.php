<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Creitive\Breadcrumbs\Breadcrumbs;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $breadcrumbs;

    public function __construct()
    {
        // parent::__construct();

        $this->breadcrumbs = new Breadcrumbs;
        $this->breadcrumbs->addCrumb('<span class="oi oi-home"></span>', '/');
    }
}
