<?php

namespace App\Http\Livewire\V1\Admin\User\ResetPassword;

use App\Http\Services\V1\Admin\User\ResetPassword\ResetPasswordService;
use Livewire\Component;
use function view;

class ResetPassword extends Component
{
    public $email;
    private $resetPasswordService;

    public function __construct($id = null)
    {
        parent::__construct($id);

        $this->resetPasswordService = ResetPasswordService::getInstance();
    }

    public function resetPassword()
    {
        $this->resetPasswordService->resetPassword($this);
    }

    public function render()
    {
        return view('livewire.v1.admin.user.reset-password.user-reset-password')
            ->extends('layouts.v1.app');
    }
}
