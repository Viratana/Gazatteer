<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locations = Location::all();
        if(!empty($locations)){
            return Response::json(['data' => $locations], 200);
        }
        else{
            return Response::json(['message' => 'No Record Found'], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Display the specified resource.
     */
    public function show(Location $location)
    {
        $locationss = Location::find($location->id);
        if(!empty($locationss)){
            return Response::json(['data' => $locationss], 200);
        }
        else{
            return Response::json(['message' => 'No Record Found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function search($term){
        // $locations = Location::where('name_en',​ $term)->get();       //search by full name
        $locations = Location::query()
                ->where('name_en', 'like', "%{$term}%")
                ->orWhere('name_kh', 'like', "%{$term}%")
                ->orWhere('code', 'like', "%{$term}%")
                ->get();                                    //search by ចាប់តួអក្សរ       
        if(!empty($locations)){
            return Response::json(['data' => $locations], 200);
        } else {
            return Response::json(['message' => 'No Record Found'], 404);
        }
    }
}
