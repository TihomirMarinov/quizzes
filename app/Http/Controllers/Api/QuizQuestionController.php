<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Question\StoreQuestionRequest;
use App\Http\Resources\QuizQuestionResource;
use App\Models\QuizAnswer;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;

class QuizQuestionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/quizs/questions",
     *     tags={"Quizs"},
     *     summary="Get Quizs qestions",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/IndexQuiz"),
     *     ),
     *     @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/DefaultUnauthorized")),
     *     @OA\Response(response=404, description="Not Found", @OA\JsonContent(ref="#/components/schemas/DefaultMessage")),
     * )
     */
    public function index()
    {
       $items = QuizQuestion::with(['answers'])->get();

       return QuizQuestionResource::collection($items);
    }

    /**
     * @OA\Get(
     *     path="/quizs/questions/{id}",
     *     tags={"Quizs"},
     *     security={{"bearerAuth": {}}},
     *     summary="Get Quizs qestion by ID",
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
     *         @OA\JsonContent(ref="#/components/schemas/IndexQuiz"),
     *     ),
     *     @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/DefaultUnauthorized")),
     *     @OA\Response(response=404, description="Not Found", @OA\JsonContent(ref="#/components/schemas/DefaultMessage")),
     * )
     */
    public function show(int $id)
    {
        $item = QuizQuestion::with(['answers'])->find($id);

        if (!$item) {
            return $this->errorNotFound('Question not found');
        }

        return new QuizQuestionResource($item);
    }

    /**
     * @OA\Post(
     *     path="/quizs/questions",
     *     tags={"Quizs"},
     *     summary="Create question",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(ref="#/components/schemas/InputQuiz"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 ref="#/components/schemas/IndexQuiz",
     *             ),
     *         ),
     *     ),
     *     @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/DefaultUnauthorized")),
     *     @OA\Response(response=404, description="Not Found", @OA\JsonContent(ref="#/components/schemas/DefaultMessage")),
     *     @OA\Response(response=403, description="Forbidden", @OA\JsonContent(ref="#/components/schemas/DefaultForbidden")),
     * )
     */
    public function store(StoreQuestionRequest $request)
    {
        $user = auth('api')->user();
        $data = $request->validated();

        $item = new QuizQuestion();
        $item->question = $request->has('question') ? (string) $data['question'] : '';
        $item->created_by = $user->id;
        $item->updated_by = $user->id;
        $item->save();

        if ($request->has('answers')) {
            $answers = [];

            foreach ($data['answers'] as $answer) {
                $answers[] = [
                    'question_id' => $item->id,
                    'answer' => $answer['answer'] ?? '',
                    'is_true' => $answer['is_correct'] ?? '',
                    'created_at' => now(),
                    'created_by' => (int) ($user->id),
                    'updated_at' => now(),
                    'updated_by' => (int) ($user->id),
                ];
            }

            QuizAnswer::insert($answers);
        }

        $item->load(['answers']);

        return new QuizQuestionResource($item); 
    }

    /**
     * @OA\Post(
     *     path="/quizs/questions/{id}",
     *     tags={"Quizs"},
     *     summary="Update question by ID",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(ref="#/components/schemas/InputQuiz"),
     *     ),
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
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 ref="#/components/schemas/IndexQuiz",
     *             ),
     *         ),
     *     ),
     *     @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/DefaultUnauthorized")),
     *     @OA\Response(response=404, description="Not Found", @OA\JsonContent(ref="#/components/schemas/DefaultMessage")),
     *     @OA\Response(response=403, description="Forbidden", @OA\JsonContent(ref="#/components/schemas/DefaultForbidden")),
     * )
     */
    public function update(int $id, StoreQuestionRequest $request)
    {
        $user = auth('api')->user();
        $data = $request->validated();

        $item =  QuizQuestion::with(['answers'])->find($id);

        if (!$item) {
            return $this->errorNotFound('Question not found');
        }

        $item->question = $request->has('question') ? (string) $data['question'] : '';
        $item->created_by = $user->id;
        $item->updated_by = $user->id;
        $item->save();

        if ($request->has('answers')) {
            $item->answers()->delete();
            $answers = [];

            foreach ($data['answers'] as $answer) {
                $answers[] = [
                    'question_id' => $item->id,
                    'answer' => $answer['answer'] ?? '',
                    'is_true' => $answer['is_correct'] ?? '',
                    'created_at' => now(),
                    'created_by' => (int) ($user->id),
                    'updated_at' => now(),
                    'updated_by' => (int) ($user->id),
                ];
            }

            QuizAnswer::insert($answers);
        }

        $item->load(['answers']);

        return new QuizQuestionResource($item); 
    }

    /**
     * @OA\Delete(
     *     path="/quizs/questions/{id}",
     *     tags={"Quizs"},
     *     summary="Delete a question",
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
        $item = QuizQuestion::with(['answers'])->find($id);

        if (!$item) {
            return $this->errorNotFound('Question not found');
        }

        $item->answers()->delete();
        $item->delete();

        return $this->successNoContent();
    }
}
