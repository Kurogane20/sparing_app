@extends('adminlte.layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row align-items-center mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0" style="font-size:1.4rem;font-weight:700;">
                            <i class="fas fa-plus-circle mr-2" style="color:#4f46e5;font-size:1.2rem;"></i>Tambah UID
                        </h1>
                        <p class="m-0" style="font-size:0.75rem;color:#94a3b8;">Daftarkan logger baru</p>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('uids.index') }}">Data Logger</a></li>
                            <li class="breadcrumb-item active">Tambah</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <strong>Terdapat kesalahan:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                @endif

                <div class="row">
                    <div class="col-lg-7 col-md-9">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5 class="m-0"><i class="fas fa-database mr-2" style="color:#4f46e5;"></i>Form Tambah UID</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('uids.store') }}" method="POST">
                                    @csrf

                                    <div class="form-group">
                                        <label for="user_id" style="font-size:0.83rem;font-weight:600;color:#475569;">
                                            <i class="fas fa-user mr-1" style="color:#4f46e5;"></i> Nama User
                                        </label>
                                        <select name="user_id" id="user_id" class="form-control" required>
                                            <option value="">-- Pilih User --</option>
                                            @foreach($users as $u)
                                                <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>
                                                    {{ $u->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="uid" style="font-size:0.83rem;font-weight:600;color:#475569;">
                                            <i class="fas fa-fingerprint mr-1" style="color:#4f46e5;"></i> UID
                                        </label>
                                        <input type="text" class="form-control" id="uid" name="uid"
                                               value="{{ old('uid') }}" placeholder="Masukkan UID logger" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="lokasi" style="font-size:0.83rem;font-weight:600;color:#475569;">
                                            <i class="fas fa-map-marker-alt mr-1" style="color:#4f46e5;"></i> Lokasi
                                        </label>
                                        <input type="text" class="form-control" id="lokasi" name="lokasi"
                                               value="{{ old('lokasi') }}" placeholder="Nama lokasi / titik pantau" required>
                                    </div>

                                    <div class="form-group">
                                        <label style="font-size:0.83rem;font-weight:600;color:#475569;">
                                            <i class="fas fa-tag mr-1" style="color:#4f46e5;"></i> Tipe Data
                                        </label>
                                        <div class="d-flex" style="gap:12px;">
                                            <label class="tipe-option" for="tipe_internal">
                                                <input type="radio" id="tipe_internal" name="tipe_data" value="internal"
                                                       {{ old('tipe_data', 'internal') === 'internal' ? 'checked' : '' }}>
                                                <div class="tipe-card">
                                                    <i class="fas fa-building"></i>
                                                    <span>Internal</span>
                                                    <small>Data logger internal perusahaan</small>
                                                </div>
                                            </label>
                                            <label class="tipe-option" for="tipe_klhk">
                                                <input type="radio" id="tipe_klhk" name="tipe_data" value="klhk"
                                                       {{ old('tipe_data') === 'klhk' ? 'checked' : '' }}>
                                                <div class="tipe-card">
                                                    <i class="fas fa-globe"></i>
                                                    <span>KLHK</span>
                                                    <small>Data logger pelaporan KLHK</small>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="d-flex" style="gap:10px;margin-top:8px;">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save mr-1"></i> Simpan
                                        </button>
                                        <a href="{{ route('uids.index') }}" class="btn btn-secondary" style="background:#e2e8f0;color:#475569;box-shadow:none;">
                                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

<style>
.tipe-option {
    cursor: pointer;
    flex: 1;
    margin: 0;
}
.tipe-option input[type="radio"] {
    display: none;
}
.tipe-card {
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    padding: 14px 16px;
    text-align: center;
    transition: all 0.2s;
    background: #fff;
}
.tipe-card i {
    font-size: 1.5rem;
    color: #94a3b8;
    display: block;
    margin-bottom: 6px;
}
.tipe-card span {
    display: block;
    font-weight: 600;
    font-size: 0.88rem;
    color: #1e293b;
}
.tipe-card small {
    display: block;
    font-size: 0.72rem;
    color: #94a3b8;
    margin-top: 2px;
}
.tipe-option input[type="radio"]:checked + .tipe-card {
    border-color: #4f46e5;
    background: #eef2ff;
    box-shadow: 0 0 0 3px rgba(79,70,229,0.12);
}
.tipe-option input[type="radio"]:checked + .tipe-card i {
    color: #4f46e5;
}
.tipe-option input[type="radio"]:checked + .tipe-card span {
    color: #4f46e5;
}
</style>
@endsection
