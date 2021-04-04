<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use \App\Models\User;
use \App\Models\Role;
use \App\Models\TicketStatus;

class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return redirect('/admin/dashboard');
    }

    public function dashboard()
    {
        return view('admin.index');
    }

    public function getUsers()
    {
    
        // dd(Auth::user()->roles);
        return User::orderBy('name')->get();
    }

    // Roles

    public function getRoles()
    {
        return Role::orderBy('name')->get();
    }

    public function saveRole(Request $request)
    {
        // Validate
        return Role::updateOrCreate(
            [ 'id' => $request->id ],
            [ 'name' => $request->name, 'slug' => $request->slug]
        );
    }

    // TicketStatus
    public function getTicketStatuses()
    {
        return TicketStatus::orderBy('name')->get();
    }

    public function saveTicketStatus(Request $request)
    {
        // Validate
        return TicketStatus::updateOrCreate(
            [ 'id' => $request->id ],
            [ 'name' => $request->name, 'status' => $request->status]
        );
    }
}
