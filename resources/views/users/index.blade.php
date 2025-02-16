@extends('adminlte.layouts.app')

@section('content')   
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">User Management</h1>
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
                    <div class="card-header">
                        <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add User</a>
                    </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>UID</th>
                                        <th>Company</th>
                                        <th>Company Type</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->role }}</td>
                                            <td>{{ $user->uid}}</td>
                                            <td>{{ $user->company }}</td>
                                            <td>{{ $user->companytype }}</td>
                                            <td>{{ $user->phone }}</td>
                                            <td>{{ $user->address }}</td>
                                            <td>{{ $user->status }}</td>
                                            <td>
                                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> Edit</a>
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i> Delete</button>
                                                </form>
                                            </td>
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