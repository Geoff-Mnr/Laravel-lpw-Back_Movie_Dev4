<?php

namespace App\Http\Controllers\API;

use App\Models\Movie;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class MoviesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = $request->q;

        try {
         $movies = Movie::where('title', 'like', "%$search%")
            ->orWhere('director', 'like', "%$search%")
            ->orWhere('synopsis', 'like', "%$search%")
            ->get();
            return $this->handleResponse('Movies retrieved successfully', $movies);}
        catch (Exception $e) {
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
                'director' => 'required',
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
    public function show(string $id, Request $request)
    {
        try {

            $userId = $request->user()->id;
            $movie = Movie::where('user_id, $userID')->where('id', $id)->with('user')->first();
            if ($movie) {
                return $this->handleResponseNoPagination('Movie retrieved successfully', $movie, 200);
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
            $movie = Movie::find($movie->id);
            if ($movie) {
                $movie->update($request->all());
                return $this->handleResponseNoPagination('Movie updated successfully', $movie, 200);
            } else {
                return $this->handleError('Movie not found', 400);
            }
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
}
