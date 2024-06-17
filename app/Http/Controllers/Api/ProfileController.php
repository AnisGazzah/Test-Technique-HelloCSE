<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use App\Services\FileUploadService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    use ApiResponser;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * @OA\Get (
     *     path="/api/profiles",
     *     summary="Active profiles list",
     *     tags={"Profiles"},
     *     description="This api returns a list of all active profiles.",
     *     operationId="profilesList",
     *     @OA\Response( response=200, description="successful operation", @OA\JsonContent() ),
     * )
     */
    public function index()
    {
        $profiles = Profile::active()->get();

        return $this->successResponse(ProfileResource::collection($profiles), __('profile.messages.retrieved'), 201);
    }

    /**
     * @OA\Post(
     *     path="/api/profiles",
     *     summary="Create a profile",
     *     security={{"Sanctum":{}}},
     *     tags={"Profiles"},
     *     description="This API creates a profile.",
     *     operationId="createProfile",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Pass user information",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"first_name", "last_name", "image", "status"},
     *                 @OA\Property(property="first_name", type="string", example="John"),
     *                 @OA\Property(property="last_name", type="string", example="Doe"),
     *                 @OA\Property(property="image", type="string", format="binary"),
     *                 @OA\Property(property="status", type="string", example="active")
     *             )
     *         )
     *     ),
     *     @OA\Response( response=200, description="successful operation", @OA\JsonContent() ),
     *     @OA\Response(response=400, description="Bad Request", @OA\JsonContent() ),
     * )
     */
    public function store(ProfileRequest $request)
    {
        $profileData = $request->validated();

        $profileData['image'] = $this->fileUploadService->upload($request->file('image'), 'images/profile');

        $profile = Profile::create($profileData);

        return $this->successResponse(new ProfileResource($profile), __('profile.messages.created'), 201);
    }

    /**
     * @OA\Put(
     *     path="/api/profiles/{id}",
     *     summary="Update a profile",
     *     security={{"Sanctum":{}}},
     *     tags={"Profiles"},
     *     description="This API updates a profile.",
     *     operationId="updateProfile",
     *     @OA\Parameter(
     *         description="Pass a profile ID.",
     *         in="path",
     *         name="id",
     *         required=true,
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Pass user information",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"first_name", "last_name", "image", "status"},
     *                 @OA\Property(property="first_name", type="string", example="John"),
     *                 @OA\Property(property="last_name", type="string", example="Doe"),
     *                 @OA\Property(property="image", type="string", format="binary"),
     *                 @OA\Property(property="status", type="string", example="active")
     *             )
     *         )
     *     ),
     *     @OA\Response( response=200, description="successful operation", @OA\JsonContent() ),
     *     @OA\Response( response=401, description="Unauthenticated", @OA\JsonContent() )
     * )
     */
    public function update(Profile $profile, Request $request)
    {
        $profileData = $request->validated();

        $profileData['image'] = $this->fileUploadService->upload($request->file('image'), 'images/profile');

        $profile = $profile->update($profileData);

        return $this->successResponse(new ProfileResource($profile), __('profile.messages.updated'));
    }

    /**
     * @OA\Delete (
     *     path="/api/profiles/{id}",
     *     summary="Delete an profile by ID.",
     *     security={{"Sanctum":{}}},
     *     tags={"Profiles"},
     *     description="Delete an profile by ID.",
     *     operationId="deleteprofile",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Profile ID",
     *         required=true,
     *     ),
     *     @OA\Response( response=200, description="successful operation", @OA\JsonContent() ),
     *     @OA\Response( response=401, description="Unauthenticated", @OA\JsonContent() )
     *     ),
     * )
     */
    public function destroy(Profile $profile)
    {
        $profile->delete();

        return $this->successResponse(null, __('profile.messages.deleted'));
    }

}
