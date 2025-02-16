@extends('adminlte.layouts.app')

@section('content')   
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Profile</h1>
                    </div><!-- /.col -->
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="card card-primary card-outline">                    
                        <div class="card-body">                            
                            <strong><i class="fas fa-city mr-1"></i> Nama Perusahaan</strong>
                            <p class="text-muted">
                            {{$user->company}}
                            </p>

                            <hr>

                            <strong><i class="fas fa-map-marker-alt mr-1"></i> Alamat</strong>

                            <p class="text-muted">{{$user->address}}</p>

                            <hr>

                            <strong><i class="fas fa-phone-alt mr-1"></i> Nomor Telpon</strong>

                            <p class="text-muted">{{$user->phone}}</p>

                            <hr>

                            <strong><i class="fas fa-industry mr-1"></i> Bidang Perusahaan</strong>
                            <p class="text-muted">{{$user->companytype}}</p>
                            
                        </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
@endsection