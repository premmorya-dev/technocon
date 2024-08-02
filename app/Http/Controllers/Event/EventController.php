<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TechnoconEvent;
use Illuminate\Http\RedirectResponse;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TechnoconEvent::query();

        // Apply filters
        if ($request->filled('filter_event_id')) {
            $query->where('event_id', '=', $request->input('filter_event_id'));
        }
        if ($request->filled('filter_event_code')) {
            $query->where('event_code', 'like', '%' . $request->input('filter_event_code') . '%');
        }
        if ($request->filled('filter_event_prefix')) {
            $query->where('event_prefix', 'like', '%' . $request->input('filter_event_prefix') . '%');
        }
        if ($request->filled('filter_event_name')) {
            $query->where('event_name', 'like', '%' . $request->input('filter_event_name') . '%');
        }


        $events = $query->sortable()->paginate(setting('pagination_limit'));
        return view('pages/event.list', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.event.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'event_code' => 'required|max:4',
            'event_backend_name' => 'required',
            'event_name' => 'required',
            'event_from_date' => 'required|date',
            'event_end_date' => 'required|date',


        ]);



        $user = TechnoconEvent::create([
            'event_code' => $request->event_code,
            'event_backend_name' => $request->event_backend_name,
            'event_presenting_partner_1' => $request->event_presenting_partner_1,
            'event_presenting_partner_2_association_slug' => $request->event_presenting_partner_2_association_slug,
            'event_presenting_partner_2' => $request->event_presenting_partner_2,
            'event_presenting_partner_1_association_slug' => $request->event_presenting_partner_1_association_slug,
            'event_name_prefix' => $request->event_name_prefix,
            'event_name' => $request->event_name,
            'event_name_suffix' => $request->event_name_suffix,
            'event_partner_1_association_slug' => $request->event_partner_1_association_slug,
            'event_partner_1' => $request->event_partner_1,
            'event_partner_1_suffix' => $request->event_partner_1_suffix,
            'event_partner_2_association_slug' => $request->event_partner_2_association_slug,
            'event_partner_2' => $request->event_partner_2,
            'event_partner_2_suffix' => $request->event_partner_2_suffix,
            'event_partner_3_association_slug' => $request->event_partner_3_association_slug,
            'event_partner_3' => $request->event_partner_3,
            'event_partner_3_suffix' => $request->event_partner_3_suffix,
            'event_from_date' => $request->event_from_date,
            'event_end_date' => $request->event_end_date,
            'event_url' => $request->event_url,
            'registration_page_banner_url' => $request->registration_page_banner_url,
            'registration_page_header_main_text' => $request->registration_page_header_main_text,
            'registration_page_header_important_notes' => $request->registration_page_header_important_notes,
            'whatsapp_notification_banner_image_dir_url' => $request->whatsapp_notification_banner_image_dir_url,
            'whatsapp_notification_banner_image' => $request->whatsapp_notification_banner_image,




        ]);

        return redirect()->route('event.list')->with('success', 'Event created successfully.');
    }

   

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($event_id)
    {
       
       $event = TechnoconEvent::find($event_id);
       return view('pages.event.edit', compact('event'));

    }

    // /**
    //  * Update the specified resource in storage.
    //  */
    public function update(Request $request,$event_id)
    {
        // dd($request->all());

        $request->validate([
            'event_code' => 'required|max:4',
            'event_backend_name' => 'required',
            'event_name' => 'required',
            'event_from_date' => 'required|date',
            'event_end_date' => 'required|date',


        ]);



        $event = TechnoconEvent::findOrFail($event_id);
        $event->event_code = $request->event_code;
        $event->event_backend_name = $request->event_backend_name;
        $event->event_presenting_partner_1 = $request->event_presenting_partner_1;
        $event->event_presenting_partner_2_association_slug = $request->event_presenting_partner_2_association_slug;
        $event->event_presenting_partner_2 = $request->event_presenting_partner_2;
        $event->event_presenting_partner_1_association_slug = $request->event_presenting_partner_1_association_slug;
        $event->event_name_prefix = $request->event_name_prefix;
        $event->event_name = $request->event_name;
        $event->event_name_suffix = $request->event_name_suffix;
        $event->event_partner_1_association_slug = $request->event_partner_1_association_slug;
        $event->event_partner_1 = $request->event_partner_1;
        $event->event_partner_1_suffix = $request->event_partner_1_suffix;
        $event->event_partner_2_association_slug = $request->event_partner_2_association_slug;
        $event->event_partner_2 = $request->event_partner_2;
        $event->event_partner_2_suffix = $request->event_partner_2_suffix;
        $event->event_partner_3_association_slug = $request->event_partner_3_association_slug;
        $event->event_partner_3 = $request->event_partner_3;
        $event->event_partner_3_suffix = $request->event_partner_3_suffix;
        $event->event_from_date = $request->event_from_date;
        $event->event_end_date = $request->event_end_date;
        $event->event_url = $request->event_url;
        $event->registration_page_banner_url = $request->registration_page_banner_url;
        $event->registration_page_header_main_text = $request->registration_page_header_main_text;
        $event->registration_page_header_important_notes = $request->registration_page_header_important_notes;
        $event->whatsapp_notification_banner_image_dir_url = $request->whatsapp_notification_banner_image_dir_url;
        $event->whatsapp_notification_banner_image = $request->whatsapp_notification_banner_image;


        $event->save();

      return redirect()->route('event.list')->with('success', 'User updated successfully.');

    }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    public function destroy(Request $request)
    {
        $currentEventId = auth()->id();

        if ($request->event_id) {


            $event = TechnoconEvent::find($request->event_id);

            if ($event) {
                $event->delete();
                session()->flash('success', 'Event deleted successfully');
                return response()->json(['error' => '0']);
            } else {
                session()->flash('success', 'Event not found');
                return response()->json(['error' => '1']);
            }
        }

        if ($request->event_ids) {
            foreach ($request->event_ids as $event_id) {
                $event = TechnoconEvent::find($event_id);
                if ($event) {
                    $event->delete();
                    session()->flash('success', 'All event deleted successfully');
                }
            }

            return response()->json(['error' => '0']);
        }
    }


}
