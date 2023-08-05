<?php

namespace App\Http\Controllers\Api;

use App\Models\{
    PricePeriod,
};
use App\Http\Requests\Rent\{
    InputRentRequest,
    UpdateRentRequest,
};
use App\Http\Controllers\Controller;
use App\Http\Resources\PricePeriodResource;


/**
 * @OA\Tag(
 *     name="Rents",
 *     description="Rents based routes",
 * )
 */
class RentController extends Controller
{
     /**
     * @OA\Get(
     *     path="/rents",
     *     tags={"Rents"},
     *     summary="Get Rents",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 ref="#/components/schemas/Rent",
     *             ),
     *         ),
     *     ),
     *     @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/DefaultUnauthorized")),
     *     @OA\Response(response=403, description="Forbidden", @OA\JsonContent(ref="#/components/schemas/DefaultForbidden")),
     *     @OA\Response(response=404, description="Not Found", @OA\JsonContent(ref="#/components/schemas/DefaultMessage")),
     * )
     */
    public function index()
    {
        $items = PricePeriod::get();

        return PricePeriodResource::collection($items);
    }

    /**
     * @OA\Get(
     *     path="/rents/{id}",
     *     tags={"Rents"},
     *     summary="Get Rent by ID",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         required=true,
     *         name="id",
     *         in="path",
     *         description="ID",
     *         @OA\Schema(type="integer", minimum=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 ref="#/components/schemas/Rent",
     *             ),
     *         ),
     *     ),
     *     @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/DefaultUnauthorized")),
     *     @OA\Response(response=403, description="Forbidden", @OA\JsonContent(ref="#/components/schemas/DefaultForbidden")),
     *     @OA\Response(response=404, description="Not Found", @OA\JsonContent(ref="#/components/schemas/DefaultMessage")),
     * )
     */
    public function show(int $id)
    {
        $item = PricePeriod::find($id);

        if (!$item) {
            return $this->errorBadRequest(null, 'Rent period not found!');
        }

        return new PricePeriodResource($item);
    }

     /**
     * @OA\Post(
     *     path="/rents",
     *     tags={"Rents"},
     *     summary="Create Rent",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(ref="#/components/schemas/InputRent"),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 ref="#/components/schemas/Rent",
     *             ),
     *         ),
     *     ),
     *     @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/DefaultUnauthorized")),
     *     @OA\Response(response=404, description="Not Found", @OA\JsonContent(ref="#/components/schemas/DefaultMessage")),
     *     @OA\Response(response=403, description="Forbidden", @OA\JsonContent(ref="#/components/schemas/DefaultForbidden")),
     * )
     */
    public function store(InputRentRequest $request)
    {
        $data = $request->validated();
        $user = auth('api')->user();

        $item = new PricePeriod();
        $item->price = $request->has('price') ? $data['price'] : PricePeriod::DEFAULT_PRICE;
        $item->start = $request->has('start') ? $data['start'] : now();
        $item->end = $request->has('end') ? $data['end'] : now();
        $item->created_by = $user->id;
        $item->updated_by = $user->id;
        $item->save();

        return new PricePeriodResource(PricePeriod::findOrFail($item->id));
    }

    /**
     * @OA\Post(
     *     path="/rents/{id}",
     *     tags={"Rents"},
     *     summary="Update Rent",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(ref="#/components/schemas/InputRent"),
     *     ),
     *     @OA\Parameter(
     *         required=true,
     *         name="id",
     *         in="path",
     *         description="ID",
     *         @OA\Schema(type="integer", minimum=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 ref="#/components/schemas/Rent",
     *             ),
     *         ),
     *     ),
     *     @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/DefaultUnauthorized")),
     *     @OA\Response(response=404, description="Not Found", @OA\JsonContent(ref="#/components/schemas/DefaultMessage")),
     *     @OA\Response(response=403, description="Forbidden", @OA\JsonContent(ref="#/components/schemas/DefaultForbidden")),
     * )
     */
    public function update(int $id, UpdateRentRequest $request)
    {
        $user = auth('api')->user();
        $data = $request->validated();

        $item = PricePeriod::findOrFail($id);

        $item->price = $request->has('price') ? $data['price'] : PricePeriod::DEFAULT_PRICE;
        $item->start = $request->has('start') ? $data['start'] : now();
        $item->end = $request->has('end') ? $data['end'] : now();
        $item->updated_at = now();
        $item->updated_by = $user->id;
        $item->save();

        return new PricePeriodResource(PricePeriod::findOrFail($item->id));
    }

    /**
     * @OA\Delete(
     *     path="/rents/{id}",
     *     tags={"Rents"},
     *     summary="Delete a rent category",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID",
     *         @OA\Schema(type="integer", minimum=1),
     *     ),
     *     @OA\Response(response=204, description="No Content"),
     *     @OA\Response(response=400, description="Bad Request", @OA\JsonContent(ref="#/components/schemas/DefaultBadRequest")),
     *     @OA\Response(response=403, description="Forbidden", @OA\JsonContent(ref="#/components/schemas/DefaultForbidden")),
     *     @OA\Response(response=404, description="Not Found", @OA\JsonContent(ref="#/components/schemas/DefaultMessage")),
     * )
     */
    public function destroy(int $id)
    {
        $item = PricePeriod::find($id);

        if (!$item) {
            return $this->errorBadRequest(null, 'Rent period not found!');
        }
        $item->delete();

        return $this->successNoContent();
    }
}
