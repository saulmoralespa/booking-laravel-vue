<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
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
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string',
            'document_number' => [
                'required',
                'string',
            ],
            'arrival_date' => 'required|date',
            'number_people' => 'required|integer',
            'number_nights' => 'required|integer',
            'total_amount' => 'required|numeric',
            'status' => 'required|string',
        ]);

        $client = Client::where('document_number', $request->get('document_number'))->first();

        if (!$client) {
            $client = Client::create($request->all());
            $client->save();
        }

        $booking = new Booking();
        $booking->client_id = $client->id;
        $booking->arrival_date = $request->get('arrival_date');
        $booking->number_people = $request->get('number_people');
        $booking->number_nights = $request->get('number_nights');
        $booking->total_amount = $request->get('total_amount');
        $booking->status = $request->get('status');
        $booking->client_id = $client->id;
        $booking->hotel_id = $request->get('hotel_id');
        $booking->client()->associate($client);
        $booking->save();

        return response()->json($booking);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
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
    public function update(Booking $booking, Request $request): JsonResponse
    {
        if ($request->get('action') === 'cancel') {
            $booking->update([
                'status' => 'Cancelada',
            ]);
            $booking->save();
        }

        if ($request->get('action') === 'provisional') {
            $booking->update([
                'status' => 'Provisional',
            ]);
            $booking->save();
        }

        if ($request->get('action') === 'confirm') {
            $booking->update([
                'status' => 'Confirmada',
            ]);
            $booking->save();
        }

        if (count($request->all()) > 1) {

            $booking->update(
                $request->all()
            );

            $booking->save();

            $client = $booking->client;

            if ($client) {
                $client->update([
                    'name' => $request->get('name'),
                    'document_number' => $request->get('document_number'),
                ]);
                $client->save();
            }
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
