@extends('backend.layouts.master')

@section('title')
Manage Social Links
@endsection

@push('styles')
{{-- common styles --}}
<link rel="stylesheet" href="{{ asset('backend/assets/datatable/css/dataTables-responsive.css') }}">
@endpush

@section('content')
<div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">

        <!-- Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-md-6">
                    <h4>Manage Social Links</h4>
                </div>
                <div class="col-md-6 text-right">
                    <a class="btn btn-primary" href="{{ route('social-links.create') }}">
                        <i class="fa fa-plus"></i> Add Social Link
                    </a>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="card-box mb-30">
            <div class="pd-20">
                <h4 class="text-blue h4">All Social Records</h4>
            </div>

            <div class="pb-20">
                <table class="table hover multiple-select-row data-table-export1 nowrap p-3" data-title="Social Links List">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Platform</th>
                            <th>Icon</th>
                            <th>URL</th>
                            <th>Status</th>
                            <th class="no-export">Edit</th>
                            <th class="no-export">Delete</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($socialLinks as $key => $social)
                        <tr>
                            <td>{{ $key + 1 }}</td>

                            <td>{{ $social->platform ?? '-' }}</td>

                            {{-- Icon --}}
                            <td>
                                {{ $social->icon ?? '-' }}
                            </td>

                            {{-- URL --}}
                            <td>
                                <a href="{{ $social->url }}" target="_blank">
                                    {{ \Illuminate\Support\Str::limit($social->url, 30) }}
                                </a>
                            </td>

                            {{-- Status --}}
                            <td>
                                @if($social->status == 'active')
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-warning">Inactive</span>
                                @endif
                            </td>

                            <!-- Edit -->
                            <td>
                                <a href="{{ route('social-links.edit', $social->id) }}">
                                    <button class="btn btn-warning btn-sm">
                                        <i class="micon dw dw-pencil-1"></i> Edit
                                    </button>
                                </a>
                            </td>

                            <!-- Delete -->
                            <td>
                                <form action="{{ route('social-links.destroy', $social->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure?')">
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