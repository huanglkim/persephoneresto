   <div class="row">
        <div class="col">
            <select name="startmonth" class="form-control">
                <option value="">Pilih Bulan Awal</option>
                @foreach(range(1, 12) as $month)
                    <option value="{{ $month }}" {{ request('startmonth') == $month ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col">
            <select name="startyear" class="form-control">
                <option value="">Pilih Tahun Awal</option>
                @foreach(range(date('Y') - 5, date('Y')) as $year)
                    <option value="{{ $year }}" {{ request('startyear') == $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col">
            <select name="endmonth" class="form-control">
                <option value="">Pilih Bulan Akhir</option>
                @foreach(range(1, 12) as $month)
                    <option value="{{ $month }}" {{ request('endmonth') == $month ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col">
            <select name="endyear" class="form-control">
                <option value="">Pilih Tahun Akhir</option>
                @foreach(range(date('Y') - 5, date('Y')) as $year)
                    <option value="{{ $year }}" {{ request('endyear') == $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col">
            <button type="submit" class="btn btn-modern">Filter</button>
        </div>
    </div>

