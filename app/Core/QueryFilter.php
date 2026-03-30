<?php

declare(strict_types=1);

namespace App\Core;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilter
{
    public function __construct(
        protected Request $request,
    ) {}

    public function apply(Builder $query): Builder
    {
        foreach ($this->filters() as $param => $method) {
            if (! method_exists($this, $method)) {
                continue;
            }

            if ($this->request->filled($param)) {
                $this->{$method}($query);
            }
        }

        return $query;
    }

    /**
     * @return array<string, string> query parameter name => method name
     */
    abstract protected function filters(): array;
}
