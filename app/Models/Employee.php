<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'uuid';

    protected $guarded = ["created_at", "updated_at"];

    protected $appends = [
        "image_url"
    ];

    public function getImageUrlAttribute()
    {
        return route("file.avatar", ['file_name' => $this->image, "employee_id" => $this->uuid]);
    }

    // public function getImageAttribute()
    // {
    //     return route("file.avatar", ["filename" => $this->image]);
    // }


    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id', 'uuid');
    }
}
