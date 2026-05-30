<?php
namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Issue;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    public function index(Request $request)
    {
        $query = Issue::with(['book', 'user']);

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('book', fn($b) => $b->where('title', 'like', "%$search%"))
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%$search%"));
            });
        }

        $issues = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('issues.index', compact('issues'));
    }

    public function create()
    {
        $books   = Book::where('available_copies', '>', 0)->orderBy('title')->get();
        $members = User::where('role', 'member')->where('is_active', true)->orderBy('name')->get();
        return view('issues.create', compact('books', 'members'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'book_id'  => 'required|exists:books,id',
            'user_id'  => 'required|exists:users,id',
            'due_date' => 'required|date|after:today',
            'notes'    => 'nullable|string',
        ]);

        $book = Book::findOrFail($data['book_id']);

        if (!$book->isAvailable()) {
            return back()->with('error', 'This book has no available copies.')->withInput();
        }

        $existing = Issue::where('book_id', $data['book_id'])
            ->where('user_id', $data['user_id'])
            ->whereIn('status', ['issued', 'overdue'])
            ->first();

        if ($existing) {
            return back()->with('error', 'This member already has a copy of this book on loan.')->withInput();
        }

        $data['issue_date'] = Carbon::today();
        $data['status']     = 'issued';

        Issue::create($data);
        $book->decrement('available_copies');

        return redirect()->route('issues.index')->with('success', 'Book issued successfully!');
    }

    public function show(Issue $issue)
    {
        $issue->load(['book.category', 'user']);
        return view('issues.show', compact('issue'));
    }

    public function returnBook(Issue $issue)
    {
        if ($issue->status === 'returned') {
            return back()->with('error', 'This book has already been returned.');
        }

        $fine = $issue->calculateFine();

        $issue->update([
            'return_date' => Carbon::today(),
            'status'      => 'returned',
            'fine_amount' => $fine,
        ]);

        $issue->book->increment('available_copies');

        $msg = $fine > 0
            ? "Book returned. Fine of ₹{$fine} has been recorded."
            : 'Book returned successfully. No fine applicable.';

        return redirect()->route('issues.index')->with('success', $msg);
    }

    public function overdue()
    {
        Issue::where('status', 'issued')
            ->where('due_date', '<', Carbon::today())
            ->update(['status' => 'overdue']);

        $overdueIssues = Issue::with(['book', 'user'])
            ->where('status', 'overdue')
            ->orderBy('due_date')
            ->paginate(15);

        return view('issues.overdue', compact('overdueIssues'));
    }
}
