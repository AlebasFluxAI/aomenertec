<?php

namespace App\Models\Traits;

use App\Models\V1\Image;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

trait ValidateUserFormTrait
{
    protected $rules = [
        'identification' => 'required|min:6|unique:users,identification',
        'name' => 'required|min:6',
        'phone' => 'min:7|unique:users,phone',
        'admin_id' => 'required',
        'email' => 'required|email|unique:users,email',
    ];


}
