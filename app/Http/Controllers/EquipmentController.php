<?php

namespace App\Http\Controllers;

use App\Http\Requests\EquipmentRequest;
use App\Http\Requests\WithoutRedirectEquipmentRequest;
use App\Models\Equipment;
use App\Services\Equipments;
use Illuminate\Http\Request;


class EquipmentController extends Controller
{
    /**
     * @param Request $request
     * @param Equipments $service
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     */
    public function index(Request $request, Equipments $service)
    {
        return response($service->getEquipments($request));
    }

    /**
     * @param WithoutRedirectEquipmentRequest $request
     * @param Equipments $service
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     */
    public function store(WithoutRedirectEquipmentRequest $request, Equipments $service)
    {
        return response($service->createEquipments($request), 201);
    }

    /**
     * @param Equipment $id
     * @param Equipments $service
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     */
    public function show(Equipment $id, Equipments $service)
    {
        return response($service->showEquipment($id));
    }

    /**
     * @param EquipmentRequest $request
     * @param Equipment $id
     * @param Equipments $service
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     */
    public function update(EquipmentRequest $request, Equipment $id, Equipments $service)
    {
        $service->updateEquipment($request, $id);

        return response([], 204);
    }

    /**
     * @param Equipment $id
     * @param Equipments $service
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     */
    public function destroy(Equipment $id, Equipments $service)
    {
        $service->deleteEquipment($id);

        return response([], 202);
    }
}
