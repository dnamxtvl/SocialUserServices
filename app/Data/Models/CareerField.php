<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class CareerField extends Model
{
    use HasFactory;

    protected $connection = 'mysql_user';
    protected $table = 'career_fields';
    protected $primaryKey = 'id';
}
