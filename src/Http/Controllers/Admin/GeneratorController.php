<?php

namespace Modules\Core\Http\Controllers\Admin;

use Modules\Core\Http\Controllers\Controller;

class GeneratorController extends Controller
{
    public function index()
    {
        return view('core::admin.generator');
    }
}
