<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class ServiceRoom extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasJsonRelationships;

    protected $fillable = ['branch_id', 'category_ids', 'name', 'active'];

    protected function casts(): array
    {
        return [
            'category_ids' => 'json',
            'active' => 'boolean',
        ];
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function categories()
    {
        return $this->belongsToJson(ServiceCategory::class, 'category_ids');
    }

    public function category_names()
    {
        return $this->categories()
            ->pluck('name')
            ->implode(', ');
    }
}
