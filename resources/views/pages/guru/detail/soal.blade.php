@extends('layouts.guru')

@section('title', 'Dashboard Materi')

@section('content')
    <div class="container-fluid">
      <div class="row">
        <div class="col-xl-12 col-lg-11">
          <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary">List Soal</h6>
            </div>
            <div class="card-body">
              <div class="row">
                  @foreach ($soals as $soal)
                    @foreach ($soal->mapels as $item)
                        <div class="col-md-6 col-xl-3 col-lg-4">
                          <div class="card mb-4">
                              <div class="card-header py-3 d-flex flew-row align-items-center justify-content-center">
                                  <h6 class="m-0 font-weight-bold text-dark">Kelas {{ $item->kelas->kelas }}{{ $item->kelas->kode_kelas }}</h6>
                              </div>
                              <div class="card-body">
                                  <div class="align-items-center justify-content-center text-center">
                                      <h6 class="font-weight-bold text-dark">{{ $item->kelas->wali_kelas->nama }}</h6><br>
                                      <h6 class="m-0 text-dark">{{ $item->nama }}</h6>
                                      <h6 class="m-0 font-weight-bold text-info">Soal : {{ $item->soal_kelas }} Umum</h6><br>
                                      <a href="{{ route('detail.soal.guru', [$item->kelas->kelas.'-'.$item->kelas->kode_kelas, \Str::slug($item->nama), $item->id]) }}" class="btn btn-sm btn-primary">Lihat</a>
                                  </div>
                              </div>
                          </div>
                      </div>
                    @endforeach
                  @endforeach
              </div>
            </div>
          </div>
        </div>
    </div>
@endsection