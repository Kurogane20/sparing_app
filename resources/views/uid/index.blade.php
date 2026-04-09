@extends('adminlte.layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row align-items-center mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0" style="font-size:1.4rem;font-weight:700;">
                            <i class="fas fa-database mr-2" style="color:#4f46e5;font-size:1.2rem;"></i>Data Logger
                        </h1>
                        <p class="m-0" style="font-size:0.75rem;color:#94a3b8;">Daftar UID dan tipe data logger</p>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Data Logger</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                @endif

                <div class="card card-primary card-outline">
                    <div class="card-header d-flex align-items-center">
                        <div>
                            <h5 class="m-0"><i class="fas fa-list mr-2" style="color:#4f46e5;"></i>Daftar Logger</h5>
                            <p class="m-0" style="font-size:0.72rem;color:#94a3b8;">Total: {{ $uids->count() }} logger</p>
                        </div>
                        @if(Auth::user()->role == 'admin')
                            <a href="{{ route('uids.create') }}" class="btn btn-primary ml-auto">
                                <i class="fas fa-plus mr-1"></i> Tambah UID
                            </a>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>User</th>
                                        <th>UID</th>
                                        <th>Lokasi</th>
                                        <th>Tipe Data</th>
                                        @if(Auth::user()->role == 'admin')
                                            <th>Aksi</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($uids as $uid)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $uid->user->name }}</td>
                                            <td>
                                                <code style="background:#f1f5f9;color:#4f46e5;padding:2px 8px;border-radius:6px;font-size:0.82rem;font-weight:600;">
                                                    {{ $uid->uid }}
                                                </code>
                                            </td>
                                            <td>{{ $uid->lokasi }}</td>
                                            <td>
                                                @if($uid->tipe_data === 'klhk')
                                                    <span style="display:inline-flex;align-items:center;gap:4px;background:#eff6ff;color:#1d4ed8;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;border:1px solid #bfdbfe;">
                                                        <i class="fas fa-globe" style="font-size:0.65rem;"></i> KLHK
                                                    </span>
                                                @else
                                                    <span style="display:inline-flex;align-items:center;gap:4px;background:#f0fdf4;color:#166534;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;border:1px solid #bbf7d0;">
                                                        <i class="fas fa-building" style="font-size:0.65rem;"></i> Internal
                                                    </span>
                                                @endif
                                            </td>
                                            @if(Auth::user()->role == 'admin')
                                                <td>
                                                    <a href="{{ route('uids.edit', $uid->id) }}" class="btn btn-info btn-sm">
                                                        <i class="fas fa-pencil-alt"></i> Edit
                                                    </a>
                                                    <form action="{{ route('uids.destroy', $uid->id) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus UID ini?')">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </button>
                                                    </form>
                                                </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ Auth::user()->role == 'admin' ? 6 : 5 }}" class="text-center" style="color:#94a3b8;padding:32px;">
                                                <i class="fas fa-inbox" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
                                                Belum ada data logger
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
