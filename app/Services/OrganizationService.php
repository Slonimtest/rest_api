<?php

namespace App\Services;

use App\Models\Activitie;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class OrganizationService
 * @package App\Services
 */
class OrganizationService
{
    /**
     * Get all organization
     *
     * @return Collection|Organization[]
     */
    public function listAll(): Collection
    {
        return Organization::with(['building', 'phones', 'activities'])->get();
    }

    /**
     * Get organization by id
     *
     * @param int $id
     *
     * @return Organization|null
     */
    public function getById(int $id): ?Organization
    {
        return Organization::with(['building', 'phones', 'activities'])->find($id);
    }

    /**
     * Create organization
     *
     * @param array $data
     *
     * @return Organization
     */
    public function create(array $data): Organization
    {
        $organization = Organization::create([
            'name' => $data['name'],
            'building_id' => $data['building_id']
        ]);

        if (!empty($data['phones'])) {
            $organization->phones()->createMany(
                array_map(fn($phone) => ['phone' => $phone], $data['phones'])
            );
        }

        if (!empty($data['activities'])) {
            $organization->activities()->sync($data['activities']);
        }

        return $organization->load(['building', 'phones', 'activities']);
    }

    /**
     * Update organization
     *
     * @param Organization $organization
     * @param array $data
     *
     * @return Organization
     */
    public function update(Organization $organization, array $data): Organization
    {
        $organization->update([
            'name' => $data['name'] ?? $organization->name,
            'building_id' => $data['building_id'] ?? $organization->building_id
        ]);

        if (isset($data['phones'])) {
            $organization->phones()->delete();
            $organization->phones()->createMany(
                array_map(fn($phone) => ['phone' => $phone], $data['phones'])
            );
        }

        if (isset($data['activities'])) {
            $organization->activities()->sync($data['activities']);
        }

        return $organization->load(['building', 'phones', 'activities']);
    }

    /**
     * Delete organization
     *
     * @param Organization $Organization
     *
     * @return void
     */
    public function delete(Organization $organization): void
    {
        $organization->delete();
    }

    /**
     * Get organization by building
     *
     * @param int $buildingId
     *
     * @return Collection
     */
    public function listByBuilding(int $buildingId): Collection
    {
        return Organization::with(['building', 'phones', 'activities'])
            ->where('building_id', $buildingId)
            ->get();
    }

    /**
     * Get organization by activitys
     *
     * @param array $activityIds
     *
     * @return Collection
     */
    public function listByActivity(array $activityIds): Collection
    {
        return Organization::with(['building', 'phones', 'activities'])
            ->whereHas('activities', fn($query) => $query->whereIn('id', $activityIds))
            ->get();
    }

    /**
     * Search organization by name
     *
     * @param string|null $name
     *
     * @return Collection
     */
    public function searchByName(?string $name): Collection
    {
        return Organization::with(['building', 'phones', 'activities'])
            ->when($name, fn($q, $name) => $q->where('name', 'like', "%{$name}%"))
            ->get();
    }

    /**
     * Search organization by activity name
     *
     * @param string|null $activityName
     *
     * @return Collection|null
     */
    public function searchByActivityName(?string $activityName): ?Collection
    {
        if (empty($activityName)) {
            return null;
        }

        $matched = Activitie::where('name', 'like', "%{$activityName}%")->get();

        $activityIds = [];
        if (!$matched) {
            foreach ($matched as $act) {
                dd($act);
                if ($act->parent_id === null) {
                    $activityIds[] = $act->id;
                    $children = Activitie::where('parent_id', $act->id)->pluck('id')->toArray();
                    $activityIds = array_merge($activityIds, $children);
                    $grandChildren = Activitie::whereIn('parent_id', $children)->pluck('id')->toArray();
                    $activityIds = array_merge($activityIds, $grandChildren);
                } else {
                    $activityIds[] = $act->id;
                }
            }

            $activityIds = array_unique($activityIds);
        }

        if (empty($activityIds)) {
            return null;
        }

        return Organization::with(['building', 'phones', 'activities'])
            ->whereHas('activities', fn($q) => $q->whereIn('id', $activityIds))
            ->get();
    }

    /**
     * Search organizations with in radius
     *
     * @param float $lat
     * @param float $lng
     * @param float $radiusKm
     *
     * @return Collection
     */
    public function searchWithinRadius(float $lat, float $lng, float $radiusKm): Collection
    {
        $distanceFormula = "
        6371 * acos(
            greatest(least(
                cos(radians(?)) *
                cos(radians(buildings.latitude)) *
                cos(radians(buildings.longitude) - radians(?)) +
                sin(radians(?)) *
                sin(radians(buildings.latitude)),
            1), -1)
        )";

        return Organization::selectRaw(
            "organizations.*,
        {$distanceFormula} AS distance",
            [$lat, $lng, $lat]
        )
            ->join('buildings', 'organizations.building_id', '=', 'buildings.id')
            ->whereRaw("{$distanceFormula} <= ?", [$lat, $lng, $lat, $radiusKm])
            ->orderBy('distance')
            ->with(['building', 'phones', 'activities'])
            ->get();
    }

    /**
     * Search organizations with in rectangle
     *
     * @param float $lat1
     * @param float $lat2
     * @param float $lng1
     * @param float $lng2
     *
     * @return Collection
     */
    public function searchWithinRectangle(float $lat1, float $lat2, float $lng1, float $lng2): Collection
    {
        return Organization::with(['building', 'phones', 'activities'])
            ->whereHas(
                'building',
                fn($query) =>
                $query->whereBetween('latitude', [$lat1, $lat2])
                    ->whereBetween('longitude', [$lng1, $lng2])
            )
            ->get();
    }
}
