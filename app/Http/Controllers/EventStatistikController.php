<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;

class EventStatistikController extends Controller
{
    /**
     * ✅ WEB: Dashboard - Statistik per event + grafik kategori
     */
    public function dashboard()
    {
        // Ambil semua event dengan relasi registrasi dan ticket.attendance
        $events = Event::with(['registrations.ticket.attendance'])->get();

        // Statistik tiap event
        $statistik = $events->map(function ($event) {
            $terdaftar = $event->registrations->count();

            $hadir = $event->registrations->filter(function ($r) {
                return optional(optional($r->ticket)->attendance)->status_attd === 'hadir';
            })->count();

            return [
                'nama_event'   => $event->nama_event,
                'terdaftar'    => $terdaftar,
                'hadir'        => $hadir,
                'tidak_hadir'  => $terdaftar - $hadir,
                'persentase'   => $event->kuota > 0 ? round(($terdaftar / $event->kuota) * 100, 2) : 0,
            ];
        });

        // Statistik seminar dan workshop per bulan
        $seminarData = [];
        $workshopData = [];

        for ($month = 1; $month <= 12; $month++) {
            $seminarData[] = Registration::whereHas('event', function ($q) {
                $q->where('kategori_event', 'seminar');
            })->whereMonth('created_at', $month)->count();

            $workshopData[] = Registration::whereHas('event', function ($q) {
                $q->where('kategori_event', 'workshop');
            })->whereMonth('created_at', $month)->count();
        }

        return view('admin.dashboard', [
            'statistik' => $statistik,
            'seminarData' => $seminarData,
            'workshopData' => $workshopData,
        ]);
    }

    /**
     * ✅ API: Statistik satu event
     */
    public function api($id)
    {
        $event = Event::with('registrations')->findOrFail($id);

        $total = $event->registrations->count();
        $hadir = $event->registrations->where('hadir', true)->count();
        $tidak_hadir = $total - $hadir;
        $persentase = $event->kuota > 0 ? round(($total / $event->kuota) * 100, 2) : 0;

        return response()->json([
            'event' => $event->nama_event,
            'kuota' => $event->kuota,
            'terdaftar' => $total,
            'hadir' => $hadir,
            'tidak_hadir' => $tidak_hadir,
            'persentase_keterisian' => $persentase,
            'chart_data' => [
                'labels' => ['Hadir', 'Tidak Hadir'],
                'datasets' => [[
                    'data' => [$hadir, $tidak_hadir],
                    'backgroundColor' => ['#4CAF50', '#F44336']
                ]]
            ]
        ]);
    }
}
