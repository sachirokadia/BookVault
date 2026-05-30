<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use HasFactory, Notifiable;
    protected $fillable = ['name','email','password','role','phone','address','member_id','is_active'];
    protected $hidden   = ['password','remember_token'];
    protected $casts    = ['email_verified_at'=>'datetime','password'=>'hashed','is_active'=>'boolean'];

    public function issues()       { return $this->hasMany(Issue::class); }
    public function activeIssues() { return $this->hasMany(Issue::class)->whereIn('status',['issued','overdue']); }
    public function isAdmin():     bool { return $this->role === 'admin'; }
    public function isLibrarian(): bool { return in_array($this->role,['admin','librarian']); }
}
