<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $bookings = Booking::join('clients', 'bookings.client_id', '=', 'clients.id')
            ->orderBy('bookings.id', 'desc')
            ->select('bookings.*', 'name', 'document_number', 'is_main_guest')
            ->get();
        return Response()->json([
            'data' => $bookings,
            'draw' => $request->get('draw'),
            'recordsTotal' => count($bookings),
            'recordsFiltered' => count($bookings),
        ]);
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
    public function show(string $id)
    {
        $booking = Booking::join('clients', 'bookings.client_id', '=', 'clients.id')
            ->where('bookings.id', $id)
            ->orderBy('bookings.id', 'desc')
            ->select('bookings.*', 'name', 'document_number', 'is_main_guest')
            ->first();

        return response()->json($booking);
    }


    public function edit()
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        if ($request->get('action') === 'cancel'){
            $booking->update([
                'status' => 'Cancelada'
            ]);
            $booking->save();
        }

        if ($request->get('action') === 'provisional'){
            $booking->update([
                'status' => 'Provisional'
            ]);
            $booking->save();
        }

        if ($request->get('action') === 'confirm'){
            $booking->update([
                'status' => 'Confirmada'
            ]);
            $booking->save();
        }

        return response()->json($booking);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        //
    }
}
