<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(string[] $array)
 * @method static where(string $string, mixed $request)
 */
class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public $timestamps = false;

    public static function fromKey($key)
    {
        return self::where('key', $key)->firstOrFail()->value;
    }
}
