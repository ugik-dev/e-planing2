<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Timeline extends Model
{
    protected $fillable = ['user_id', 'year', 'metode', 'category', 'start', 'end'];
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query, $category = false, $id = false)
    {
        $currentDateTime = Carbon::now(); // Get the current date and time
        $query = $query->where('start', '<=', $currentDateTime)
            ->where('end', '>=', $currentDateTime);

        if ($category) {
            $query = $query->where('category', '=', $category);
        }

        if ($id) {
            $query = $query->where('id', '=', $id);
        }

        return $query;
    }
}
