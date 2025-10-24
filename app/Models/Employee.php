<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['organization_id','name','email'];

    public function organization() { return $this->belongsTo(Organization::class); }
    public function embedding()    { return $this->hasOne(FaceEmbedding::class)->where('model','buffalo_l'); }
}
