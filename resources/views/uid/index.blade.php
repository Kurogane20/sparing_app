@extends('adminlte.layouts.app')

@section('content')   
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Daftar Logger</h1>
                    </div><!-- /.col -->
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Starter Page</li>
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
                    @if(Auth::user()->role == 'admin')
                        <div class="card-header">
                            <a href="{{ route('uids.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add UID</a>
                        </div>
                    @endif
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>User</th>
                                        <th>UID</th>
                                        <th>Lokasi</th> 
                                        @if(Auth::user()->role == 'admin')                                       
                                            <th>Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($uids as $uid)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $uid->user->name }}</td>
                                            <td>{{ $uid->uid }}</td>
                                            <td>{{ $uid->lokasi }}</td>
                                            @if(Auth::user()->role == 'admin')                                           
                                                <td>
                                                    <a href="{{ route('uids.edit', $uid->id) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                                    <form action="{{ route('uids.destroy', $uid->id) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i> Delete</button>
                                                    </form>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
@endsection