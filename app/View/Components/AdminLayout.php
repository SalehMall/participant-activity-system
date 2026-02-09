<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AdminLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        // Kode ini memberi tahu Laravel:
        // "Kalau ada yang panggil <x-admin-layout>,
        // tolong tampilkan file resources/views/layouts/admin.blade.php"
        return view('layouts.admin');
    }
}