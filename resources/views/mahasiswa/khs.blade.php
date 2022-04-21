@extends('mahasiswa.layout')
@section('content')
<div class="row my-3">
    <div class="col">
        <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
<div class="col-lg-12 margin-tb">
    <div class="pull-left mt-2">
        <h2 class="col-12 text-center">JURUSAN TEKNOLOGI INFORMASI-POLITEKNIK NEGERI MALANG</h2>
    </div>
    <div class="col-12 text-center">
        <h3><strong>KARTU HASIL STUDI (KHS)</strong></h3>
    </div>

    <a class="btn btn-success float-right" href="{{ route('mahasiswa.cetak_khs', $khs->mahasiswa->id_mahasiswa) }}"> Cetak PDF </a>

    <b>Nama:</b> {{ $khs->mahasiswa->nama }}<br>
    <b>NIM: </b>{{ $khs->mahasiswa->nim }}<br>
    <b>Kelas: </b> {{ $khs->mahasiswa->kelas->nama_kelas }}<br><br>
    <div class="col-12">
        <table class="table table-bordered">
            <tr>
                <th>Mata Kuliah</th>
                <th>SKS</th>
                <th>Semester</th>
                <th>Nilai</th>
            </tr>
            @foreach ($khs as $k)
            <tr>
                <td>{{ $k->matakuliah->nama_matkul }}</td>
                <td>{{ $k->matakuliah->sks }}</td>
                <td>{{ $k->matakuliah->semester }}</td>
                <td>{{ $k->nilai }}</td>
            </tr>
            @endforeach
        </table>
    </div>

    @endsection