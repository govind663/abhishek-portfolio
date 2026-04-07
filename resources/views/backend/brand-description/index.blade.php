@extends('backend.layouts.master')

@section('title')
Manage Brand Description
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
                    <h4>Manage Brand Description</h4>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <a class="btn btn-primary" href="{{ route('brand-description.create') }}">
                        <i class="fa fa-plus"></i> Add Brand Description
                    </a>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="card-box mb-30">
            <div class="pd-20">
                <h4 class="text-blue h4">All Brand Description Records</h4>
            </div>

            <div class="pb-20">
                <table class="table hover multiple-select-row data-table-export1 nowrap p-3" data-title="Brand Description List">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Logo</th>
                            <th>Status</th>
                            <th class="no-export">Edit</th>
                            <th class="no-export">Delete</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($brandDescriptions as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>

                                <td>{{ $item->title ?? '-' }}</td>

                                <td>{{ \Illuminate\Support\Str::limit($item->description, 50) }}</td>

                                <td>
                                    <img src="{{ asset('storage/' . $item->logo) }}" class="seo-img" style="width: 180px !important;" alt="Brand Logo">
                                </td>

                                <td>
                                    @if($item->status == 'active')
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-warning">Inactive</span>
                                    @endif
                                </td>

                                <td class="no-export">
                                    <a href="{{ route('brand-description.edit', $item->id) }}">
                                        <button class="btn btn-warning btn-sm">
                                            <i class="micon dw dw-pencil-1"></i> Edit
                                        </button>
                                    </a>
                                </td>

                                <td class="no-export">
                                    <form action="{{ route('brand-description.destroy', $item->id) }}" method="POST">
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
<script src="{{ asset('backend/assets/datatable/js/datatable-init.js') }}" defer></script>
@endpush