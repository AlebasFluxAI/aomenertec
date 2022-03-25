<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Technician extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'network_operator_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function networkOperator()
    {
        return $this->belongsTo(NetworkOperator::class);
    }
    public function clients()
    {
        return $this->belongsToMany(Client::class, 'client_technicians')->withPivot('active');
    }
}
