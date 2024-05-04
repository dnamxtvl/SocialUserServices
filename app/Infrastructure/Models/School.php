<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class School extends Model
{
    use HasFactory;

    protected $connection = 'mysql_user';
    protected $table = 'schools';
    protected $primaryKey = 'id';
}
