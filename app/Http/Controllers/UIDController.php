<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Uid;
use Illuminate\Http\Request;

class UIDController extends Controller
{
    public function index()
    {
        $uids = Uid::with(['siswa:id,name'])
                ->get()
                ->filter(fn ($uid) => !empty($uid->siswa?->name))
                ->values();

        $siswas = Siswa::query()->select('id', 'name')->orderBy('name')->get();

        return view('datauid', compact('uids', 'siswas'));
    }

    public function updateName(Request $request)
    {
        $validated = $request->validate([
            // validate against the correct connection/table names
            'uid_id' => 'required|integer|exists:absensi_v2.uid,id',
            'siswa_id' => 'required|integer|exists:siswa_connectis.view_siswa,id',
        ]);

        /** @var Uid $uid */
        $uid = Uid::query()->findOrFail($validated['uid_id']);
        $uid->id_siswa = $validated['siswa_id'];
        /** @var Siswa|null $siswa */
        $siswa = Siswa::find((int) $validated['siswa_id']);
        if ($siswa) {
            $uid->name = $siswa->name;
        }
        $uid->save();

        return response()->json([
            'success' => true,
            'uid' => [
                'id' => $uid->id,
                'uid' => $uid->uid,
                'name' => $uid->name,
                'siswa' => $siswa ? ['id' => $siswa->id, 'name' => $siswa->name] : null,
            ],
        ]);
    }
}
