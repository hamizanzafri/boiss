<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Spatie\Geocoder\Geocoder;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\Image\Png;
use App\Mail\TicketMail;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:event_manager|superadmin');
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->user_type === 'admin') {
            // Fetch all events for admin
            $events = Event::all();
            return view('event.table', compact('events'));
        } else {
            // Fetch only upcoming events for general users
            $events = Event::where('date', '>=', Carbon::today())->get();
            return view('event.list', compact('events'));
        }
    }

    public function create()
    {
        return view('event.create');
    }

    public function store(Request $request)
    {
        // Validate the request data including new fields
        $validatedData = $request->validate([
            'event' => 'required',
            'venue' => 'required',
            'date' => 'required|date',
            'photo' => 'nullable|image',  // more explicit image validation
            'ticket_price' => 'required|numeric',
            'ticket_stock' => 'required|integer'
        ]);

        // Handle the file upload for photo if needed
        if ($request->hasFile('photo')) {
            if ($request->file('photo')->isValid()) {
                $photoPath = $request->file('photo')->store('photos', 'public');
                $validatedData['photo'] = $photoPath;
            } else {
                return back()->withErrors('The photo is not valid.');
            }
        }

        // Geocode the venue to get latitude and longitude
        $client = new \GuzzleHttp\Client();
        $geocoder = new Geocoder($client);
        $apiKey = config('geocoder.key');
        if (!$apiKey) {
            return back()->withErrors('Geocoding API key is missing.');
        }

        $geocoder->setApiKey($apiKey);
        $geocoder->setCountry('MY'); // Optional: set the country if necessary

        $coordinates = $geocoder->getCoordinatesForAddress($request->venue);
        $validatedData['latitude'] = $coordinates['lat'] ?? null;
        $validatedData['longitude'] = $coordinates['lng'] ?? null;

        // Create the new event with validated data
        $event = Event::create($validatedData);
        return redirect()->route('event.index')->with('success', 'Event created successfully.');
    }

    public function show(Event $event)
    {
        return view('event.show', compact('event'));
    }

    public function edit(Event $event)
    {
        return view('event.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        // Validate the request data including new fields
        $validatedData = $request->validate([
            'event' => 'required',
            'venue' => 'required',
            'date' => 'required|date',
            'photo' => 'nullable|image|mimes',  // validating image types and size
            'ticket_price' => 'required|numeric',
            'ticket_stock' => 'required|integer'
        ]);

        // Handle the file upload
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            // Delete old photo if exists
            if ($event->photo) {
                Storage::delete($event->photo);
            }
            $path = $request->file('photo')->store('public/events');
            $validatedData['photo'] = $path;
        }

        // Geocode the venue if it has been changed
        if ($event->venue !== $request->venue) {
            $client = new \GuzzleHttp\Client();
            $geocoder = new Geocoder($client);
            $apiKey = config('geocoder.key');
            if (!$apiKey) {
                return back()->withErrors('Geocoding API key is missing.');
            }

            $geocoder->setApiKey($apiKey);
            $geocoder->setCountry('MY'); // Optional: set the country

            $coordinates = $geocoder->getCoordinatesForAddress($request->venue);
            $validatedData['latitude'] = $coordinates['lat'] ?? null;
            $validatedData['longitude'] = $coordinates['lng'] ?? null;
        }

        // Update the event with the new data
        $event->update($validatedData);
        return redirect()->route('event.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        // Delete the specified event
        if ($event->photo) {
            Storage::delete($event->photo);
        }
        $event->delete();
        return redirect()->route('event.index')->with('success', 'Event deleted successfully.');
    }

    public function showTicket(Ticket $ticket)
    {
        return view('tickets.show', compact('ticket'));
    }
}
