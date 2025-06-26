<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Organization
 * @package App\Models
 *
 * @property int $id
 * @property string $name
 * @property int $building_id
 */
class Organization extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'building_id',
    ];

    /**
     * Current build organization
     *
     * @return BelongsTo
     */
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * Current phones organization
     *
     * @return HasMany
     */
    public function phones(): HasMany
    {
        return $this->hasMany(OrganizationPhone::class);
    }

    /**
     * Current activities organization
     *
     * @return BelongsToMany
     */
    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activitie::class, 'organization_activities', 'organization_id', 'activity_id');
    }
}
