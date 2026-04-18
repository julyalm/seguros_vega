<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AgentController extends Controller
{
    /**
     * Display a listing of agents.
     */
    public function index()
    {
        $agents = User::where('role', 'agente')->get();
        return view('admin.agents.index', compact('agents'));
    }

    /**
     * Store a newly created agent.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'agente',
        ]);

        return redirect()->route('admin.agents.index')
            ->with('success', 'Agente creado correctamente.');
    }

    /**
     * Remove the specified agent.
     */
    public function destroy(User $agent)
    {
        if ($agent->role === 'admin') {
            return abort(403, 'No se puede eliminar a un administrador.');
        }

        $agent->delete();

        return redirect()->route('admin.agents.index')
            ->with('success', 'Agente eliminado correctamente.');
    }
}
