<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminPrice extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'admin_id',
        'client_type_id',
        'value',
        ];
    public function clientType(){
        return $this->belongsTo(ClientType::class);
    }
}
