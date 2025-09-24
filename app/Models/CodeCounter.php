<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CodeCounter extends Model
{
    protected $table = 'code_counters';
    protected $fillable = ['key', 'counter'];
    public $timestamps = true;
}
