<?php

namespace App\Http\Livewire\V1\Admin;

use App\Http\Services\WikiIndexService;
use Livewire\Component;
use function view;

class WikiIndexComponent extends Component
{

    public $wikies;
    public $title;
    public $parent;
    public $content;
    private $wikiIndexService;

    public function __construct($id = null)
    {
        $this->wikiIndexService = WikiIndexService::getInstance();
        parent::__construct($id);
    }


    public function mount()
    {
        $this->wikiIndexService->mount($this);
    }

    public function showInput($id)
    {
        $this->wikiIndexService->showInput($this, $id);
    }


    public function render()
    {
        return view(
            'livewire.v1.admin.wiki.wiki-index'
        )->extends('layouts.v1.app-wiki');
    }
}
