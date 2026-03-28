<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\User;
use App\Rules\PasswordRule;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;
use App\Mail\SuspensionNotification;

class SystemUserController extends Controller
{
    public function __construct(){
        // $this->middleware('auth');
        // $this->middleware('can:user_create')->only(['create', 'store']);
    }

    public function index(Request $request){
        if($request->ajax()){
            $users = User::orderBy('id', 'desc')->get();
            return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('status', function ($data) {
                $backgroundColor  = $data->status ? '#4CAF50' : '#ccc';
                $sliderTranslateX = $data->status ? '26px' : '2px';
                return getStatusHTML($data, $backgroundColor, $sliderTranslateX);
            })
            ->addColumn('action', function ($data) {
                $verifyBtn = '<button onclick="toggleVerify(' . $data->id . ')" type="button" class="btn btn-soft-success btn-sm action-btn" title="' . ($data->is_verified ? 'Unverify User' : 'Verify User') . '">
                                <i class="' . ($data->is_verified ? 'ri-checkbox-circle-fill' : 'ri-checkbox-circle-line') . '"></i>
                            </button>';
                            
                $editBtn = '<button onclick="edit(' . $data->id . ')" type="button" class="btn btn-soft-primary btn-sm action-btn">
                                <i class="ri-pencil-fill"></i>
                            </button>';
                $deleteBtn = '';
                
                if ($data->id != Auth::id()) {
                    $deleteBtn = '<button type="button" onclick="showDeleteConfirm(' . $data->id . ')" class="btn btn-soft-danger btn-sm action-btn">
                                    <i class="ri-delete-bin-fill"></i>
                                </button>';
                }

                return '<div class="d-flex gap-2 justify-content-center">' . $verifyBtn . $editBtn . $deleteBtn . '</div>';
            })
            ->rawColumns([ 'status', 'action'])
            ->make(true);
        }
        return view('backend.layout.users.system_users.index');
    }
    public function create(){
        return response()->json([
            'success' => true
        ]);
    }
    public function store(UserRequest $request){
        $data = $request->validated();
        
        $user = new User;
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->is_admin_user = $data['is_admin_user'];
        $user->password = bcrypt($data['password']);
        $user->role = User::roles()['ADMIN'];
        $user->save();
        
        return response()->json([
            'success' => true,
            'message' => 'System User Successfully created'
        ]);
    }

    public function edit(User $system_user){
        return response()->json([
            'success' => true,
            'user' => $system_user
        ]);
    }

    
    public function update(Request $request, User $system_user){
        $request->validate([
            'name' => 'required|string|max:255',
            'email'=> 'required|email|unique:users,email,'.$system_user->id,
            'password' => ['nullable', new PasswordRule],
        ]);

        try {
            $system_user->name = $request->name;
            $system_user->email = $request->email;
            
            if ($request->filled('password')) {
                $system_user->password = bcrypt($request->password);
            }
            
            $system_user->update();

            return response()->json([
                'success' => true,
                'message' => 'System User Successfully updated'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'System User Failed to Update: '.$e->getMessage()
            ], 500);
        }
    }

    public function status(Request $request, $id){
        try {
            if ($id == Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot suspend your own account.'
                ], 403);
            }

            $system_user = User::find($id);
            $system_user->status = !$system_user->status;
            
            if ($system_user->status == 0) {
                $system_user->suspension_reason = $request->reason ?? 'No reason provided';
                $system_user->suspended_at = now();
                
                // Send suspension email
                Mail::to($system_user->email)->send(new SuspensionNotification($system_user, $system_user->suspension_reason));
            } else {
                $system_user->suspension_reason = null;
                $system_user->suspended_at = null;
            }
            
            $system_user->update();

            $statusText = $system_user->status ? 'Activated' : 'Suspended';
            return response()->json([
                'success' => true,
                'message' => 'User ' . $statusText . ' Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Status Change Failed: '. $e->getMessage() 
            ], 500);
        }
    }

    public function toggleVerify($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->is_verified = !$user->is_verified;
            $user->save();

            $statusText = $user->is_verified ? 'Verified' : 'Unverified';
            return response()->json([
                'success' => true,
                'message' => 'User ' . $statusText . ' Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Verification Failed: ' . $e->getMessage()
            ], 500);
        }
    }
    public function destroy(User $system_user){
        try {
            if($system_user->id == Auth::user()->id){
                return response()->json([
                    'success' => false, 
                    'message' => 'You cannot delete your own account.'
                ], 403);
            }
            $system_user->delete();
            return response()->json([
                'success' => true,
                'message' => 'User deleted Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'User delete Failed: '. $e->getMessage() 
            ], 500);
        }
    }
}
