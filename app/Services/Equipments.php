<?php

namespace App\Services;

use App\Http\Requests\WithoutRedirectEquipmentRequest;
use App\Http\Resources\GetEquipment;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Equipments
{
    /**
     * @param Request $request
     * @return array
     */
    public function getEquipments(Request $request)
    {
        $equipment = Equipment::with('equipment_types');
        foreach ($request->all() as $key=>$query){
            if($key === 'id' || $key === 'equipment_type_id' || $key === 'serial_number' || $key === 'description') {
                $query = explode(',', $query);
                $equipment->orWhereIn($key, $query);
            }elseif($key === 'q') {
                $equipment->orWhere('id', 'like', '%'.$query.'%')
                    ->orWhere('equipment_type_id', 'like', '%'.$query.'%')
                    ->orWhere('serial_number', 'like', '%'.$query.'%')
                    ->orWhere('description', 'like', '%'.$query.'%');
            }
        }
        return [GetEquipment::collection($equipment->paginate(5)), $equipment->paginate(5)->lastPage()];
    }

    /**
     * @param WithoutRedirectEquipmentRequest $request
     * @return array|array[]
     */
    public function createEquipments(WithoutRedirectEquipmentRequest $request)
    {
        $equipments = preg_split('/[\s,]+/m', $request->serial_number);
        $response = ['errors' => [], 'success' => []];

        foreach ($equipments as $key => $equipment) {
            $equipmentItem = [
                'serial_number' => $equipment,
                'equipment_type_id' => $request->equipment_type_id,
                'description' => $request->description,
            ];
            $validator = Validator::make($equipmentItem, $request->rules());
            if ($validator->fails()) {
                $response['errors'][$key] = $validator->errors()->all();
            } else {
                $createdEquipment = Equipment::create($equipmentItem);
                $response['success'][$key] = new GetEquipment($createdEquipment);
            }
        }

        return $response;
    }

    /**
     * @param Equipment $equipment
     * @return GetEquipment
     */
    public function showEquipment(Equipment $equipment)
    {
        return new GetEquipment($equipment);
    }

    /**
     * @param Equipment $equipment
     * @return void
     */
    public function deleteEquipment(Equipment $equipment)
    {
        $equipment->delete();
    }

    /**
     * @param Request $request
     * @param Equipment $equipment
     * @return void
     */
    public function updateEquipment(Request $request, Equipment $equipment)
    {
        $equipment->update($request->all());
    }
}
