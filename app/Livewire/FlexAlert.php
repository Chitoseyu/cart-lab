<?php

namespace App\Livewire;

use Livewire\Component;

class FlexAlert extends Component
{
    public $message;
    public $visible = false;

    protected $listeners = ['showFlexAlert' => 'showMessage'];


    public function showMessage($message)
    {
        $this->message = $message;
        $this->visible = true;

        // 3 秒後自動隱藏
        $this->dispatch('hideFlexAlert',$message);
    }


    public function render()
    {
        return view('livewire.flex-alert');
    }
}
