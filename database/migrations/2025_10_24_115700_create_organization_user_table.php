<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('organization_user', function (Blueprint $t) {
      $t->id();
      $t->foreignId('organization_id')->constrained()->cascadeOnDelete();
      $t->foreignId('user_id')->constrained()->cascadeOnDelete();
      $t->enum('role', ['admin','member'])->default('member'); // admins can manage org
      $t->timestamps();
      $t->unique(['organization_id','user_id']);
    });
  }
  public function down(): void {
    Schema::dropIfExists('organization_user');
  }
};
