<?php

namespace Laravel\Nova\Http\Requests;

use Laravel\Nova\Query\Builder as QueryBuilder;

class ResourceIndexRequest extends NovaRequest
{
    use CountsResources, QueriesResources;

    /**
     * Get the paginator instance for the index request.
     *
     * @param  \Laravel\Nova\Http\Requests\ResourceIndexRequest  $request
     * @param  string  $resource
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return array
     */
    public function searchIndex()
    {
        return (new QueryBuilder($resource = $this->resource()))->search(
            $this, $this->newQuery(), $this->search,
            $this->filters()->all(), $this->orderings(), $this->trashed()
        )->paginate(
            $this->viaRelationship()
                        ? $resource::$perPageViaRelationship
                        : ($this->perPage ?? $resource::perPageOptions()[0])
        );
    }

    /**
     * Get the count of the resources.
     *
     * @return int
     */
    public function toCount()
    {
        return $this->toQuery()->toBase()->getCountForPagination();
    }
}
