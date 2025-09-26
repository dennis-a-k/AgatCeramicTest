<?php

namespace App\Repositories\Contracts;

interface FilterableRepositoryInterface extends RepositoryInterface, FilterBuilderInterface, AvailableFiltersInterface, CategoryHelperInterface
{
}
