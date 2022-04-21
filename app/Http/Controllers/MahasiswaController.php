<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

use App\Models\Kelas;
use App\Models\Mahasiswa_Matakuliah;

use Illuminate\Support\Facades\Storage;

use PDF;

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

        // $mahasiswa = DB::table('mahasiswa')->paginate(5);
        // return view('mahasiswa.index', ['mahasiswa' => $mahasiswa]);

        // yang semila Mahasiswa::all. diubah menjadi with() yang menyatakan relasi
        $mahasiswa = Mahasiswa::with('kelas')->get();
        $mahasiswa = Mahasiswa::OrderBy('id_mahasiswa', 'asc')->paginate(3);
        return view('mahasiswa.index', ['mahasiswa' => $mahasiswa]);
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        $mahasiswa = DB::table('mahasiswa')
            ->where('nim', 'LIKE', "%{$search}%")
            ->orWhere('nama', 'LIKE', "%{$search}%")
            ->orWhere('jurusan', 'LIKE', "%{$search}%")
            ->orWhere('jk', 'LIKE', "%{$search}%")
            ->orWhere('email', 'LIKE', "%{$search}%")
            ->orWhere('alamat', 'LIKE', "%{$search}%")
            ->orWhere('tanggalLahir', 'LIKE', "%{$search}%")
            ->paginate(3);

        return view('mahasiswa.index', ['mahasiswa' => $mahasiswa]);
    }

    public function create()
    {
        // return view('mahasiswa.create');

        $kelas = Kelas::all(); //mendapatkan data dari tabel kelas
        return view('mahasiswa.create', ['kelas' => $kelas]);
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
        // Mahasiswa::create($request->all());
        //jika data berhasil ditambahkan, akan kembali ke halaman utama
        // return redirect()->route('mahasiswa.index')
        //     ->with('success', 'Mahasiswa Berhasil Ditambahkan');

        if ($request->file('image')) {
            $image_name = $request->file('image')->store('images', 'public');
        }

        $mahasiswa = new Mahasiswa;
        $mahasiswa->nim = $request->get('Nim');
        $mahasiswa->nama = $request->get('Nama');
        $mahasiswa->jurusan = $request->get('Jurusan');
        $mahasiswa->jk = $request->get('Jk');
        $mahasiswa->email = $request->get('Email');
        $mahasiswa->alamat = $request->get('Alamat');
        $mahasiswa->tanggalLahir = $request->get('TanggalLahir');

        $mahasiswa->foto = $image_name;

        $kelas = new Kelas;
        $kelas->id = $request->get('Kelas');

        //fungsi elequent untuk menambah data dengan relasi belongsTo
        $mahasiswa->kelas()->associate($kelas);
        $mahasiswa->save();

        //jika data berhasil ditambahkan, akan kembali ke halaman utama
        return redirect()->route('mahasiswa.index')
            ->with('success', 'Mahasiswa Berhasil Ditambahkan');
    }

    public function show($nim)
    {
        //menampilkan detail data dengan menemukan/berdasarkan Nim Mahasiswa
        // $Mahasiswa = Mahasiswa::where('nim', $nim)->first();
        // return view('mahasiswa.detail', compact('Mahasiswa'));

        //menampilkan detail data berdasarkan Nim Mahasiswa
        //code sebelum dibuat relasi --> $mahasiswa = Mahasiswa::find($Nim)
        $mahasiswa = Mahasiswa::with('kelas')->where('nim', $nim)->first();

        return view('mahasiswa.detail', ['Mahasiswa' => $mahasiswa]);
    }

    public function edit($nim)
    {
        //menampilkan detail data dengan menemukan berdasarkan Nim Mahasiswa untuk diedit
        // $Mahasiswa = DB::table('mahasiswa')->where('nim', $nim)->first();
        // return view('mahasiswa.edit', compact('Mahasiswa'));

        //menampilkan detail data dengan menemukan berdasarkan Nim Mahasiswa untuk di edit
        $mahasiswa = Mahasiswa::with('kelas')->where('nim', $nim)->first();
        $kelas = Kelas::all(); // mendapat data dari tabel kelas
        return view('mahasiswa.edit', compact('mahasiswa', 'kelas'));
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
        // Mahasiswa::where('nim', $nim)
        //     ->update([
        //         'nim' => $request->Nim,
        //         'nama' => $request->Nama,
        //         'kelas' => $request->Kelas,
        //         'jurusan' => $request->Jurusan,
        //         'Jk' => $request->Jk,
        //         'Email' => $request->Email,
        //         'Alamat' => $request->Alamat,
        //         'TanggalLahir' => $request->TanggalLahir,
        //     ]);

        $mahasiswa = Mahasiswa::with('kelas')->where('nim', $nim)->first();

        if ($mahasiswa->foto && file_exists(storage_path('app/public/' . $mahasiswa->foto))) {
            Storage::delete('public/' . $mahasiswa->foto);
        }
        $image_name = $request->file('image')->store('images', 'public');
        $mahasiswa->foto = $image_name;

        $mahasiswa->nim = $request->get('Nim');
        $mahasiswa->nama = $request->get('Nama');
        $mahasiswa->jurusan = $request->get('Jurusan');
        $mahasiswa->jk = $request->get('Jk');
        $mahasiswa->email = $request->get('Email');
        $mahasiswa->alamat = $request->get('Alamat');
        $mahasiswa->tanggalLahir = $request->get('TanggalLahir');
        $mahasiswa->save();

        $kelas = new Kelas;
        $kelas->id = $request->get('Kelas');

        //fungsi elequent untuk menambah data dengan relasi belongsTo
        $mahasiswa->kelas()->associate($kelas);
        $mahasiswa->save();

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

    // latihan m-t-m
    public function khs($id)
    {
        $khs = Mahasiswa_Matakuliah::where('mahasiswa_id', $id)
            ->with('matakuliah')->get();
        $khs->mahasiswa = Mahasiswa::with('kelas')
            ->where('id_mahasiswa', $id)->first();

        return view('mahasiswa.khs', compact('khs'));
    }

    public function cetak_khs($id)
    {
        $khs = Mahasiswa_Matakuliah::where('mahasiswa_id', $id)
            ->with('matakuliah')->get();
        $khs->mahasiswa = Mahasiswa::with('kelas')
            ->where('id_mahasiswa', $id)->first();

        $pdf = PDF::loadview('mahasiswa.print_khs', ['khs' => $khs]);
        return $pdf->stream();
    }
};
