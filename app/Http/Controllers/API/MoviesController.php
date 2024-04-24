<?php

namespace App\Http\Controllers\API;

use App\Models\Movie;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;


class MoviesController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $search = $request->q;
        $perPage = $request->input('per_page', 10);
        
        try {
            
            $query = Movie::where('user_id', auth()->user()->id)
            ->with('director')
            ->when($search, function ($query) use ($search) {
            return $query->where('title', 'like', "%$search%")
                ->orWhere('year', 'like', "%$search%")
                ->orWhere('synopsis', 'like', "%$search%")
                ->orWhereHas('director', function ($query) use ($search) {
                    $query->where('name', 'like', "%$search%");
                });
        });
            $movies = $query->paginate($perPage)->withQueryString();

            $movies->getCollection()->transform(function ($movie) {
                $movieCount = Movie::where('user_id', $movie->user_id)->count();
                return [
                    'id' => $movie->id,
                    'title' => $movie->title,
                    'director' => $movie->director->name,
                    'year' => $movie->year,
                    'synopsis' => $movie->synopsis,
                    'created_at' => $movie->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $movie->updated_at->format('Y-m-d H:i:s'),
                    'user_movie_count' => $movieCount
                ];
            });

            return $this->handleResponse('Movies retrieved successfully', $movies);
        } catch (Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
}
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request -> validate([
                'title' => 'required',
                'director_id' => 'required',
                'year' => 'required',
                'synopsis' => 'required',
            ]);

            
            $request['user_id'] = $request->user()->id;

            $movie = Movie::create($request->all());

            return $this->handleResponseNoPagination('Movie created successfully', $movie);
        } catch (Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $movie = Movie::where('user_id', auth()->user()->id)->where('id', $id)->with('director')->first();
            if ($movie) {
                $movieData = [
                    'id' => $movie->id,
                    'title' => $movie->title,
                    'director' => $movie->director->name,
                    'year' => $movie->year,
                    'synopsis' => $movie->synopsis,
                ];
                return $this->handleResponseNoPagination('Movie retrieved successfully', $movieData, 200);
            } else {
                return $this->handleError('Movie not found', 400);
            }
        } catch (Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, Movie $movie)
    {
        try {
            $movie->update($request->all());
            return $this->handleResponseNoPagination('Movie updated successfully', $movie, 200);
        } catch (Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie)
    {
        try {
            $movie = Movie::find($movie->id);
            if ($movie) {
                $movie->delete();
                return $this->handleResponseNoPagination('Movie deleted successfully', $movie, 200);
            } else {
                return $this->handleError('Movie not found', 400);
            }
        } catch (Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
    }

    public function getAllMovies(Request $request)
    {
        $search = $request->q;
        $perPage = $request->input('per_page', 10);

        $query = Movie::query()
            ->with('director', 'user')
            ->when($search, function ($query) use ($search) {
                return $query->where('title', 'like', "%$search%")
                    ->orWhere('year', 'like', "%$search%")
                    ->orWhere('synopsis', 'like', "%$search%")
                    ->orWhereHas('director', function ($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    });
            });

        $movies = $query->paginate($perPage)->withQueryString();

        $movies->getCollection()->transform(function ($movie) {
            return [
                'title' => $movie->title,
                'director' => $movie->director->name,
                'year' => $movie->year,
                'synopsis' => $movie->synopsis,
                'created_at' => $movie->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $movie->updated_at->format('Y-m-d H:i:s'),
                'created_by' => $movie->user->username,
            ];
        });
        return $this->handleResponse('Movies retrieved successfully', $movies);
    }

    function getMoviesByUserId (Request $request, $id) {
        $search = $request->q;
        $perPage = $request->input('per_page', 10);

        $query = Movie::where('user_id', $id)
            ->with('director')
            ->when($search, function ($query) use ($search) {
                return $query->where('title', 'like', "%$search%")
                    ->orWhere('year', 'like', "%$search%")
                    ->orWhere('synopsis', 'like', "%$search%")
                    ->orWhereHas('director', function ($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    });
            });

        $movies = $query->paginate($perPage)->withQueryString();

        $movies->getCollection()->transform(function ($movie) {
            return [
                'title' => $movie->title,
                'director' => $movie->director->name,
                'year' => $movie->year,
                'synopsis' => $movie->synopsis,
                'created_at' => $movie->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $movie->updated_at->format('Y-m-d H:i:s'),
            ];
        });

        return $this->handleResponse('Movies retrieved successfully', $movies);
    }
}
