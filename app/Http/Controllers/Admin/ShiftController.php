<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shift; // <-- PASTIKAN BARIS INI ADA
use App\Models\User; // <-- Add this if using the user variable in the form
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    /**
     * Menampilkan daftar semua shift.
     */
    public function index()
    {
        $shifts = Shift::orderBy('start_time')->get();
        return view('admin.shifts.index', compact('shifts'));
    }

    /**
     * Menampilkan form untuk membuat shift baru.
     */
    public function create()
    {
        // Buat instance Shift kosong untuk form partial
        $shift = new Shift(); // <-- $shift is defined here
        // Kirim hanya variabel $shift
        return view('admin.shifts.create', compact('shift')); // <-- $shift is passed here
    }

    /**
     * Menyimpan shift baru ke dalam database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        Shift::create($request->all());

        return redirect()->route('admin.shifts.index')->with('success', 'Shift baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit shift yang sudah ada.
     * Note: If the _form partial is expecting $user, you might need to adjust this
     * if you are editing a Shift model directly, not a User's shift.
     * Assuming the _form is generic or primarily for User edit context based on its content.
     */
    public function edit(Shift $shift)
    {
         // Also fetch shifts here for the edit form's partial dropdown
        $shifts = Shift::orderBy('start_time')->get();
        // If the form truly needs a $user variable even when editing a shift,
        // you might need additional logic or reconsider the partial's usage.
        // For now, passing the shift and the list of shifts.
        // If the form expects a $user, you might pass a dummy one or adjust the form.
        $user = new User(); // Or fetch a relevant user if applicable
        return view('admin.shifts.edit', compact('shift', 'shifts', 'user')); // Pass $user if _form needs it
    }

    /**
     * Mengupdate data shift di dalam database.
     */
    public function update(Request $request, Shift $shift)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $shift->update($request->all());

        return redirect()->route('admin.shifts.index')->with('success', 'Shift berhasil diperbarui.');
    }

    /**
     * Menghapus shift dari database.
     */
    public function destroy(Shift $shift)
    {
        // Consider adding checks here if shifts are heavily linked to schedules/attendances
        // to prevent accidental deletion or handle cascading deletes/updates.
        $shift->delete();
        return redirect()->route('admin.shifts.index')->with('success', 'Shift berhasil dihapus.');
    }
}
