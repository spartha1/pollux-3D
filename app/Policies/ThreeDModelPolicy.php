<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ThreeDModel;

class ThreeDModelPolicy
{
    public function view(User $user, ThreeDModel $model)
    {
        return true; // Public access for viewing
    }

    public function create(User $user)
    {
        return true; // Authenticated users can create
    }

    public function update(User $user, ThreeDModel $model)
    {
        return $user->id === $model->user_id;
    }

    public function delete(User $user, ThreeDModel $model)
    {
        return $user->id === $model->user_id;
    }
}
