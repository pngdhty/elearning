<select name="materi_id" class="form-control" required>
    <option value="">Materi</option>
    @foreach ($materi as $item)
        <option value="{{ $item->id }}">{{ $item->judul }} (@if ($item->author == Auth::id()) {{ 'tambahan' }} @else{{ $item->pertemuan }}@endif)</option>
    @endforeach
</select>