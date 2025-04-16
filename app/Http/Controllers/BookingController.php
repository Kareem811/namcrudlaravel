<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'number' => ['required', 'regex:/^(9|7)\d{7}$/'],
            'department' => 'required|string',
            'service' => 'required|string',
            'type' => 'required|in:online,offline',
            'date' => 'required|date',
            'time' => 'required',
        ]);

        Booking::create($request->all());

        return response()->json('Success');
    }
}
