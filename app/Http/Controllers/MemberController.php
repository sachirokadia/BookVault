<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name',      'like', "%$search%")
                  ->orWhere('email',     'like', "%$search%")
                  ->orWhere('member_id', 'like', "%$search%");
            });
        }

        if ($role = $request->get('role')) {
            $query->where('role', $role);
        }

        $users = $query->withCount('activeIssues')->orderBy('name')->paginate(15)->withQueryString();

        return view('members.index', compact('users'));
    }

    public function create()
    {
        return view('members.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:8|confirmed',
            'role'      => 'required|in:admin,librarian,member',
            'phone'     => 'nullable|string|max:20',
            'address'   => 'nullable|string|max:500',
            'member_id' => 'nullable|string|unique:users,member_id',
        ]);

        $data['password']  = Hash::make($data['password']);
        $data['member_id'] = $data['member_id'] ?? 'BV-' . strtoupper(substr(uniqid(), -6));
        $data['is_active'] = true;

        User::create($data);

        return redirect()->route('members.index')->with('success', 'Member registered successfully!');
    }

    public function show(User $member)
    {
        $member->load(['issues.book']);
        return view('members.show', ['user' => $member]);
    }

    public function edit(User $member)
    {
        return view('members.edit', ['user' => $member]);
    }

    public function update(Request $request, User $member)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $member->id,
            'role'      => 'required|in:admin,librarian,member',
            'phone'     => 'nullable|string|max:20',
            'address'   => 'nullable|string|max:500',
            'member_id' => 'nullable|string|unique:users,member_id,' . $member->id,
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $data['is_active'] = $request->boolean('is_active');
        $member->update($data);

        return redirect()->route('members.index')->with('success', 'Member record updated.');
    }

    public function destroy(User $member)
    {
        if ($member->activeIssues()->count() > 0) {
            return back()->with('error', 'Cannot remove a member with active loans.');
        }
        $member->delete();
        return redirect()->route('members.index')->with('success', 'Member removed from the registry.');
    }

    public function toggleStatus(User $member)
    {
        $member->update(['is_active' => !$member->is_active]);
        $status = $member->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Member account {$status}.");
    }
}
