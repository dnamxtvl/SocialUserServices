<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Job extends Model
{
    use HasFactory;

    protected $connection = 'mysql_user';
    protected $table = 'jobs';
    protected $primaryKey = 'id';
}
