<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

/**
 * @property mixed|string[] $data
 * @property Application|Request|mixed|array|object $status
 * @method create()
 * @method static truncate()
 * @method static factory()
 * @method static first()
 * @method static firstOrFail()
 * @method static updateOrCreate($flags, $attributes)
 */
class SheetRow extends Model
{

    use HasFactory;

    protected $fillable = ['status'];

    protected $with = ['cells'];

    const STATUS_ALLOWED = 'allowed';
    const STATUS_REJECTED = 'prohibited';

    /**
     * @return HasMany
     */
    public function cells(): HasMany
    {
        return $this->hasMany(SheetCell::class);
    }
}
