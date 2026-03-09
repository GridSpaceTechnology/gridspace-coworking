<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class HostApprovalController extends Controller
{
    public function index()
    {
        $pendingHosts = User::where('role', 'host')
            ->where('approved', false)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.host-approval', compact('pendingHosts'));
    }

    public function approve(User $user)
    {
        $user->update(['approved' => true]);
        
        return back()->with('success', 'Host approved successfully!');
    }

    public function reject(User $user)
    {
        $user->delete();
        
        return back()->with('success', 'Host rejected and removed from system.');
    }
}
