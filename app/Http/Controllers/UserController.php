<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Outlet;
use App\Models\LogActivity;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $users = User::join('outlets', 'outlets.id', 'users.outlet_id')
            ->when($search, function ($query, $search) {
                return $query->where('users.nama', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            })
            ->select(
                'users.id as id',
                'users.nama as nama',
                'username',
                'role',
                'outlets.nama as outlet'
            )
            ->paginate();

        if ($search) {
            $users->appends(['search' => $search]);
        }
        return view('user.index', [
            'users' => $users,
        ]);
    }
    public function create()
    {
        LogActivity::Add('Berhasil Membuat User');
        $outlets = Outlet::select('id as value', 'nama as option')->get();
        return view('user.create', [
            'outlets' => $outlets
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:100',
            'username' => 'required|max:100|unique:users',
            'role' => 'required|in:admin,kasir,owner',
            'outlet_id' => 'required|exists:outlets,id',
            'password' => 'required|max:100|confirmed'
        ], [], [
            'outlet_id' => 'Outlet'
        ]);

        $request->merge([
            'password' => bcrypt($request->password)
        ]);

        User::create($request->all());
        LogActivity::Add('Berhasil Menambah User');

        return redirect()->route('user.index')
            ->with('message', 'success store');
    }
    public function show(User $user)
    {
        return abort(404);
    }
    public function edit(User $user)
    {
        LogActivity::Add('Berhasil Mengedit User');
        $outlets = Outlet::select('id as value', 'nama as option')->get();
        return view('user.edit', [
            'user' => $user,
            'outlets' => $outlets
        ]);
    }
    public function update(Request $request, User $user)
    {
        $request->validate([
            'nama' => 'required|max:100',
            'username' => 'required|max:100|unique:users,username,'.$user->id,
            'role' => 'required|in:admin,kasir,owner',
            'outlet_id' => 'required|exists:outlets,id',
            'password' => 'nullable|max:100|confirmed'
        ], [], [
            'outlet_id' => 'Outlet'
        ]);

        if($request->password){
            $request->merge([
                'password' => bcrypt($request->password)
            ]);

            $user->update($request->all());
        } else {
            $user->update($request->except('password'));
        }

        LogActivity::Add('Berhasil Mengupdate User');
        return redirect()->route('user.index')
            ->with('message', 'success update');
    }
    public function destroy(User $user)
    {
        LogActivity::Add('Berhasil Menghapus User');
        $user->delete();
        return back()->with('message', 'success delete');
    }
}
