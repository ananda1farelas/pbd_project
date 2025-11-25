<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBarang = Barang::count();
        return view('dash.index', compact('totalBarang'));
    }
}
