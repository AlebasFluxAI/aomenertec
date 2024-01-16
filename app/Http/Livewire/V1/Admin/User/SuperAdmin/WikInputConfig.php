<?php

namespace App\Http\Livewire\V1\Admin\User\SuperAdmin;

use App\Http\Services\V1\Admin\User\SuperAdmin\WikiInputConfigService;
use Livewire\Component;

class WikInputConfig extends Component
{
    public $model;
    public $message;
    public $title;
    public $parent;
    public $content;
    public $wikies;
    public $wiki_id = null;

    private $wikiInputConfigService;

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->wikiInputConfigService = WikiInputConfigService::getInstance();
    }

    public function showInput($id)
    {
        $this->wikiInputConfigService->showInput($this, $id);
    }

    public function mount()
    {
        $this->wikiInputConfigService->mount($this);
    }

    public function submitForm()
    {
        $this->wikiInputConfigService->submitForm($this);
    }

    public function render()
    {
        return view('livewire.v1.admin.user.super.wiki-input-config')
            ->extends('layouts.v1.app');
    }
}
