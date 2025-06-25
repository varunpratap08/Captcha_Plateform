<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AgentController extends Controller
{
    public function __construct()
    {
        // Middleware is already applied in web.php route group
    }

    public function index()
    {
        try {
            // Log the start of the operation
            \Log::info('Fetching agents list');
            
            // Check if the agents table exists
            if (!\Schema::hasTable('agents')) {
                \Log::error('Agents table does not exist');
                abort(500, 'The agents table does not exist. Please run migrations.');
            }
            
            $agents = Agent::paginate(10);
            \Log::info('Successfully fetched ' . $agents->count() . ' agents');
            
            return view('admin.agents.index', compact('agents'));
            
        } catch (\Exception $e) {
            // Log the full error with trace
            \Log::error('Error in AgentController@index: ' . $e->getMessage() . 
                       ' in ' . $e->getFile() . ':' . $e->getLine() . 
                       '\n' . $e->getTraceAsString());
            
            // Return a more helpful error message in development
            if (config('app.debug')) {
                return response()->view('errors.500', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ], 500);
            }
            
            // Generic error message in production
            return response()->view('errors.500', [
                'message' => 'An error occurred while loading the agents page.'
            ], 500);
        }
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