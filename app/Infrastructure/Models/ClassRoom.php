<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ClassRoom extends Model
{
    use HasFactory;

    protected $connection = 'mysql_user';
    protected $table = 'class_rooms';
    protected $primaryKey = 'id';
}
