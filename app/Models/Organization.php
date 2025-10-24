<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = ['name','slug'];

    public function users()  { return $this->belongsToMany(User::class)->withPivot('role')->withTimestamps(); }
    public function employees() { return $this->hasMany(Employee::class); }
}