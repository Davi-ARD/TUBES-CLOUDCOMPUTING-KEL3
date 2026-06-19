<?php

namespace App\Http\Controllers;

use App\Models\Lpj;
use App\Models\Proposal;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $proposals = Proposal::where('user_id', $user->id)
            ->latest()
            ->take(8)
            ->get()
            ->map(fn (Proposal $p) => [
                'type'         => 'Proposal',
                'icon'         => 'file-text',
                'nama'         => $p->nama_kegiatan,
                'status'       => $p->status,
                'created_at'   => $p->created_at,
                'tanggal'      => optional($p->created_at)->locale('id')->translatedFormat('j F Y'),
                'show_url'     => route('proposal.show', $p),
                'download_url' => route('proposal.generate', $p),
            ]);

        $lpjs = Lpj::where('user_id', $user->id)
            ->latest()
            ->take(8)
            ->get()
            ->map(fn (Lpj $l) => [
                'type'         => 'LPJ',
                'icon'         => 'clipboard-list',
                'nama'         => $l->nama_kegiatan,
                'status'       => $l->status,
                'created_at'   => $l->created_at,
                'tanggal'      => optional($l->created_at)->locale('id')->translatedFormat('j F Y'),
                'show_url'     => route('lpj.show', $l),
                'download_url' => route('lpj.generate', $l),
            ]);

        $recentDocuments = $proposals->concat($lpjs)
            ->sortByDesc('created_at')
            ->take(8)
            ->values();

        return view('dashboard', compact('user', 'recentDocuments'));
    }
}
