<div class="row mb-3">
    <div class="col">
        <select name="month" class="form-control" aria-label="Pilih Bulan">
            <option value="">Pilih Bulan</option>
            @foreach (range(1, 12) as $month)
                <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->locale('id')->month($month)->translatedFormat('F') }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col">
        <select name="year" class="form-control" aria-label="Pilih Tahun">
            <option value="">Pilih Tahun</option>
            @foreach (range(date('Y') - 5, date('Y')) as $year)
                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                    {{ $year }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col">
        <button type="submit" class="btn btn-modern">Filter</button>
    </div>
</div>
