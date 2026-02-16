<?php

namespace App\Policies;

use App\Models\ContactGroup;
use App\Models\User;

class ContactGroupPolicy
{
    public function view(User $user, ContactGroup $contactGroup)
    {
        return $user->id === $contactGroup->user_id;
    }

    public function update(User $user, ContactGroup $contactGroup)
    {
        return $user->id === $contactGroup->user_id;
    }

    public function delete(User $user, ContactGroup $contactGroup)
    {
        return $user->id === $contactGroup->user_id;
    }
}