<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait HandlesPaginationAndSearch
{
    /**
     * Apply search and pagination to a query.
     */
    protected function applyFiltersAndPaginate(Builder $query, Request $request, array $searchFields = [])
    {
        $search = $request->input('q') ?? $request->input('search');
        $perPage = (int) $request->input('per_page', 15);

        if ($search) {
            $query->where(function ($q) use ($search, $searchFields) {
                foreach ($searchFields as $field) {
                    $q->orWhere($field, 'LIKE', '%' . $search . '%');
                }
            });
        }

        if ($perPage === -1) {
            return $query->get();
        }

        return $query->paginate($perPage);
    }
}
