<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyEmployee extends Model
{
    use HasFactory;
    protected $table = 'm_family_employee';
    protected $guarded = ['id'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
