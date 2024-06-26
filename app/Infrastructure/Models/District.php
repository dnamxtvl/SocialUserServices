<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class District extends Model
{
    use HasFactory;

    protected $connection = 'mysql_user';

    protected $table = 'districts';

    protected $primaryKey = 'id';

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
