@extends('adminlte.layouts.app')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Data Report</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">View-data</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="card card-primary card-outline">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <form action="{{ route('export') }}" method="GET" class="form-inline">
                            @csrf
                            <div class="form-group mx-sm-3 mb-2">
                                <label for="start_date" class="sr-only">Start Date</label>
                                <input type="date" id="start_date" name="start_date" class="form-control" required>
                            </div>
                            <div class="form-group mx-sm-3 mb-2">
                                <label for="end_date" class="sr-only">End Date</label>
                                <input type="date" id="end_date" name="end_date" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-success mb-2">
                                <i class="fas fa-file-excel"></i> Export
                            </button>
                        </form>

                        <!-- Sorting Form -->
                        <form method="GET" action="{{ route('view-data.index') }}" class="form-inline mb-2 ml-auto">
                            <div class="form-group mx-sm-3 mb-2" >
                                <label for="sort">Sort by: </label>
                                <select name="sort" id="sort" class="form-control d-inline-block w-auto">
                                    <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Created At</option>
                                    <option value="datetime" {{ request('sort') == 'datetime' ? 'selected' : '' }}>Date Time</option>
                                    <option value="pH" {{ request('sort') == 'pH' ? 'selected' : '' }}>pH</option>
                                    <option value="cod" {{ request('sort') == 'cod' ? 'selected' : '' }}>COD</option>
                                    <option value="tss" {{ request('sort') == 'tss' ? 'selected' : '' }}>TSS</option>
                                    <option value="nh3n" {{ request('sort') == 'nh3n' ? 'selected' : '' }}>NH3N</option>
                                    <option value="debit" {{ request('sort') == 'debit' ? 'selected' : '' }}>Debit</option>
                                </select>
                            </div>
                            
                            <div class="form-group mx-sm-3 mb-2">
                                <select name="order" id="order" class="form-control d-inline-block w-auto">
                                    <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                                    <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Descending</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success mb-2">
                                <i class="fas fa-sort"></i> Sort
                            </button>
                        </form>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><a href="{{ route('view-data.index', ['sort' => 'uid', 'order' => request('order') == 'asc' ? 'desc' : 'asc']) }}">UID</a></th>
                                    <th><a href="{{ route('view-data.index', ['sort' => 'pH', 'order' => request('order') == 'asc' ? 'desc' : 'asc']) }}">pH</a></th>
                                    <th><a href="{{ route('view-data.index', ['sort' => 'cod', 'order' => request('order') == 'asc' ? 'desc' : 'asc']) }}">COD</a></th>
                                    <th><a href="{{ route('view-data.index', ['sort' => 'tss', 'order' => request('order') == 'asc' ? 'desc' : 'asc']) }}">TSS</a></th>
                                    <th><a href="{{ route('view-data.index', ['sort' => 'nh3n', 'order' => request('order') == 'asc' ? 'desc' : 'asc']) }}">NH3N</a></th>
                                    <th><a href="{{ route('view-data.index', ['sort' => 'debit', 'order' => request('order') == 'asc' ? 'desc' : 'asc']) }}">Debit</a></th>
                                    <th><a href="{{ route('view-data.index', ['sort' => 'datetime', 'order' => request('order') == 'asc' ? 'desc' : 'asc']) }}">Date Time</a></th>
                                    <th><a href="{{ route('view-data.index', ['sort' => 'created_at', 'order' => request('order') == 'asc' ? 'desc' : 'asc']) }}">Date</a></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $datas)
                                    <tr>
                                        <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
                                        <td>{{ $datas->uid }}</td>
                                        <td>{{ $datas->pH }}</td>
                                        <td>{{ $datas->cod }}</td>
                                        <td>{{ $datas->tss }}</td>
                                        <td>{{ $datas->nh3n }}</td>
                                        <td>{{ $datas->debit }}</td>
                                        <td>{{ \Carbon\Carbon::parse($datas->datetime)->setTimezone('Asia/Jakarta')->translatedFormat('l, d F Y H:i') }}</td>
                                        <td>{{ $datas->created_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $data->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
