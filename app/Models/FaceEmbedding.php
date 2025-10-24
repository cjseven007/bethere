<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaceEmbedding extends Model
{
    protected $fillable = ['employee_id','model','vector'];
    protected $casts = ['vector' => 'array'];

    public function employee() { return $this->belongsTo(Employee::class); }
}