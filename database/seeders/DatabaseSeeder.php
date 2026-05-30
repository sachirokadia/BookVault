<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Book;
use App\Models\Issue;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Users ──
        User::create([
            'name'      => 'Arjun Verma',
            'email'     => 'admin@bookvault.com',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
            'member_id' => 'BV-ADMIN',
            'phone'     => '+91-9900000001',
            'is_active' => true,
        ]);

        User::create([
            'name'      => 'Meena Sharma',
            'email'     => 'librarian@bookvault.com',
            'password'  => Hash::make('password'),
            'role'      => 'librarian',
            'member_id' => 'BV-LIB01',
            'phone'     => '+91-9900000002',
            'is_active' => true,
        ]);

        $members = [
            ['name' => 'Kabir Nair',    'email' => 'member@bookvault.com',  'member_id' => 'BV-001'],
            ['name' => 'Diya Pillai',   'email' => 'diya@student.edu',      'member_id' => 'BV-002'],
            ['name' => 'Farhan Qureshi','email' => 'farhan@student.edu',    'member_id' => 'BV-003'],
            ['name' => 'Sneha Iyer',    'email' => 'sneha@student.edu',     'member_id' => 'BV-004'],
            ['name' => 'Rahul Gupta',   'email' => 'rahul@student.edu',     'member_id' => 'BV-005'],
            ['name' => 'Pooja Desai',   'email' => 'pooja@student.edu',     'member_id' => 'BV-006'],
        ];

        foreach ($members as $m) {
            User::create(array_merge($m, [
                'password'  => Hash::make('password'),
                'role'      => 'member',
                'phone'     => '+91-98' . rand(10000000, 99999999),
                'is_active' => true,
            ]));
        }

        // ── Categories ──
        $categories = [
            ['name' => 'Science Fiction',   'description' => 'Futuristic and speculative fiction'],
            ['name' => 'Classic Literature', 'description' => 'Timeless works of literary fiction'],
            ['name' => 'Data Science',       'description' => 'Machine learning, AI and analytics'],
            ['name' => 'Philosophy',         'description' => 'Ethics, metaphysics and logic'],
            ['name' => 'Biography',          'description' => 'Life stories of notable figures'],
            ['name' => 'Web Development',    'description' => 'Frontend, backend and full stack'],
            ['name' => 'Psychology',         'description' => 'Human behaviour and cognition'],
            ['name' => 'Economics',          'description' => 'Micro and macro economics'],
        ];

        foreach ($categories as $c) {
            Category::create($c);
        }

        // ── Books (different from Project 1) ──
        $books = [
            // Sci-Fi
            ['title' => 'Dune',                        'author' => 'Frank Herbert',      'isbn' => '978-0441013593', 'category_id' => 1, 'copies' => 3, 'year' => 1965, 'publisher' => 'Ace Books'],
            ['title' => 'Foundation',                   'author' => 'Isaac Asimov',       'isbn' => '978-0553293357', 'category_id' => 1, 'copies' => 2, 'year' => 1951, 'publisher' => 'Bantam Books'],
            ['title' => 'Neuromancer',                  'author' => 'William Gibson',     'isbn' => '978-0441569595', 'category_id' => 1, 'copies' => 2, 'year' => 1984, 'publisher' => 'Ace Books'],

            // Classic Literature
            ['title' => 'To Kill a Mockingbird',       'author' => 'Harper Lee',         'isbn' => '978-0061935466', 'category_id' => 2, 'copies' => 4, 'year' => 1960, 'publisher' => 'HarperCollins'],
            ['title' => 'One Hundred Years of Solitude','author' => 'Gabriel García Márquez','isbn' => '978-0060883287', 'category_id' => 2, 'copies' => 2, 'year' => 1967, 'publisher' => 'Harper'],
            ['title' => 'Crime and Punishment',        'author' => 'Fyodor Dostoevsky',  'isbn' => '978-0143107637', 'category_id' => 2, 'copies' => 3, 'year' => 1866, 'publisher' => 'Penguin Classics'],

            // Data Science
            ['title' => 'Hands-On Machine Learning',   'author' => 'Aurélien Géron',     'isbn' => '978-1492032649', 'category_id' => 3, 'copies' => 3, 'year' => 2019, 'publisher' => "O'Reilly"],
            ['title' => 'Python for Data Analysis',    'author' => 'Wes McKinney',        'isbn' => '978-1491957660', 'category_id' => 3, 'copies' => 2, 'year' => 2017, 'publisher' => "O'Reilly"],

            // Philosophy
            ['title' => 'Meditations',                 'author' => 'Marcus Aurelius',    'isbn' => '978-0140449334', 'category_id' => 4, 'copies' => 4, 'year' => 180,  'publisher' => 'Penguin Classics'],
            ['title' => 'The Republic',                'author' => 'Plato',              'isbn' => '978-0140455113', 'category_id' => 4, 'copies' => 2, 'year' => -380, 'publisher' => 'Penguin Classics'],

            // Biography
            ['title' => 'Steve Jobs',                  'author' => 'Walter Isaacson',    'isbn' => '978-1451648539', 'category_id' => 5, 'copies' => 2, 'year' => 2011, 'publisher' => 'Simon & Schuster'],

            // Web Dev
            ['title' => 'JavaScript: The Good Parts', 'author' => 'Douglas Crockford',   'isbn' => '978-0596517748', 'category_id' => 6, 'copies' => 3, 'year' => 2008, 'publisher' => "O'Reilly"],
            ['title' => 'You Don\'t Know JS',          'author' => 'Kyle Simpson',        'isbn' => '978-1491904244', 'category_id' => 6, 'copies' => 2, 'year' => 2015, 'publisher' => "O'Reilly"],

            // Psychology
            ['title' => 'Thinking, Fast and Slow',     'author' => 'Daniel Kahneman',    'isbn' => '978-0374533557', 'category_id' => 7, 'copies' => 3, 'year' => 2011, 'publisher' => 'Farrar, Straus and Giroux'],

            // Economics
            ['title' => 'Freakonomics',                'author' => 'Levitt & Dubner',    'isbn' => '978-0060731328', 'category_id' => 8, 'copies' => 2, 'year' => 2005, 'publisher' => 'William Morrow'],
        ];

        foreach ($books as $b) {
            Book::create([
                'title'            => $b['title'],
                'author'           => $b['author'],
                'isbn'             => $b['isbn'],
                'category_id'      => $b['category_id'],
                'total_copies'     => $b['copies'],
                'available_copies' => $b['copies'],
                'published_year'   => $b['year'],
                'publisher'        => $b['publisher'],
            ]);
        }

        // ── Sample Issues ──

        // Active: Kabir has Dune
        $book1 = Book::where('isbn','978-0441013593')->first();
        Issue::create([
            'book_id'    => $book1->id,
            'user_id'    => 3,
            'issue_date' => Carbon::today()->subDays(3),
            'due_date'   => Carbon::today()->addDays(11),
            'status'     => 'issued',
        ]);
        $book1->decrement('available_copies');

        // Active: Diya has Meditations
        $book2 = Book::where('isbn','978-0140449334')->first();
        Issue::create([
            'book_id'    => $book2->id,
            'user_id'    => 4,
            'issue_date' => Carbon::today()->subDays(8),
            'due_date'   => Carbon::today()->addDays(6),
            'status'     => 'issued',
        ]);
        $book2->decrement('available_copies');

        // Overdue: Farhan has Hands-On ML (10 days late)
        $book3 = Book::where('isbn','978-1492032649')->first();
        Issue::create([
            'book_id'    => $book3->id,
            'user_id'    => 5,
            'issue_date' => Carbon::today()->subDays(25),
            'due_date'   => Carbon::today()->subDays(10),
            'status'     => 'overdue',
        ]);
        $book3->decrement('available_copies');

        // Overdue: Sneha has Foundation (5 days late)
        $book4 = Book::where('isbn','978-0553293357')->first();
        Issue::create([
            'book_id'    => $book4->id,
            'user_id'    => 6,
            'issue_date' => Carbon::today()->subDays(19),
            'due_date'   => Carbon::today()->subDays(5),
            'status'     => 'overdue',
        ]);
        $book4->decrement('available_copies');

        // Returned (with fine): Rahul had Crime and Punishment
        Issue::create([
            'book_id'      => Book::where('isbn','978-0143107637')->first()->id,
            'user_id'      => 7,
            'issue_date'   => Carbon::today()->subDays(22),
            'due_date'     => Carbon::today()->subDays(8),
            'return_date'  => Carbon::today()->subDays(1),
            'status'       => 'returned',
            'fine_amount'  => 14.00, // 7 days × ₹2
        ]);

        // Returned (no fine): Pooja had Thinking Fast and Slow
        Issue::create([
            'book_id'      => Book::where('isbn','978-0374533557')->first()->id,
            'user_id'      => 8,
            'issue_date'   => Carbon::today()->subDays(10),
            'due_date'     => Carbon::today()->addDays(4),
            'return_date'  => Carbon::today()->subDays(2),
            'status'       => 'returned',
            'fine_amount'  => 0.00,
        ]);
    }
}
