<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('face_embeddings', function (Blueprint $t) {
      $t->id();
      $t->foreignId('employee_id')->constrained()->cascadeOnDelete();
      $t->string('model')->default('buffalo_l');              // fixed model
      $t->json('vector');                                     // embedding vector as JSON array
      $t->timestamps();
      $t->unique(['employee_id','model']); // one vector per model per employee
    });
  }
  public function down(): void {
    Schema::dropIfExists('face_embeddings');
  }
};
