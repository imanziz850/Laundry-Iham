<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use App\Models\LogActivity;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $outlets = Outlet::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%")
                ->orWhere('tlp', 'like', "%{$search}%");
        })
            ->paginate();
        if ($search) {
            $outlets->appends(['search' => $search]);
        }
        return view('outlet.index', [
            'outlets' => $outlets
        ]);
    }
    public function create()
    {
        LogActivity::Add('berhasil membuat outlet');
        return view('outlet.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:100',
            'tlp' => 'required|max:20',
            'alamat' => 'required|max:250',
        ], [], [
            'tlp' => 'Telepon'
        ]);

        Outlet::create($request->all());
        LogActivity::Add('berhasil menambah outlet');

        return redirect()->route('outlet.index')
            ->with('message', 'success store');
    }
    public function show(Outlet $outlet)
    {
        return abort(404);
    }
    public function edit(Outlet $outlet)
    {
        LogActivity::Add('berhasil mengubah outlet');
        return view('outlet.edit', [
            'outlet' => $outlet
        ]);
    }
    public function update(Request $request, Outlet $outlet)
    {
        $request->validate([
            'nama' => 'required|max:100',
            'tlp' => 'required|max:20',
            'alamat' => 'required|max:250',
        ], [], [
            'tlp' => 'Telepon'
        ]);

        $outlet->update($request->all());

        return redirect()->route('outlet.index')
        ->with('message','success update');
    }
    public function destroy(Outlet $outlet)
    {
        LogActivity::Add('berhasil menghapus outlet');
        $outlet->delete();
        return back()->with('message', 'success delete');
    }
}
