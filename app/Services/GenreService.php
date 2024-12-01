<?php

namespace App\Services;

use App\Models\Genre;
use Illuminate\Support\Facades\DB;
use Exception;

class GenreService {         
    public function getAllGenres(array $params) {
        try {
            $query = Genre::query();
            if ($params['search']){
                $query->where('name', 'like', '%' . $params['search'] . '%');
            }

            if ($params['getAllData']) {
                $genres = $query->get();
            } else {
                $genres = $query->paginate($params['perPage'], ['*'], 'page', $params['page']);
            }
            return response()->json(['status' => 'success', 'response' => $genres]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function getGenre(string $slug) {
        try {
            $genre = Genre::where('slug', $slug);

            if (!$genre) {
                return response()->json(['status' => 'failed', 'response' => 'Genre not found'], 404);
            }

            return response()->json(['status' => 'success', 'response' => $genre]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function createGenre(array $request) {
        try {
            $genre = DB::transaction(function() use ($request) { 
                return Genre::create([
                    'name' => $request['name'],
                    'slug' => $request['slug'],
                ]);     
            });

            return response()->json(['status' => 'success', 'response' => $genre]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function updateGenre(array $request, int $id) {
        try {
            $genre = DB::transaction(function() use ($id, $request) {
                $genre = Genre::find($id);
                
                if (!$genre) {
                    throw new Exception("Genre not found");
                }

                $genre->fill([
                    'name' => $request['name'] ?? $genre->name,
                    'slug' => $request['slug'] ?? $genre->slug,
                ])->save();

                return $genre;
            });

            return response()->json(['status' => 'success', 'response' => $genre]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function deleteGenre(int $id) {
        try {
            $genre = DB::transaction(function() use ($id) {
                $genre = Genre::find($id);

                if (!$genre) {
                    throw new Exception("Genre not found");
                }

                $genre->delete();

                return $genre;
            });

            return response()->json(['status' => 'success', 'response' => 'Genre deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }
}
