<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'equipments';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function equipment_types()
    {
        return $this->belongsTo(EquipmentType::class, 'equipment_type_id');
    }
}
