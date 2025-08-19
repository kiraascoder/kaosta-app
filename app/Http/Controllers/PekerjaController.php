<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pekerja;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\OrderProduksiLog; // Pastikan ini di-import jika digunakan

class PekerjaController extends Controller
{
    public function index(Request $request)
    {
        // Eager load relasi 'user' untuk akses data user jika diperlukan di view
        $query = Pekerja::with('user');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('pekerja_id', 'like', "%{$search}%")
                ->orWhere('nama', 'like', "%{$search}%")
                ->orWhere('role', 'like', "%{$search}%")
                // Tambahkan pencarian berdasarkan email user jika ada
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('email', 'like', "%{$search}%");
                });
        }

        $pekerja = $query->orderBy('nama')->paginate(10);

        return view('pekerja_index', compact('pekerja'));
    }


    public function create()
    {
        return view('pekerja_create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'role' => 'required|in:Sales,Desain Grafis,Pemotong Kain,Penjahit,Sablon,Quality Control',
            'nohp' => 'nullable|string|max:255|unique:pekerja,nohp',
            'email' => 'nullable|email|max:255|unique:users,email', // Validasi unik di tabel users
            'alamat' => 'nullable|string',
            'tanggal_masuk' => 'required|date',
        ]);

        // Tentukan email yang akan digunakan untuk User
        $emailForUser = $validatedData['email'] ?? Str::slug($validatedData['nama']) . '@dummy.com';

        // Cari atau buat user baru
        // Penting: Pastikan role ditetapkan di sini saat membuat user,
        // agar user memiliki role yang valid saat pertama kali dibuat dan login.
        $user = User::firstOrCreate(
            ['email' => $emailForUser], // Kriteria pencarian
            [
                'name' => $validatedData['nama'],
                'password' => Hash::make('default123'), // Password default
                'role' => $validatedData['role'], // Set role di sini
            ]
        );

        // Jika user sudah ada tapi perannya berbeda (misal dari dummy ke peran asli), update perannya
        if ($user->wasRecentlyCreated === false && $user->role !== $validatedData['role']) {
            $user->role = $validatedData['role'];
            $user->save();
        }

        // Mapping role ke kode
        $roleMap = [
            'Sales' => 'SLS',
            'Desain Grafis' => 'DSG',
            'Pemotong Kain' => 'PMK',
            'Penjahit' => 'PJT',
            'Sablon' => 'SBL',
            'Quality Control' => 'QC',
        ];

        // Generate kode pekerja
        $tanggal = Carbon::parse($validatedData['tanggal_masuk'])->format('Ymd');
        $kodeRole = $roleMap[$validatedData['role']];

        // Ambil ID terakhir dari tabel pekerja untuk urutan, atau mulai dari 1 jika kosong
        $lastPekerjaId = Pekerja::latest('id')->value('id');
        $urutan = str_pad(($lastPekerjaId ? $lastPekerjaId + 1 : 1), 3, '0', STR_PAD_LEFT);

        $kodePekerja = "PK-{$kodeRole}-{$tanggal}-{$urutan}";

        // Simpan pekerja
        $pekerja = Pekerja::create([
            'user_id' => $user->id,
            'pekerja_id' => $kodePekerja, // Gunakan kode pekerja yang sudah di-generate
            'nama' => $validatedData['nama'],
            'nohp' => $validatedData['nohp'],
            'email' => $validatedData['email'], // Simpan email asli jika ada, atau biarkan null jika dummy
            'alamat' => $validatedData['alamat'],
            'tanggal_masuk' => $validatedData['tanggal_masuk'],
            'role' => $validatedData['role'],
        ]);

        // Pastikan role user sinkron dengan role pekerja
        // Ini sudah dilakukan di firstOrCreate atau update di atas, tapi bisa jadi fallback
        if ($user->role !== $pekerja->role) {
            $user->role = $pekerja->role;
            $user->save();
        }

        return redirect()->route('pekerja_index')->with('success', 'Pekerja & User berhasil ditambahkan!');
    }


    public function edit($id)
    {
        $pekerja = Pekerja::findOrFail($id);
        return view('pekerja_edit', compact('pekerja'));
    }

    public function update(Request $request, $id)
    {
        $pekerja = Pekerja::findOrFail($id);

        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'role' => 'required|in:Sales,Desain Grafis,Pemotong Kain,Penjahit,Sablon,Quality Control',
            'nohp' => 'nullable|string|max:255|unique:pekerja,nohp,' . $id, // Unique kecuali untuk diri sendiri
            'email' => 'nullable|email|max:255|unique:users,email,' . $pekerja->user_id, // Unique di tabel users, kecuali user ini
            'alamat' => 'nullable|string',
            'tanggal_masuk' => 'required|date',
        ]);

        // Update data pekerja
        $pekerja->update($validatedData);

        // Update data user yang terkait
        if ($pekerja->user) {
            $pekerja->user->update([
                'name' => $validatedData['nama'],
                'email' => $validatedData['email'] ?? Str::slug($validatedData['nama']) . '@dummy.com', // Update email user, gunakan dummy jika null
                'role' => $validatedData['role'], // Sinkronkan role user
            ]);
        }

        return redirect()->route('pekerja_index')->with('success', 'Data pekerja berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $pekerja = Pekerja::findOrFail($id);

        // Hapus user yang terkait jika tidak ada pekerja lain yang menggunakan user tersebut
        if ($pekerja->user_id) {
            $otherPekerja = Pekerja::where('user_id', $pekerja->user_id)->where('id', '!=', $pekerja->id)->count();
            if ($otherPekerja === 0) {
                // Hanya hapus user jika tidak ada pekerja lain yang terhubung
                $pekerja->user->delete();
            }
        }

        $pekerja->delete();

        return redirect()->route('pekerja_index')->with('success', 'Data pekerja berhasil dihapus.');
    }

    public function dashboard($pekerja_id)
    {
        $tugas = OrderProduksiLog::with(['order', 'order.produk', 'order.jenisKain', 'order.ukuran']) // Eager load relasi yang dibutuhkan
            ->where('pekerja_id', $pekerja_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $pekerja = Pekerja::where('id', $pekerja_id)->firstOrFail();

        return view('dashboard_pekerja', compact('tugas', 'pekerja'));
    }
}
