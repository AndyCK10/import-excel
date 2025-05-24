<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('member_point', function (Blueprint $table) {
            $table->id();
            $table->string('member_code');
            $table->string('bar_code');
            $table->string('name');
            $table->integer('status')->comment('0:ยังไม่ได้ยืนยันสมาชิก, 1: ยืนยันสมาชิกแล้ว');
            $table->string('province');
            $table->dateTime('date_sync');
            $table->decimal('value', 14, 2)->nullable();
            $table->decimal('point_use', 14, 2)->nullable();
            $table->decimal('point_remain', 14, 2)->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_point');
    }
};
