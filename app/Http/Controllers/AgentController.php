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
            
            // Eager load wallet and earnings fields
            $agents = Agent::select('id', 'name', 'phone_number', 'created_at', 'wallet_balance', 'total_earnings', 'total_withdrawals', 'referral_code')
                ->paginate(10);
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
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string',
            'date_of_birth' => 'nullable|date|before:today',
            'email' => 'nullable|email',
            'upi_id' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|max:2048|mimes:jpg,jpeg,png,webp',
            'status' => 'nullable|string|in:active,inactive',
        ]);

        $data = $request->only([
            'name', 'phone_number', 'date_of_birth', 'email', 'upi_id', 'status'
        ]);
        $data['referral_code'] = \App\Models\Agent::generateReferralCode();
        $data['profile_completed'] = true;

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('profile-images', 'public');
        } else if ($request->filled('profile_image_url') && filter_var($request->input('profile_image_url'), FILTER_VALIDATE_URL)) {
            $url = $request->input('profile_image_url');
            $imageContents = @file_get_contents($url);
            if ($imageContents !== false) {
                $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
                $filename = 'profile-images/' . uniqid('agent_admin_' . time() . '_') . '.' . $extension;
                \Storage::disk('public')->put($filename, $imageContents);
                $data['profile_image'] = $filename;
            }
        }

        \App\Models\Agent::create($data);
        return redirect()->route('admin.agents.index')->with('success', 'Agent created.');
    }

    public function edit(Agent $agent)
    {
        return view('admin.agents.edit', compact('agent'));
    }

    public function update(Request $request, Agent $agent)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string',
            'date_of_birth' => 'nullable|date|before:today',
            'email' => 'nullable|email',
            'upi_id' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'pincode' => 'nullable|string',
            'aadhar_number' => 'nullable|string',
            'pan_number' => 'nullable|string',
            'gst_number' => 'nullable|string',
            'bio' => 'nullable|string',
            'profile_image' => 'nullable|image|max:2048|mimes:jpg,jpeg,png,webp',
            'bank_account_number' => 'nullable|string',
            'ifsc_code' => 'nullable|string',
            'account_holder_name' => 'nullable|string',
            'status' => 'nullable|string|in:active,inactive',
        ]);

        $data = $request->only([
            'name', 'phone_number', 'date_of_birth', 'email', 'upi_id', 'address', 'city', 'state', 'pincode',
            'aadhar_number', 'pan_number', 'gst_number', 'bio', 'bank_account_number', 'ifsc_code', 'account_holder_name', 'status'
        ]);

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('profile-images', 'public');
        } else if ($request->filled('profile_image_url') && filter_var($request->input('profile_image_url'), FILTER_VALIDATE_URL)) {
            $url = $request->input('profile_image_url');
            $imageContents = @file_get_contents($url);
            if ($imageContents !== false) {
                $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
                $filename = 'profile-images/' . uniqid('agent_admin_' . time() . '_') . '.' . $extension;
                \Storage::disk('public')->put($filename, $imageContents);
                $data['profile_image'] = $filename;
            }
        }

        $agent->update($data);
        return redirect()->route('admin.agents.index')->with('success', 'Agent updated.');
    }

    public function destroy(Agent $agent)
    {
        $agent->delete();
        return redirect()->route('agents.index')->with('success', 'Agent deleted.');
    }

    public function show($id)
    {
        $agent = Agent::with(['referredUsers'])->findOrFail($id);
        return view('admin.agents.show', compact('agent'));
    }

    public function create()
    {
        return view('admin.agents.create');
    }

    /**
     * Get a list of all agents (for contact matching)
     * GET /api/v1/agents/list
     */
    public function list(Request $request)
    {
        $agents = \App\Models\Agent::select('id', 'name', 'phone_number', 'profile_image')->get();
        $agents = $agents->map(function($agent) {
            return [
                'id' => $agent->id,
                'name' => $agent->name,
                'phone_number' => $agent->phone_number,
                'profile_image_url' => $agent->profile_image ? asset('storage/' . $agent->profile_image) : null,
            ];
        });
        return response()->json(['status' => 'success', 'agents' => $agents]);
    }
}