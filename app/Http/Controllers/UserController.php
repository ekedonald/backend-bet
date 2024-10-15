<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Models\Pool;
use App\Models\Ticker;
use App\Models\Token;
use App\Models\Transaction;
use App\Models\User;
use App\Services\AppConfig;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function dashboard(){
        $totalTransaction = Transaction::count();
        $totalRole = Role::count();
        $totalPermission = Permission::count();
        $totalUser = User::count();
        $totalTransactionAmount = Transaction::sum('amount');
        $totalPendingTransaction = Transaction::
            where('type', AppConfig::TRANSACTION_TYPE_DEBIT)
            ->where('status', AppConfig::TRANSACTION_STATUS_WAITING)->count();
        $totalTicker = Ticker::count();
        $totalToken = Token::count();
        $totalPool = Pool::count();
        return view('dashboard.index', compact('totalUser', 'totalPermission','totalRole','totalTransactionAmount', 'totalTransaction', 'totalPendingTransaction', 'totalTicker', 'totalToken', 'totalPool'));
    }
    public function store(CreateUserRequest $request){
        $role = Role::where('id', $request->role_id)->first();

        if(!$role){
            return redirect()->back()->with('error', 'Role does not exist');
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        $user->assignRole($role->name);

        return redirect()->route('user.index')->with('success', 'User added successfully');
    }

    public function index()
    {
        $users = User::paginate(20);
        return view("users.index", ['users' => $users]);
    }

    public function create()
    {
        $roles = Role::all();
        return view("users.create", ['roles' => $roles]);
    }

    public function edit(string $id)
    {
        $user = User::where('id', $id)->first();
        $roles = Role::all();
        if(!$user){
            return redirect()->back()->with('error', 'User does not exist');
        }
        return view('users.edit', ['user' => $user, 'roles' => $roles]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:users,id,'.$id,
            'password' => 'required|string',
            'name' => 'required|string',
            'role_id' => 'required|integer'
        ]);

        $user = User::where('id', $id)->first();
        if(!$user){
            return redirect()->back()->with('error', 'User does not exist');
        }

        $role = Role::where('id', $request->role_id)->first();
        if(!$role){
            return redirect()->back()->with('error', 'Role does not exist');
        }
    
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        $user->assignRole($role->name);

        $user->save();
        return redirect()->route('user.index')->with('success', 'User has been updated');
    }

    public function destroy($id)
    {
        $user = User::where('id', $id)->first();
        if(!$user){
            return redirect()->route('user.index')->with('error', 'User does not exist');
        }

        $user->delete();

        return redirect()->route('user.index')->with('success', 'User has been removed');
    }
}
