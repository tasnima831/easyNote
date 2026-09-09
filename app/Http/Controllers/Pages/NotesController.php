<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class NotesController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.notes.index');
    }
}
