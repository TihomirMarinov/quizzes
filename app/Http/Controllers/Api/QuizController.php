<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FinishQuizRequest;
use App\Http\Requests\Quiz\StartQuizRequest;
use App\Http\Resources\QuizResource;
use App\Http\Resources\QuizResultResource;
use App\Models\QuizAnswer;
use App\Models\QuizResult;
use Carbon\Carbon;
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
     * @OA\Post(
     *     path="/quizs/start",
     *     tags={"Quizs"},
     *     summary="Start quizs",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 ref="#/components/schemas/IndexQuizTest",
     *             ),
     *         ),
     *     ),
     *     @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/DefaultUnauthorized")),
     *     @OA\Response(response=404, description="Not Found", @OA\JsonContent(ref="#/components/schemas/DefaultMessage")),
     *     @OA\Response(response=403, description="Forbidden", @OA\JsonContent(ref="#/components/schemas/DefaultForbidden")),
     * )
     */
    public function start(StartQuizRequest $request)
    {
        $data = $request->validated();


        $item = new QuizResult();
        $item->name = $request->has('name') ? $data['name'] : '';
        $item->last_name = $request->has('last_name') ? $data['last_name'] : '';
        $item->email = $request->has('email') ? $data['email'] : '';
        $item->score = 0;
        $item->wrong_answers = 0;
        $item->expired_at = Carbon::now()->addMinutes(5);
        $item->created_at = now();
        $item->updated_at = now();
        $item->save();

        return new QuizResource(QuizResult::findOrFail($item->id));
    }

    /**
     * @OA\Post(
     *     path="/quizs/{id}/finish",
     *     tags={"Quizs"},
     *     summary="Finish quiz",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/FinishQuiz"),
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
     *                 type="integer",
     *             ),
     *         ),
     *     ),
     *     @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/DefaultUnauthorized")),
     *     @OA\Response(response=403, description="Forbidden", @OA\JsonContent(ref="#/components/schemas/DefaultForbidden")),
     *     @OA\Response(response=404, description="Not Found", @OA\JsonContent(ref="#/components/schemas/DefaultMessage")),
     * )
     */
    public function finish(int $id, FinishQuizRequest $request)
    {
        $data = $request->validated();

        $item = QuizResult::where('expired_at', '>', now())->find($id);

        if (!$item) {
            return $this->errorNotFound('Result not found');
        }

        if ($request->has('answers')) {
            $correct = 0;
            $wrong = 0;

            $quizRezultAswers = QuizAnswer::whereIn('id', $data['answers'])->get();

            foreach($quizRezultAswers as $answer) {
                if ($answer->is_true) {
                    $correct = $correct + 1;
                } else {
                    $wrong = $wrong + 1;
                }
            }
            
            $item->score = $correct;
            $item->wrong_answers = $wrong;
            $item->expired_at = now();
            $item->save();
        }

        return new QuizResource(QuizResult::findOrFail($item->id));
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
