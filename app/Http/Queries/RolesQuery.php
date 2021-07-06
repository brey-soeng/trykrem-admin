<?php


namespace App\Http\Queries;

use Spatie\Permission\Models\Role;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class RolesQuery extends QueryBuilder
{

    public function __construct( )
    {
        parent::__construct(Role::query());
        $this->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::partial('name')
        ]);
    }
}
