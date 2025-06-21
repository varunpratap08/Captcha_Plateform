<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AgentController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.admin');
    }

    public function index()
    {
        $agents = Agent::paginate(10);
        return view('admin.agents.index', compact('agents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'phone_number' => 'required|string',
            'referral_code' => 'required|string|unique:agents',
        ]);

        Agent::create($request->only(['name', 'phone_number', 'referral_code']));
        return redirect()->route('agents.index')->with('success', 'Agent created.');
    }

    public function update(Request $request, Agent $agent)
    {
        $request->validate([
            'name' => 'required|string',
            'phone_number' => 'required|string',
            'referral_code' => 'required|string|unique:agents,referral_code,' . $agent->id,
        ]);

        $agent->update($request->only(['name', 'phone_number', 'referral_code']));
        return redirect()->route('agents.index')->with('success', 'Agent updated.');
    }

    public function destroy(Agent $agent)
    {
        $agent->delete();
        return redirect()->route('agents.index')->with('success', 'Agent deleted.');
    }
}