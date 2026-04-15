@extends('backend.layouts.master')

@section('title')
Manage Resumes
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('backend/assets/datatable/css/dataTables-responsive.css') }}">
@endpush

@section('content')
<div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <h4>Manage Resumes</h4>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <a class="btn btn-primary" href="{{ route('resume.create') }}">
                        <i class="fa fa-plus"></i> Add Resume
                    </a>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="card-box mb-30">
            <div class="pd-20">
                <h4 class="text-blue h4">All Resume Records</h4>
            </div>

            <div class="pb-20">

                <table class="table hover data-table-export1 nowrap" data-title="Resume List">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Name</th>
                            <th>Title</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th class="no-export">Action</th>
                            <th class="no-export">Edit</th>
                            <th class="no-export">Delete</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($resumes as $key => $resume)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $resume->name ?? '-' }}</td>
                                <td>{{ $resume->title ?? '-' }}</td>
                                <td>{{ $resume->email ?? '-' }}</td>
                                <td>{{ $resume->phone ?? '-' }}</td>
                                <td>{{ $resume->location ?? '-' }}</td>
                                {{-- Status --}}
                                <td>
                                    @if($resume->status == 'active')
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-warning">Inactive</span>
                                    @endif
                                </td>

                                <!-- Download / Preview -->
                                <td class="no-export">
                                    <div class="btn-group" role="group">

                                        {{-- Preview --}}
                                        <a href="{{ route('resume.pdf', $resume->id) }}?preview=1" target="_blank">
                                            <button class="btn btn-info btn-sm">
                                                Preview
                                            </button>
                                        </a>

                                        {{-- Download --}}
                                        <a href="{{ route('resume.pdf', $resume->id) }}">
                                            <button class="btn btn-success btn-sm">
                                                Download
                                            </button>
                                        </a>

                                    </div>
                                </td>

                                <!-- EDIT -->
                                <td class="no-export">
                                    <a href="{{ route('resume.edit', $resume->id) }}">
                                        <button class="btn btn-warning btn-sm">
                                            <i class="micon dw dw-pencil-1"></i> Edit
                                        </button>
                                    </a>
                                </td>

                                <!-- DELETE -->
                                <td class="no-export">
                                    <form action="{{ route('resume.destroy', $resume->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure to delete?')">
                                            <i class="micon dw dw-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>

    </div>

    <x-backend.footer />
</div>
@endsection

@push('scripts')
<script src="{{ asset('backend/assets/datatable/js/datatable-init.js') }}"></script>
@endpush