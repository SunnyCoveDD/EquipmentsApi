<?php

use App\Models\EquipmentType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('equipments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(EquipmentType::class)->constrained('equipment_types');
            $table->string('serial_number')->unique();
            $table->text('description');
            $table->unique(['equipment_type_id', 'serial_number'], 'equipment_type_id_serial_number_unique');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('equipments');
    }
};
