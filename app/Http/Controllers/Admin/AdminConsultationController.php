<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\Request;

class AdminConsultationController extends Controller
{
    public function index()
    {
        $consultations = Consultation::orderBy('created_at', 'desc')->get();
        return view('admin.consultations.index', compact('consultations'));
    }

    public function show($id)
    {
        $consultation = Consultation::findOrFail($id);
        
        // Auto-mark as read when viewed
        if (!$consultation->is_read) {
            $consultation->update(['is_read' => true]);
        }

        return view('admin.consultations.show', compact('consultation'));
    }

    public function toggleRead($id)
    {
        $consultation = Consultation::findOrFail($id);
        $consultation->update([
            'is_read' => !$consultation->is_read
        ]);

        return back()->with('success', 'Status pesan berhasil diubah.');
    }

    public function destroy($id)
    {
        $consultation = Consultation::findOrFail($id);
        $consultation->delete();

        return redirect()->route('admin.consultations.index')
            ->with('success', 'Pesan berhasil dihapus.');
    }
}
