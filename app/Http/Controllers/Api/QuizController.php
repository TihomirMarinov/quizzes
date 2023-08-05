<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuizResultResource;
use App\Models\QuizResult;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * @OA\Get(
     *     path="/quizs/results",
     *     tags={"Quizs"},
     *     security={{"bearerAuth": {}}},
     *     summary="Get Quizs results",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/IndexQuizResult"),
     *     ),
     *     @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/DefaultUnauthorized")),
     *     @OA\Response(response=404, description="Not Found", @OA\JsonContent(ref="#/components/schemas/DefaultMessage")),
     * )
     */
    public function index()
    {
        return QuizResultResource::collection(QuizResult::get());
    }

     /**
     * @OA\Get(
     *     path="/quizs/results/{id}",
     *     tags={"Quizs"},
     *     security={{"bearerAuth": {}}},
     *     summary="Get Quizs result by ID",
     *     @OA\Parameter(
     *         required=true,
     *         name="id",
     *         in="path",
     *         description="Question ID",
     *         @OA\Schema(type="integer", minimum=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/IndexQuizResult"),
     *     ),
     *     @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/DefaultUnauthorized")),
     *     @OA\Response(response=404, description="Not Found", @OA\JsonContent(ref="#/components/schemas/DefaultMessage")),
     * )
     */
    public function show(int $id)
    {
        $item = QuizResult::find($id);

        if (!$item) {
            return $this->errorNotFound('Result not found');
        }

        return new QuizResultResource($item);
    }

    /**
     * @OA\Delete(
     *     path="/quizs/results/{id}",
     *     tags={"Quizs"},
     *     summary="Delete a result",
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
        $item = QuizResult::find($id);

        if (!$item) {
            return $this->errorNotFound('Result not found');
        }

        $item->delete();

        return $this->successNoContent();
    }
}
