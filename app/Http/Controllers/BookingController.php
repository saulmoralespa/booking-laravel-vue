<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use function Laravel\Prompts\select;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $bookings = Booking::join('clients', 'bookings.client_id', '=', 'clients.id')
        ->orderBy('bookings.id', 'desc')
        ->get();
        return Response()->json([
            'data' => $bookings,
            'draw' => $request->get('draw'),
            'recordsTotal' => count($bookings),
            'recordsFiltered' => count($bookings),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        //
    }
}
