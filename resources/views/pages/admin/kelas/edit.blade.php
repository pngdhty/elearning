@extends('layouts.admin')

@section('title', 'Admin - Tambah Kelas')

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-xl-9 col-lg-8">
        <div class="card shadow mb-4 border-info">
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-info">
            <h6 class="m-0 font-weight-bold text-white">Edit Kelas</h6>
          </div>
          <div class="card-body">
            <form class="user" method="POST" action="{{ route('kelas.update', $kelas->id) }}">
              @csrf @method('PATCH')
              @include('pages.admin.kelas.includes.form')
              <button type="submit" class="btn btn-primary btn-user btn-block">Update Kelas</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection