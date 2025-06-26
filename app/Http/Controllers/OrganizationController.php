<?php

namespace App\Http\Controllers;

use App\Http\Requests\Organization\ListByActivityRequest;
use App\Http\Requests\Organization\ListByBuildingRequest;
use App\Http\Requests\Organization\SearchByActivityNameRequest;
use App\Http\Requests\Organization\SearchByNameRequest;
use App\Http\Requests\Organization\WithinRadiusRequest;
use App\Models\Activitie;
use App\Models\Organization;
use App\Services\OrganizationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class OrganizationController
 * @package App\Http\Controllers
 *
 * @OA\Tag(
 *     name="Organizations",
 *     description="API Endpoints for Organizations"
 * )
 */
class OrganizationController extends ApiController
{
    /**
     * @param OrganizationService $service
     */
    public function __construct(protected OrganizationService $service)
    {
        $this->middleware('api');
    }

    /**
     * Display all a listing of the organizations.
     *
     * @OA\Get(
     *     path="/api/buildings",
     *     tags={"Organizations"},
     *     summary="Get list of all organizations",
     *     @OA\Response(
     *         response=200,
     *         description="List of organizations"
     *     ),
     *     security={{"ApiKeyAuth": {}}}
     * )
     */
    public function index()
    {
        return response()->json($this->service->listAll());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified organizations.
     *
     * @OA\Get(
     *     path="/api/organizations/{id}",
     *     tags={"Organizations"},
     *     summary="Get organization by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Organization object"
     *     ),
     *     @OA\Response(response=404, description="Not Found"),
     *     security={{"ApiKeyAuth": {}}}
     * )
     *
     * @param Organization $id
     */
    public function show(Organization $id)
    {
        $organization = $this->service->getById($id->id);

        if (!$organization) {
            return response()->json(['message' => 'Not Found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($organization);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Organization $organization)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organization $organization)
    {
        //
    }

    /**
     * Get list all organizations in building
     *
     * @OA\Get(
     *     path="/api/organizations/by-building/{buildingId}",
     *     tags={"Organizations"},
     *     summary="Get organizations by building ID",
     *     security={{"ApiKeyAuth": {}}},
     *     @OA\Parameter(name="buildingId", in="query", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Filtered organizations"),
     *     @OA\Response(response=400, description="Validation error")
     * )
     *
     * @param ListByBuildingRequest $request
     *
     * @return JsonResponse
     */
    public function byBuilding(ListByBuildingRequest $request): JsonResponse
    {
        $request = $request->validated();

        $organizations = $this->service->listByBuilding($request['buildingId']);

        return $this->successResponseWithData($organizations);
    }

    /**
     * Get list all organizations by activity
     *
     * @OA\Get(
     *     path="/api/organizations/by-activity/{activityId}",
     *     tags={"Organizations"},
     *     summary="Get organizations by activity ID (including children)",
     *     security={{"ApiKeyAuth": {}}},
     *     @OA\Parameter(name="activityId", in="query", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Filtered organizations"),
     *     @OA\Response(response=400, description="Validation error")
     * )
     *
     * @param ListByActivityRequest $request
     *
     * @return JsonResponse
     */
    public function byActivity(ListByActivityRequest $request): JsonResponse
    {
        $request = $request->validated();
        $activityIds = array_merge(
            [$request['activityId']],
            Activitie::where('parent_id', $request['activityId'])->pluck('id')->toArray()
        );

        $organizations = $this->service->listByActivity($activityIds);

        return $this->successResponseWithData($organizations);
    }

    /**
     * Search organization by name
     *
     * @OA\Get(
     *     path="/api/organizations/search",
     *     tags={"Organizations"},
     *     summary="Search organizations by name",
     *     security={{"ApiKeyAuth": {}}},
     *     @OA\Parameter(name="name_organization", in="query", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Search results"),
     *     @OA\Response(response=404, description="Not Found")
     * )
     *
     * @param SearchByNameRequest $request
     *
     * @return JsonResponse
     */
    public function searchByName(SearchByNameRequest $request): JsonResponse
    {
        $request = $request->validated();

        $organization = $this->service->searchByName($request['name_organization']);

        if (!$organization) {
            return $this->exceptionResponse('Not Found', Response::HTTP_NOT_FOUND);
        }

        return $this->successResponseWithData($organization);
    }

    /**
     * Search organizations by activity name
     *
     * @OA\Get(
     *     path="/api/organizations/search/by_activity",
     *     tags={"Organizations"},
     *     summary="Search organizations by activity name (hierarchical)",
     *     security={{"ApiKeyAuth": {}}},
     *     @OA\Parameter(name="name_activity", in="query", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Search results"),
     *     @OA\Response(response=404, description="Not Found")
     * )
     *
     * @param SearchByActivityNameRequest $request
     *
     * @return JsonResponse
     */
    public function searchByActivityName(SearchByActivityNameRequest $request): JsonResponse
    {
        $request = $request->validated();

        $organizations = $this->service->searchByActivityName($request['name_activity']);

        if ($organizations->isEmpty()) {
            return $this->exceptionResponse('Not Found', Response::HTTP_NOT_FOUND);
        }

        return $this->successResponseWithData($organizations);
    }

    /**
     * Search organizations within radius
     *
     * @OA\Get(
     *     path="/api/organizations/within-radius",
     *     tags={"Organizations"},
     *     summary="Search organizations within radius",
     *     security={{"ApiKeyAuth": {}}},
     *     @OA\Parameter(name="lat", in="query", required=true, @OA\Schema(type="number", format="float")),
     *     @OA\Parameter(name="lng", in="query", required=true, @OA\Schema(type="number", format="float")),
     *     @OA\Parameter(name="radius", in="query", required=true, @OA\Schema(type="number", format="float")),
     *     @OA\Response(response=200, description="Search results"),
     *     @OA\Response(response=404, description="Not Found")
     * )
     *
     * @param WithinRadiusRequest $request
     *
     * @return JsonResponse
     */
    public function withinRadius(WithinRadiusRequest $request): JsonResponse
    {
        $request = $request->validated();

        $organizations = $this->service->searchWithinRadius($request['lat'], $request['lng'], $request['radius']);

        if ($organizations->isEmpty()) {
            return $this->exceptionResponse('Not found organization in radus', Response::HTTP_NOT_FOUND);
        }

        return $this->successResponseWithData($organizations);
    }
}
