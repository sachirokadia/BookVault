<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model {
    use HasFactory;
    protected $fillable = ['title','author','isbn','category_id','total_copies','available_copies','publisher','published_year','description'];
    public function category() { return $this->belongsTo(Category::class); }
    public function issues()   { return $this->hasMany(Issue::class); }
    public function activeIssues() { return $this->hasMany(Issue::class)->whereIn('status',['issued','overdue']); }
    public function isAvailable(): bool { return $this->available_copies > 0; }
}
