<?php

namespace App\Http\Livewire\V1\Admin\User\ResetPassword;

use App\Http\Services\V1\Admin\User\ResetPassword\ResetPasswordResetService;
use Livewire\Component;
use function view;

class ResetPasswordReset extends Component
{
    public $otp;
    public $user;
    public $has_error;
    public $password;
    public $password_reply;
    private $resetResetPasswordService;

    public function __construct($id = null)
    {
        parent::__construct($id);

        $this->resetResetPasswordService = ResetPasswordResetService::getInstance();
    }

    public function mount($otp)
    {
        $this->resetResetPasswordService->mount($this, $otp);
    }

    public function submitForm()
    {
        $this->resetResetPasswordService->submitForm($this);
    }

    public function updatedPassword()
    {
        $this->resetResetPasswordService->validatePassword($this);
    }

    public function updatedPasswordReply()
    {
        $this->resetResetPasswordService->validatePassword($this);
    }

    public function render()
    {
        return view('livewire.v1.admin.user.reset-password.user-reset-password-reset')
            ->extends('layouts.v1.app');
    }
}
