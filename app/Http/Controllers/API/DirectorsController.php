<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Models\Director;
use Illuminate\Http\Request;
use Exception;


class DirectorsController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search= $request->q;
        $perPage =$request->input('per_page', 10);

        try {
            $query = Director::when($search, function ($query) use ($search) {
                return $query->where('name', 'like', "%$search%");
            });

            $directors = $query->paginate($perPage)->withQueryString();
            $directors->getCollection()->transform(function ($director) {
                return [
                    'id' => $director->id,
                    'name' => $director->name,
                    'created_at' => $director->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $director->updated_at->format('Y-m-d H:i:s'),
                ];
            });


            return $this->handleResponse('Directors retrieved successfully', $directors, 200);
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
            $request->validate([
                'name' => 'required|min:3', 
            ]);

            $director = Director::create($request->all());
            return $this->handleResponseNoPagination('Director created successfully', $director, 200);
        } catch (Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Director $director)
    {
        try {
            return $this->handleResponseNoPagination('Director retrieved successfully', $director, 200);
        } catch (Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Director $director)
    {
        try {
            $request->validate([
                'name' => 'required'|'min:3',
            ]);

            $director->update($request->all());
            return $this->handleResponseNoPagination('Director updated successfully', $director, 200);
        } catch (Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
    }

    public function list()
    {
        try {
            $directors = Director::all();
            return $this->handleResponseNoPagination('Directors retrieved successfully', $directors, 200);
        } catch (Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Director $director)
    {
        // i want return 403 if the director has movies
        try {
            if ($director->movies->count() > 0) {
                return $this->handleError('Director has movies', 403);
            }
            $director->delete();
            return $this->handleResponseNoPagination('Director deleted successfully', $director, 200);
        } catch (Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
    }
}