<?php

namespace Domain\Abstract\Models;

use Domain\Shared\Traits\GetTableName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

abstract class BaseModel extends Model
{
    use GetTableName;
    use HasFactory;
}
