<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('employees', function (Blueprint $t) {
      $t->id();
      $t->foreignId('organization_id')->constrained()->cascadeOnDelete();
      $t->string('name');
      $t->string('email')->unique();
      $t->timestamps();
    });
  }
  public function down(): void {
    Schema::dropIfExists('employees');
  }
};
