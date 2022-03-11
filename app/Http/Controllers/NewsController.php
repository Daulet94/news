<?php

namespace App\Http\Controllers;

use App\Http\Resources\NewsResource;
use App\Repository\NewsRepositoryInterface;
use App\Services\NewsServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NewsController extends Controller
{

    /**
     * @var NewsRepositoryInterface
     */
    private NewsRepositoryInterface $newsRepository;

    /**
     * @var NewsServiceInterface
     */
    private NewsServiceInterface $newsService;

    public function __construct(NewsRepositoryInterface $newsRepository, NewsServiceInterface $newsService)
    {
        $this->newsRepository = $newsRepository;
        $this->newsService = $newsService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->newsService->index();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return NewsResource
     */
    public function store(Request $request)
    {
        return new NewsResource($this->newsService->create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id)
    {
        if ($this->newsService->show($id)){
            return response()->json([
                'success' => true,
                'data' =>  $this->newsService->show($id)
            ],200);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'News not found!',
            ],404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update($id, Request $request)
    {
        if ($updated = $this->newsService->update($id, $request)){
            return response()->json([
                'success' => true,
                'data' =>  $this->newsService->update($id, $request)
            ],200);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Failed to update!',
            ],404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        if ($this->newsRepository->deleteById($id)) {
            $this->newsRepository->deleteById($id);
            return response()->json([
                'success' => true,
                'message' => 'Успешно удалено!'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Не удалось найти данные по id!'
        ]);
    }
}
