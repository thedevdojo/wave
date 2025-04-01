<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class WorkspaceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the workspaces.
     */
    public function index()
    {
        $ownedWorkspaces = auth()->user()->ownedWorkspaces;
        $memberWorkspaces = auth()->user()->memberWorkspaces()
                                        ->wherePivot('role', '!=', 'owner')
                                        ->get();
        
        return view('dashboard.workspaces.index', compact('ownedWorkspaces', 'memberWorkspaces'));
    }
    
    /**
     * Show the form for creating a new workspace.
     */
    public function create()
    {
        // Check if user has agency plan
        if (!auth()->user()->hasAgencyPlan()) {
            return redirect()->route('settings.subscription')
                ->with('error', 'You need to upgrade to the Agency Plan to create workspaces.');
        }
        
        return view('dashboard.workspaces.create');
    }
    
    /**
     * Store a newly created workspace in storage.
     */
    public function store(Request $request)
    {
        // This is handled by the Livewire component in the create view
        return redirect()->route('workspaces.index');
    }
    
    /**
     * Display the specified workspace.
     */
    public function show(Workspace $workspace)
    {
        // Check if user is a member of this workspace
        $isMember = $workspace->members()->where('user_id', auth()->id())->exists();
        $isOwner = $workspace->user_id === auth()->id();
        
        if (!$isMember && !$isOwner) {
            return redirect()->route('workspaces.index')
                ->with('error', 'You do not have access to this workspace.');
        }
        
        return view('dashboard.workspaces.show', compact('workspace'));
    }
    
    /**
     * Show the form for editing the specified workspace.
     */
    public function edit(Workspace $workspace)
    {
        // Check if user is an admin or owner of this workspace
        $member = $workspace->members()->where('user_id', auth()->id())->first();
        $isAdmin = $member && $member->pivot->role === 'admin';
        $isOwner = $workspace->user_id === auth()->id();
        
        if (!$isAdmin && !$isOwner) {
            return redirect()->route('workspaces.show', $workspace)
                ->with('error', 'You do not have permission to edit this workspace.');
        }
        
        return view('dashboard.workspaces.edit', compact('workspace'));
    }
    
    /**
     * Update the specified workspace in storage.
     */
    public function update(Request $request, Workspace $workspace)
    {
        // This is handled by the Livewire component in the edit view
        return redirect()->route('workspaces.show', $workspace);
    }
    
    /**
     * Show the form for deleting the specified workspace.
     */
    public function showDeleteForm(Workspace $workspace)
    {
        // Check if user is the owner of this workspace
        if ($workspace->user_id !== auth()->id()) {
            return redirect()->route('workspaces.show', $workspace)
                ->with('error', 'Only the workspace owner can delete this workspace.');
        }
        
        return view('dashboard.workspaces.delete', compact('workspace'));
    }
    
    /**
     * Remove the specified workspace from storage.
     */
    public function destroy(Workspace $workspace)
    {
        // Check if user is the owner of this workspace
        if ($workspace->user_id !== auth()->id()) {
            return redirect()->route('workspaces.show', $workspace)
                ->with('error', 'Only the workspace owner can delete this workspace.');
        }
        
        // Delete workspace logo if exists
        if ($workspace->logo) {
            Storage::disk('public')->delete($workspace->logo);
        }
        
        // Delete workspace
        $workspace->delete();
        
        // Clear current workspace session if it was the deleted one
        if (session('current_workspace_id') == $workspace->id) {
            session()->forget('current_workspace_id');
        }
        
        return redirect()->route('workspaces.index')
            ->with('success', 'Workspace deleted successfully.');
    }
    
    /**
     * Switch to the specified workspace.
     */
    public function switchWorkspace($workspaceId)
    {
        // If 'personal' is passed, switch to personal mode (clear workspace context)
        if ($workspaceId === 'personal') {
            logger()->error('SWITCHING TO PERSONAL WORKSPACE', [
                'from_workspace_id' => session('current_workspace_id')
            ]);
            
            session()->forget('current_workspace_id');
            return redirect()->back()->with('success', 'Switched to Default Workspace.');
        }
        
        logger()->error('SWITCHING WORKSPACE - BEFORE FIND', [
            'workspaceId' => $workspaceId,
            'workspaceId_type' => gettype($workspaceId)
        ]);
        
        $workspace = Workspace::findOrFail($workspaceId);
        
        logger()->error('SWITCHING WORKSPACE - AFTER FIND', [
            'workspace_id' => $workspace->id,
            'workspace_id_type' => gettype($workspace->id),
            'workspace_name' => $workspace->name
        ]);
        
        // Check if user is a member of this workspace
        $isMember = $workspace->members()->where('user_id', auth()->id())->exists();
        $isOwner = $workspace->user_id === auth()->id();
        
        if (!$isMember && !$isOwner) {
            return redirect()->route('workspaces.index')
                ->with('error', 'You do not have access to this workspace.');
        }
        
        // Force integer type for workspace ID
        $workspaceIdInt = (int) $workspace->id;
        
        // Set current workspace in session
        session(['current_workspace_id' => $workspaceIdInt]);
        
        logger()->error('WORKSPACE ID SET IN SESSION', [
            'workspace_id' => $workspaceIdInt,
            'workspace_id_type' => gettype($workspaceIdInt),
            'session_value' => session('current_workspace_id'),
            'session_value_type' => gettype(session('current_workspace_id')),
        ]);
        
        return redirect()->back()
            ->with('success', 'Switched to workspace: ' . $workspace->name);
    }
}
