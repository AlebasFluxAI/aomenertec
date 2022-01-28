<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pqr extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function pqrState()
    {
        return $this->belongsTo(PqrState::class);
    }
    public function pqrType()
    {
        return $this->belongsTo(PqrType::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function networkOperator()
    {
        return $this->belongsTo(NetworkOperator::class);
    }
    public function support()
    {
        return $this->belongsTo(Support::class);
    }
    public function pqrPosts()
    {
        return $this->hasMany(PqrPost::class);
    }
}
