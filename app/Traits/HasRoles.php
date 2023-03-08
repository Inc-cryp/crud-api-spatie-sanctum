<?php

namespace App\Traits;

Trait HasRoles {
    function hasRoles($role)
    {
        if ($this->role !== null) {
            if ($this->role->name == $role) {
                return true;
            }
        }
        return false;
    }
}
