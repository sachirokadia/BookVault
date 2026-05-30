<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Issue extends Model {
    use HasFactory;
    protected $fillable = ['book_id','user_id','issue_date','due_date','return_date','status','fine_amount','notes'];
    protected $casts = ['issue_date'=>'date','due_date'=>'date','return_date'=>'date'];
    const FINE_PER_DAY = 2;

    public function book() { return $this->belongsTo(Book::class); }
    public function user() { return $this->belongsTo(User::class); }

    public function isOverdue(): bool {
        return $this->status !== 'returned' && Carbon::now()->gt($this->due_date);
    }
    public function calculateFine(): float {
        if (!$this->isOverdue()) return 0;
        $check = $this->return_date ?? Carbon::now();
        return $this->due_date->diffInDays($check) * self::FINE_PER_DAY;
    }
    public function getDaysOverdueAttribute(): int {
        if (!$this->isOverdue()) return 0;
        return (int)$this->due_date->diffInDays(Carbon::now());
    }
}
