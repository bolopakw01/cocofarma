<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('code_counters', function (Blueprint $table) {
            $table->id();
            $table->string('key')->index(); // e.g., P-20250924ABC or B-20250924ABC
            $table->unsignedInteger('counter')->default(0);
            $table->timestamps();
            $table->unique(['key']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('code_counters');
    }
};
