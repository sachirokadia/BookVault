<?php
namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Issue;
use App\Models\User;
use App\Models\Category;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Auto-mark overdue
        Issue::where('status', 'issued')
            ->where('due_date', '<', Carbon::today())
            ->update(['status' => 'overdue']);

        $stats = [
            'total_books'     => Book::count(),
            'available_books' => Book::where('available_copies', '>', 0)->count(),
            'issued_books'    => Issue::whereIn('status', ['issued', 'overdue'])->count(),
            'overdue_books'   => Issue::where('status', 'overdue')->count(),
            'total_members'   => User::where('role', 'member')->count(),
            'categories'      => Category::count(),
        ];

        $recentIssues = Issue::with(['book', 'user'])
            ->latest()
            ->take(8)
            ->get();

        $overdueIssues = Issue::with(['book', 'user'])
            ->where('status', 'overdue')
            ->orderBy('due_date')
            ->take(5)
            ->get();

        $popularBooks = Book::withCount('issues')
            ->orderBy('issues_count', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.index', compact('stats', 'recentIssues', 'overdueIssues', 'popularBooks'));
    }
}
