<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Position extends Model
{
    use HasFactory;

    protected $connection = 'mysql_user';
    protected $table = 'positions';
    protected $primaryKey = 'id';
}
