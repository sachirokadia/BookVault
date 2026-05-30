<?php
namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('category');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title',  'like', "%$search%")
                  ->orWhere('author','like', "%$search%")
                  ->orWhere('isbn',  'like', "%$search%");
            });
        }

        if ($cat = $request->get('category')) {
            $query->where('category_id', $cat);
        }

        if ($avail = $request->get('availability')) {
            $avail === 'available'
                ? $query->where('available_copies', '>', 0)
                : $query->where('available_copies', 0);
        }

        $books      = $query->orderBy('title')->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('books.index', compact('books', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'author'         => 'required|string|max:255',
            'isbn'           => 'required|string|unique:books,isbn',
            'category_id'    => 'required|exists:categories,id',
            'total_copies'   => 'required|integer|min:1',
            'publisher'      => 'nullable|string|max:255',
            'published_year' => 'nullable|integer|min:1|max:' . date('Y'),
            'description'    => 'nullable|string',
        ]);

        $data['available_copies'] = $data['total_copies'];
        Book::create($data);

        return redirect()->route('books.index')->with('success', 'Book added to the catalogue successfully!');
    }

    public function show(Book $book)
    {
        $book->load(['category', 'issues.user']);
        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $categories = Category::orderBy('name')->get();
        return view('books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'author'         => 'required|string|max:255',
            'isbn'           => 'required|string|unique:books,isbn,' . $book->id,
            'category_id'    => 'required|exists:categories,id',
            'total_copies'   => 'required|integer|min:1',
            'publisher'      => 'nullable|string|max:255',
            'published_year' => 'nullable|integer|min:1|max:' . date('Y'),
            'description'    => 'nullable|string',
        ]);

        $issued               = $book->total_copies - $book->available_copies;
        $data['available_copies'] = max(0, $data['total_copies'] - $issued);
        $book->update($data);

        return redirect()->route('books.index')->with('success', 'Book record updated.');
    }

    public function destroy(Book $book)
    {
        if ($book->activeIssues()->count() > 0) {
            return back()->with('error', 'Cannot remove a book that has active loans.');
        }
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Book removed from the catalogue.');
    }
}
