<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use \App\Models\User;
use \App\Models\Role;
use \App\Models\Ticket;
use \App\Models\TicketStatus;
use Illuminate\Support\Facades\Validator;

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

    public function getUsers(Request $request)
    {
        $filters = [
            'is_admin' => 1,
        ];
        if ($request->has('filter')) {
            $filters['active'] = $request->filter;
        }
        return User::with('role')->where($filters)->orderBy('name')->get();
    }

    public function saveUser(Request $request)
    {
        // Validate
        $this->validate($request, [
            // 'fullName' => 'required',
            // 'email' => 'bail|required|email|unique:users',
            // 'password' => 'bail|required|min:6',
            // 'role_id' => 'required',
        ]);

        $user = User::updateOrCreate(
            ['id' => $request->id],
            [
                'name'  => $request->name,
                'role_id' => $request->role_id,
                'is_admin' => 1,
                'email' => $request->email,
                'active' => $request->active,
                'password' =>  bcrypt($request->password),
            ]
        );

        $user->role = $user->role;
        return $user;
    }

    public function deleteUser(Request $request)
    {
        User::destroy($request->all());
    }

    public function getCustomers(Request $request)
    {
        return User::with('customerInfo')->where('is_admin', 0)->orderBy('name')->paginate(15)->appends($request->page);
    }


    public function getTickets()
    {
        // $data = [];
        // foreach (Ticket::orderBy('created_at')->get() as $ticket) {
        //     $data[] = array(
        //         'ticket_id' => $ticket->id,
        //         'user'      => $ticket->user->name,
        //         'status'    => $ticket->ticketStatus->name,
        //         'subject'   => $ticket->subject,
        //         'created_at' => $ticket->created_at->diffForHumans()

        //     );
        // }
        return Ticket::with('user')->orderBy('created_at')->get();
    }

    public function editTicket($id)
    {
        
        return Ticket::with('replies','user')->find($id);
    }

    public function getReplies() {
        return Reply::with('user')->get();
    }

    // Roles

    public function getRoles()
    {
        return Role::orderBy('name')->get();
    }
    

    public function saveRole(Request $request)
    {
        // // Validate
        // $this->validate($request, [
        //     'slug' => 'unique:roles',
        // ]);
        $validator = Validator::make($request->all(),  [
            'name' => 'unique:roles,name,' . $request->id,
            'slug' => 'unique:roles,slug,' . $request->id,
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'success' => false,
                'message' => 'There are incorect values in the form!',
                'errors' => $validator->getMessageBag()->toArray()
            ), 200);
        } else {
            return Role::updateOrCreate(
                ['id' => $request->id],
                ['name' => $request->name, 'slug' => $request->slug]
            );
        }
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
            ['id' => $request->id],
            ['name' => $request->name, 'status' => $request->status]
        );
    }
}
