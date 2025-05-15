<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with('user')->get();
        return Response::json(['bookings' => $bookings]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'service' => 'required|string|in:Technical Support,Account Help,New Subscription,Other',
            'type' => 'required|in:online,offline',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'description' => 'nullable|string|max:1000',
            'user_id' => 'nullable|exists:users,id',
            'username' => 'required_without:user_id|string',
            'number' => 'required_without:user_id|string',
        ]);

        Booking::create($request->all());

        return response()->json('Success');
    }
    public function handlestatus(Request $request, Booking $id)
    {
        $status = $request->status;

        // Toggle logic
        if ($status === "accepted") {
            $id->update(['status' => "rejected"]);
        } else {
            $id->update(['status' => "accepted"]);
        }

        return response()->json(['message' => "Status changed successfully"]);
    }
}
