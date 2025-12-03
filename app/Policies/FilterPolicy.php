<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Filter;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilterPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Filter');
    }

    public function view(AuthUser $authUser, Filter $filter): bool
    {
        return $authUser->can('View:Filter');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Filter');
    }

    public function update(AuthUser $authUser, Filter $filter): bool
    {
        return $authUser->can('Update:Filter');
    }

    public function delete(AuthUser $authUser, Filter $filter): bool
    {
        return $authUser->can('Delete:Filter');
    }

    public function restore(AuthUser $authUser, Filter $filter): bool
    {
        return $authUser->can('Restore:Filter');
    }

    public function forceDelete(AuthUser $authUser, Filter $filter): bool
    {
        return $authUser->can('ForceDelete:Filter');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Filter');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Filter');
    }

    public function replicate(AuthUser $authUser, Filter $filter): bool
    {
        return $authUser->can('Replicate:Filter');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Filter');
    }

}