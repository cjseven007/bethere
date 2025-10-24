<?php
namespace App\Http\Controllers;

use App\Models\Employee;
use App\Support\OrgContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $org = OrgContext::current();
        abort_if(!$org, 403, 'No organisation context');

        /** @var User|null $user */
        $user = Auth::user(); // Intelephense can infer this with the facade

        // Check admin role in this org
        $isAdmin = $user->organizations()
            ->where('organizations.id', $org->id)
            ->wherePivot('role', 'admin')
            ->exists();

        abort_if(!$isAdmin, 403);

        // $employees = Employee::with('embedding')
        //     ->where('organization_id', $org->id)
        //     ->orderBy('name')
        //     ->paginate(20);

        return view('users.index', compact('org'));
    }
}
