<?php

namespace App\Services;

use App\Http\Resources\GetEquipmentTypes;
use App\Models\EquipmentType;
use Illuminate\Http\Request;

class EquipmentTypes
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getTypes(Request $request)
    {
        $types = EquipmentType::query();
        foreach ($request->all() as $key=>$query){
            if($key === 'id' || $key === 'name' || $key === 'mask') {
                $query = explode(',', $query);
                $types->orWhereIn($key,  $query);
            }elseif($key === 'q') {
                $types->orWhere('id', 'like', '%'.$query.'%')
                    ->orWhere('name', 'like', '%'.$query.'%')
                    ->orWhere('mask', 'like', '%'.$query.'%');
            }
        }
        return GetEquipmentTypes::collection($types->paginate(5));
    }

}
