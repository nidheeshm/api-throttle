<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = ['group_name', 'settings_name', 'value'];

    public function scopeSettings($query, $group, $settings) {
        return $query->where('group_name', $group)
                ->where('settings_name', $settings);
    }
}
