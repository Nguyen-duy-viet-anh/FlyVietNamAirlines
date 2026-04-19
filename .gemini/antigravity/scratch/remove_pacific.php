<?php
use App\Models\Airline;
use App\Models\Flight;
use App\Models\AppBooking;

require __DIR__ . '/../../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$airline = Airline::where('code', 'BL')->first();
if ($airline) {
    echo "Found Pacific Airlines (ID: {$airline->id})\n";
    $flightIds = Flight::where('airline_id', $airline->id)->pluck('id');
    echo "Found " . count($flightIds) . " flights. Deleting associated bookings...\n";
    
    // Delete bookings that refer to these flights
    $deletedBookings = AppBooking::whereIn('outbound_flight_id', $flightIds)
        ->orWhereIn('return_flight_id', $flightIds)
        ->delete();
    
    echo "Deleted $deletedBookings bookings. Deleting airline (cascading to flights)...\n";
    $airline->delete();
    echo "Pacific Airlines removed successfully.\n";
} else {
    echo "Pacific Airlines not found.\n";
}
