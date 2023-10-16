<?php

namespace App\Http\Controllers;

use App\Http\Requests\EquipmentTypeRequest;
use App\Http\Resources\GetEquipment;
use App\Models\EquipmentType;
use App\Services\Equipments;
use App\Services\EquipmentTypes;
use Illuminate\Http\Request;

class EquipmentTypeController extends Controller
{
    /**
     * @param Request $request
     * @param EquipmentTypes $service
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     */
    public function index(Request $request, EquipmentTypes $service)
    {
        return response($service->getTypes($request));
    }
}
