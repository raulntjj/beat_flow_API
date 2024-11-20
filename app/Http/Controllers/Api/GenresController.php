<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\StoreGenreRequest;
use App\Http\Requests\UpdateGenreRequest;
use App\Services\GenreService;
use App\Models\Genre;

class GenreController {
    protected $genreService;

    public function __construct(GenreService $genreService) {
        $this->genreService = $genreService;
    }

    public function index(Request $request){
        $params = [
            'getAllData' => $request->getAllData ?? true,
            'perPage' => $request->perPage ?? 10,
            'currentPage' => $request->currentPage ?? 1,
            'search' => $request->search ?? false,
            'filter' => $request->filter ?? false,
        ];
        return $this->genreService->getAllGenres($params);
    }

    public function show(string $slug){
        return $this->genreService->getGenre($slug);
    }

    public function store(StoreGenreRequest $request){
        return $this->genreService->createGenre($request->validated());
    }

    public function update(UpdateGenreRequest $request, int $id){
        return $this->genreService->updateGenre($request->validated(), $id);
    }

    public function destroy(int $id){
        return $this->genreService->deleteGenre($id);
    }
}