<?php

namespace App\Policies\FbReporting;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdAccountPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    { 
        
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    { 
        return $user->email != 'fbreview@revenuedriver.com';
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function view(User $user)
    { 
        return $user->email != 'fbreview@revenuedriver.com';
    }

    /**
     * Determine whether the user can create any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    { 
        return $user->email != 'fbreview@revenuedriver.com';
    }

    public function show($user)
    { 
        return $user->email != 'fbreview@revenuedriver.com';
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function update($user)
    {
        return $user->email != 'fbreview@revenuedriver.com';
    }


    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->email != 'fbreview@revenuedriver.com';
    }
}
