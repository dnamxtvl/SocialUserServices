<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ward extends Model
{
    use HasFactory;

    protected $connection = 'mysql_user';

    protected $table = 'wards';

    protected $primaryKey = 'id';

    public function district(): belongsTo
    {
        return $this->belongsTo(District::class);
    }
}
