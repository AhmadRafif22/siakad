<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //fungsi eloquent menampilkan data menggunakan pagination
        // $mahasiswa = Mahasiswa::all(); // Mengambil semua isi tabel
        // $paginate = Mahasiswa::orderBy('id_mahasiswa', 'asc')->paginate(5);
        // return view('mahasiswa.index', ['mahasiswa' => $mahasiswa, 'paginate' => $paginate]);

        $mahasiswa = DB::table('mahasiswa')->paginate(5);
        return view('mahasiswa.index', ['mahasiswa' => $mahasiswa]);
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        $mahasiswa = DB::table('mahasiswa')
            ->where('nim', 'LIKE', "%{$search}%")
            ->orWhere('nama', 'LIKE', "%{$search}%")
            ->orWhere('kelas', 'LIKE', "%{$search}%")
            ->orWhere('jurusan', 'LIKE', "%{$search}%")
            ->orWhere('jk', 'LIKE', "%{$search}%")
            ->orWhere('email', 'LIKE', "%{$search}%")
            ->orWhere('alamat', 'LIKE', "%{$search}%")
            ->orWhere('tanggalLahir', 'LIKE', "%{$search}%")
            ->paginate(5);

        return view('mahasiswa.index', ['mahasiswa' => $mahasiswa]);
    }

    public function create()
    {
        return view('mahasiswa.create');
    }

    public function store(Request $request)
    {
        //melakukan validasi data
        $request->validate([
            'Nim' => 'required',
            'Nama' => 'required',
            'Kelas' => 'required',
            'Jurusan' => 'required',
            'Jk' => 'required',
            'Email' => 'required',
            'Alamat' => 'required',
            'TanggalLahir' => 'required',
        ]);
        //fungsi eloquent untuk menambah data
        Mahasiswa::create($request->all());
        //jika data berhasil ditambahkan, akan kembali ke halaman utama
        return redirect()->route('mahasiswa.index')
            ->with('success', 'Mahasiswa Berhasil Ditambahkan');
    }

    public function show($nim)
    {
        //menampilkan detail data dengan menemukan/berdasarkan Nim Mahasiswa
        $Mahasiswa = Mahasiswa::where('nim', $nim)->first();
        return view('mahasiswa.detail', compact('Mahasiswa'));
    }

    public function edit($nim)
    {
        //menampilkan detail data dengan menemukan berdasarkan Nim Mahasiswa untuk diedit
        $Mahasiswa = DB::table('mahasiswa')->where('nim', $nim)->first();
        return view('mahasiswa.edit', compact('Mahasiswa'));
    }

    public function update(Request $request, $nim)
    {
        //melakukan validasi data
        $request->validate([
            'Nim' => 'required',
            'Nama' => 'required',
            'Kelas' => 'required',
            'Jurusan' => 'required',
            'Jk' => 'required',
            'Email' => 'required',
            'Alamat' => 'required',
            'TanggalLahir' => 'required',
        ]);
        //fungsi eloquent untuk mengupdate data inputan kita
        Mahasiswa::where('nim', $nim)
            ->update([
                'nim' => $request->Nim,
                'nama' => $request->Nama,
                'kelas' => $request->Kelas,
                'jurusan' => $request->Jurusan,
                'Jk' => $request->Jk,
                'Email' => $request->Email,
                'Alamat' => $request->Alamat,
                'TanggalLahir' => $request->TanggalLahir,
            ]);
        //jika data berhasil diupdate, akan kembali ke halaman utama
        return redirect()->route('mahasiswa.index')
            ->with('success', 'Mahasiswa Berhasil Diupdate');
    }

    public function destroy($nim)
    {
        //fungsi eloquent untuk menghapus data
        Mahasiswa::where('nim', $nim)->delete();
        return redirect()->route('mahasiswa.index')
            ->with('success', 'Mahasiswa Berhasil Dihapus');
    }
};
