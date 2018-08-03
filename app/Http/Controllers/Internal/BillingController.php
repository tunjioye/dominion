<?php

namespace App\Http\Controllers\Internal;

use App\Billing;
use App\Patient;
use App\Visit;
use App\Surgery;
// use App\Http\Requests\CreateBillings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillingController extends InternalControl
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->page = collect();
        $this->page->title = 'Billings';
        $this->page->view = 'm.billings';
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->page->view)
            ->with('billings', Billing::latest()->paginate(isset($_GET['entries']) ? $_GET['entries'] : 10))
            ->with('visits', Visit::all())
            ->with('page', $this->page);
    }

    public function filter($filter, $searchterm = "")
    {
        if ($filter == 'patient_file_number') {
            $objects = Patient::where("file_number", 'LIKE', "%$searchterm%")->get();
            $objects = Visit::whereIn('patient_id', $objects->pluck('id'))->get();
            $this->billings = Billing::whereIn('visit_id', $objects->pluck('id'))->latest()->paginate(isset($_GET['entries']) ? $_GET['entries'] : 10);
        } elseif ($filter == 'patient_id') {
            $searchterms = array();
            $searchterms = explode(' ', $searchterm);

            if (count($searchterms) == 2) {
                $object = Patient::where([["first_name", 'LIKE', "%$searchterms[0]%"], ["last_name", 'LIKE', "%$searchterms[1]%"]])->get();
            } else {
                $object = Patient::where("first_name", 'LIKE', "%$searchterms[0]%")->get();
                $object2 = Patient::where("last_name", 'LIKE', "%$searchterms[0]%")->get();
                $object->push($object2);
                $object = $object->flatten();
            }
            $objects = $object;
            $objects = Visit::whereIn('patient_id', $objects->pluck('id'))->get();
            $this->billings = Billing::whereIn('visit_id', $objects->pluck('id'))->latest()->paginate(isset($_GET['entries']) ? $_GET['entries'] : 10);
        } elseif ($filter == "visit_id") {
            $objects = Visit::where('title', 'LIKE', "%$searchterm%")->get();
            $this->billings = Billing::whereIn($filter, $objects->pluck('id'))->latest()->paginate(isset($_GET['entries']) ? $_GET['entries'] : 10);
        } elseif ($filter == "status") {
            // Interpret
            if (isset($searchterm[0]) && (strtolower($searchterm[0]) == 1 || strtolower($searchterm[0]) == 'y' || strtolower($searchterm[0]) == 'p')) {
                $searchterm = 1;
            } elseif (isset($searchterm[0]) && (strtolower($searchterm[0]) == 0 || strtolower($searchterm[0]) == 'n' || strtolower($searchterm[0]) == 'u')) {
                $searchterm = 0;
            } else {
                $searchterm = "";
            }
            $this->billings = Billing::where('is_paid', 'LIKE', "%$searchterm%")->latest()->paginate(isset($_GET['entries']) ? $_GET['entries'] : 10);
        } else {
            $this->billings = Billing::where($filter, 'LIKE', "%$searchterm%")->latest()->paginate(isset($_GET['entries']) ? $_GET['entries'] : 10);
        }

        if (isset($this->billings)) {
            $this->billings->filter = $filter;
            $this->billings->searchterm = $searchterm = urldecode($searchterm);
        }

        return view($this->page->view)
            ->with('billings', $this->billings)
            ->with('visits', Visit::all())
            ->with('page', $this->page);
    }
}
