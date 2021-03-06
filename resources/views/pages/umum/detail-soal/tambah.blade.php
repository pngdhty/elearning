@extends('layouts.' . $layout)

@section('title', 'Dashboard Soal - Tambah Detail Soal')

@section('content')
    <div class="container-fluid">

          <!-- Content Row -->

          <div class="row">

            <!-- Index Siswa -->
            <div class="col-xl-9 col-lg-8">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Tambah Detail Soal - {{ $soal->judul }} ({{ $soal->kategori }})</h6>
                  <h6 class="m-0 font-weight-bold text-primary">Kelas {{ $soal->kelas }}</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                <form class="user" name="detailsoal" action="{{ route('detail.store', $soal->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="float-right">
                        <a id="tambah" class="btn btn-md btn-primary text-white">Tambah</a>
                    </div>
                    <div class="wrapper">
                        <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a id="first" class="nav-link active" id="soal-tab" data-toggle="pill" href="#soal_1" role="tab" aria-controls="soal_1" aria-selected="true">1</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade first show active" id="soal_1" role="tabpanel" aria-labelledby="soal-tab">
                                <div class="form-group">
                                    <textarea name="soal[]" id="summernote" required>{{ old('soal[]') }}</textarea>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-5 mb-0 mb-sm-0">
                                        <input id="gambar" type="file" class="form-control @error('gambar') is-invalid @enderror" name="gambar_1" value="" autocomplete="gambar" autofocus>
                                    </div>
                                    <div class="col-md-7 mb-0 mb-sm-0">
                                        <div class="form-check-inline form-check" style="width: 95%">
                                            <input type="radio" id="kunci" name="kunci_1" class="form-check-input" value="1" required>
                                            <input id="jawaban" type="text" class="col-md-12 mb-1 form-control form-control-md @error('jawaban') is-invalid @enderror" placeholder="Jawaban A" name="jawaban_1_[]" value="{{ old('jawaban_1_[]') }}" required autocomplete="jawaban" autofocus>
                                        </div>
                                        <div class="form-check-inline form-check" style="width: 95%">
                                            <input type="radio" id="kunci" name="kunci_1" class="form-check-input" value="2" required>
                                            <input id="jawaban" type="text" class="col-md-12 mb-1 form-control form-control-md @error('jawaban') is-invalid @enderror" placeholder="Jawaban B" name="jawaban_1_[]" value="{{ old('jawaban_1_[]') }}" required autocomplete="jawaban" autofocus>
                                        </div>
                                        <div class="form-check-inline form-check" style="width: 95%">
                                            <input type="radio" id="kunci" name="kunci_1" class="form-check-input" value="3" required>
                                            <input id="jawaban" type="text" class="col-md-12 mb-1 form-control form-control-md @error('jawaban') is-invalid @enderror" placeholder="Jawaban C" name="jawaban_1_[]" value="{{ old('jawaban_1_[]') }}" required autocomplete="jawaban" autofocus>
                                        </div>
                                        <div class="form-check-inline form-check" style="width: 95%">
                                            <input type="radio" id="kunci" name="kunci_1" class="form-check-input" value="4" required>
                                            <input id="jawaban" type="text" class="col-md-12 mb-1 form-control form-control-md @error('jawaban') is-invalid @enderror" placeholder="Jawaban D" name="jawaban_1_[]" value="{{ old('jawaban_1_[]') }}" required autocomplete="jawaban" autofocus>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block" id="submit">Proses</button>
                </form>
                </div>
              </div>
            </div>

            <!-- Pie Chart -->
            <div class="col-xl-3 col-lg-4">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Excel Upload</h6>
                  <a href="{{ url('/storage/example.xlsx') }}" class="m-0 font-weight-bold text-primary"><i class="fas fa-arrow-circle-down"></i></a>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <form action="{{ route('detail.excel', $soal->id) }}" class="user" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <div class="mb-0 mb-sm-0">
                                <input id="excel" type="file" class="form-control @error('excel') is-invalid @enderror" name="excel" autocomplete="excel" required>
                            </div>
                            <div class="mb-0 mb-sm-0 mt-2">
                                <button type="submit" class="btn btn-primary btn-user btn-block">Proses</button>
                            </div>
                        </div>
                    </form>
                </div>
              </div>
            </div>
          </div>
    </div>
@endsection
@push('addon-style')
    <link rel="stylesheet" href="{{ asset('assets/libraries/summernote/summernote.bs4.css ') }}">
@endpush

@push('addon-script')
<script src="{{ asset('assets/libraries/summernote/summernote.bs4.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#summernote').summernote({
                placeholder: 'Soal..',
                height: 200,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['fontname', ['fontname', 'fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['view', ['fullscreen']],
                ]
            });

            $('#submit').on('click', function(){
                if ($('#summernote').summernote('isEmpty')) {
                    alert('editor content is empty');
                }
            })

            var count = 2;
            var t;

            

            function resetContents() {
                var j = 1,
                    id, aria, $this, rname, tname, fname;

                // for each element on the page with the class .input-wrap
                $('div.tab-pane').each(function () {
                    if (j > 1) {
                        // within each matched .input-wrap element, find each <input> element
                        $(this).each(function () {
                            $this = $(this);
                            id = $this.attr("id").split('_')[0];
                            aria = $this.attr('aria-labelledby').split('_')[0];
                            $(this).attr('id', id + '_' + j);
                            $(this).attr('aria-labelledby', aria + '_' + j);

                            $this.find('input:radio').each(function(){
                                rname = $(this).attr('name').split('_')[0];
                                $(this).attr('name', rname + '_' + j);
                            })

                            $this.find('input:file').each(function(){
                                fname = $(this).attr('name').split('_')[0];
                                $(this).attr('name', fname + '_' + j);
                            })

                            $this.find('#jawaban').each(function(){
                                tname = $(this).attr('name').split('_')[0];
                                $(this).attr('name', tname + '_' + j + '_[]');
                            })
                        })
                    }
                    j++
                });
            }

            function resetTabs() {
                var z = 1,
                    id, href;

                // for each element on the page with the class .input-wrap
                $('ul.nav-pills li.nav-item').each(function () {
                    if (z > 1) {
                        // within each matched .input-wrap element, find each <input> element
                        $(this).find('a').each(function () {
                            id = $(this).attr('id').split('_')[0];
                            href = $(this).attr('href').split('_')[0]
                            ariac = $(this).attr('aria-controls').split('_')[0]

                            $(this).attr('id', id + '_' + z);
                            $(this).attr('href', href + '_' + z);
                            $(this).attr('aria-controls', ariac + '_' + z)
                            $(this).html(z)
                        })
                    }
                    z++
                });
            }


            $('#tambah').click(function () {
            t = $('ul.nav-pills li.nav-item').length + 1;
                $('#pills-tab').append(`
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="soal-tab_`+t+`" data-toggle="pill" href="#soal_`+t+`" role="tab" aria-controls="soal_`+t+`" aria-selected="true">`+t+`</a>
                    </li>
                `);

                $('#pills-tabContent').append(`
                    <div class="tab-pane fade" id="soal_`+t+`" role="tabpanel" aria-labelledby="soal-tab_`+t+`">
                            <a href="#" id="remove" class="btn btn-sm btn-danger float-right mb-4 my-2 ml-1">X</a>
                        <div class="form-group">
                            <div class="summer-container`+t+`">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-5 mb-0 mb-sm-0">
                                <input id="gambar" type="file" class="form-control @error('gambar') is-invalid @enderror" name="gambar_`+t+`" value="" autocomplete="gambar" autofocus>
                            </div>
                            <div class="col-md-7 mb-0 mb-sm-0" id="sjawaban">
                                <div class="form-check-inline form-check" style="width: 95%">
                                    <input type="radio" id="kunci_`+t+`" name="kunci_`+t+`" class="form-check-input" value="1" required>
                                    <input id="jawaban" type="text" class="col-md-12 mb-1 form-control form-control-md" placeholder="Jawaban A" name="jawaban_`+t+`_[]" required autocomplete="jawaban A" autofocus>
                                </div>
                                <div class="form-check-inline form-check" style="width: 95%">
                                    <input type="radio" id="kunci_`+t+`" name="kunci_`+t+`" class="form-check-input" value="2" required>
                                    <input id="jawaban" type="text" class="col-md-12 mb-1 form-control form-control-md" placeholder="Jawaban B" name="jawaban_`+t+`_[]" required autocomplete="jawaban B" autofocus>
                                </div>
                                <div class="form-check-inline form-check" style="width: 95%">
                                    <input type="radio" id="kunci_`+t+`" name="kunci_`+t+`" class="form-check-input" value="3" required>
                                    <input id="jawaban" type="text" class="col-md-12 mb-1 form-control form-control-md" placeholder="Jawaban C" name="jawaban_`+t+`_[]" required autocomplete="jawaban C" autofocus>
                                </div>
                                <div class="form-check-inline form-check" style="width: 95%">
                                    <input type="radio" id="kunci_`+t+`" name="kunci_`+t+`" class="form-check-input" value="4" required>
                                    <input id="jawaban" type="text" class="col-md-12 mb-1 form-control form-control-md" placeholder="Jawaban D" name="jawaban_`+t+`_[]"required autocomplete="jawaban D" autofocus>
                                </div>
                            </div>
                        </div>
                    </div>
                `);

                $(`<textarea name="soal[]" required>{{ old('soal[]') }}</textarea>`)
                    .appendTo('.summer-container'+t+'')
                    .summernote({
                    placeholder: 'Soal..',
                    height: 200,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['fontname', ['fontname', 'fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['view', ['fullscreen']],
                    ]
                });
                    
                t++
            })

            $('#pills-tabContent').on('click', '#remove', function () {

                if (t > 2) {
                    var q = $(this).closest('div').attr('aria-labelledby')
                    $('#' + q).closest('li').remove()
                    $(this).closest('div').remove()
                    $('#first').addClass('active')
                    $('.first').addClass('show active')
                    resetTabs();
                    resetContents();

                }
            })

        });
    </script>
@endpush