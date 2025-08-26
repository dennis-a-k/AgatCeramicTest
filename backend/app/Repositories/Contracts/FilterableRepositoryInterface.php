<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface FilterableRepositoryInterface extends RepositoryInterface
{
    public function applyFilters(Request $request);
    public function getAvailableFilters(Request $request);
}
