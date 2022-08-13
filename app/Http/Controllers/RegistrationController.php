<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use App\Http\Requests\RegistrationRequest;
use App\Http\Resources\RegistrationResource;

class RegistrationController extends Controller
{
    public function __construct()
    {
        //$this->middleware('rate.limit');
    }

    public function index()
    {
        return RegistrationResource::collection(Registration::get());
    }

    public function store(RegistrationRequest $request)
    {
        $model = new Registration;

        $model->fill($request->all())->save();

        return new RegistrationResource($model);
    }
}
