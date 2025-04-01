<?php

namespace App\Livewire;

use Livewire\Component;

class FlexAlert extends Component
{
    public $message;
    public $visible = false;

    protected $listeners = [
        'showFlexAlert' => 'showMessage',
    ];

    public function mount()
    {
        //
    }
    
    public function showMessage($message)
    {
        $this->message = $message;
        $this->visible = true;

        $this->dispatch('hideFlexAlert', ['message' => $message]);
    }


    public function render()
    {
        return view('livewire.flex-alert');
    }
}
