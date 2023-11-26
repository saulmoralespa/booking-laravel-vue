<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Hotel;
use App\Models\Client;
use App\Models\Booking;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $hotel = Hotel::create([
            'name' => 'Casa Blanca',
            'address' => 'Calle 5 # 2-50',
            'country' => 'Colombia',
            'state' => 'Norte de Santander',
            'city' => 'Cúcuta',
            'phone' => '5732452',
            'nit' => '1094163892-6'
        ]);

        $client = Client::create([
            'name' => 'Andres Perez',
            'document_number' => '1094163893'
        ]);

        $booking = Booking::create([
            'arrival_date' => '2023-11-24',
            'number_nights' => 3,
            'total_amount' => 200000,
            'client_id' => $client->id,
            'hotel_id' => $hotel->id
        ]);

    }
}
