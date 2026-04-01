<?php

declare(strict_types=1);

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AcademicYear extends Model
{
    use HasUuids;

    protected $table = 'academic_years';
    protected $guarded = [];

    public function classes()
    {
        return $this->hasMany(ClassModel::class, 'academic_year_id');
    }
}
