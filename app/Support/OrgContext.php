<?php
namespace App\Support;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class OrgContext
{
    /**
     * Get the current organization for the authenticated user.
     *
     * @return Organization|null
     */
    public static function current(): ?Organization
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user) {
            return null;
        }

        $adminOrg = $user->organizations()->wherePivot('role', 'admin')->first();
        if ($adminOrg instanceof Organization) {
            return $adminOrg;
        }

        return $user->organizations()->first();
    }
}