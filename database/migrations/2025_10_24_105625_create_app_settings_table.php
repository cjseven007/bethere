<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('app_settings', function (Blueprint $t) {
            $t->id();
            $t->float('threshold')->default(0.40);
            $t->unsignedInteger('interval')->default(2); // seconds between snapshots
            $t->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('app_settings');
    }
};
