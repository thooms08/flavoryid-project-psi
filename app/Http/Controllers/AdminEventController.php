<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminEventController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('created_at', 'desc')->get();
        return view('admin.event.kelola-event', compact('events'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'event_start_date' => 'required|date',
            'event_end_date' => 'required|date|after_or_equal:event_start_date',
            'status' => 'required|in:active,inactive',
            'wa_group_link' => 'nullable|url|max:255',
        ]);

        $data = $request->except('image');
        
        // Generate slug unik dari nama event
        $data['url']  = Str::slug($request->event_name);
        $data['slug'] = Event::generateUniqueSlug($request->event_name);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('event', 'public');
            $data['image'] = $path;
        }

        Event::create($data);

        return redirect()->back()->with('success', 'Event berhasil ditambahkan.');
    }

    public function update(Request $request, $uuid)
    {
        $event = Event::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'event_name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'event_start_date' => 'required|date',
            'event_end_date' => 'required|date|after_or_equal:event_start_date',
            'status' => 'required|in:active,inactive',
            'wa_group_link' => 'nullable|url|max:255',
        ]);

        $data = $request->except('image');
        $data['url']  = Str::slug($request->event_name);
        // Regenerate slug hanya jika nama event berubah
        if ($event->event_name !== $request->event_name) {
            $data['slug'] = Event::generateUniqueSlug($request->event_name, $event->id);
        }

        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $path = $request->file('image')->store('event', 'public');
            $data['image'] = $path;
        }

        $event->update($data);

        return redirect()->back()->with('success', 'Event berhasil diperbarui.');
    }

    public function toggleStatus($uuid)
    {
        $event = Event::where('uuid', $uuid)->firstOrFail();
        $event->status = $event->status === 'active' ? 'inactive' : 'active';
        $event->save();

        return redirect()->back()->with('success', 'Status event berhasil diubah.');
    }

    public function destroy($uuid)
    {
        $event = Event::where('uuid', $uuid)->firstOrFail();
        
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return redirect()->back()->with('success', 'Event berhasil dihapus.');
    }
}
