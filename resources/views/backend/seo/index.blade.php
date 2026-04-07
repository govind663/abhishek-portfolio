@extends('backend.layouts.master')

@section('title')
Manage SEO Settings
@endsection

@push('styles')
{{-- common styles --}}
<link rel="stylesheet" href="{{ asset('backend/assets/datatable/css/dataTables-responsive.css') }}">
@endpush

@section('content')
<div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <h4>Manage SEO Settings</h4>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <a class="btn btn-primary" href="{{ route('seo-settings.create') }}">
                        <i class="fa fa-plus"></i> Add SEO
                    </a>
                </div>
            </div>
        </div>

        <!-- SEO Table -->
        <div class="card-box mb-30">
            <div class="pd-20">
                <h4 class="text-blue h4">All SEO Records</h4>
            </div>

            <div class="pb-20">
                <table class="table hover multiple-select-row data-table-export1 nowrap p-3" data-title="SEO Settings List">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Page</th>
                            <th>Title</th>
                            <th>Meta Description</th>
                            <th>OG Image</th>
                            <th>Twitter Image</th>
                            <th>Status</th>
                            <th class="no-export">Edit</th>
                            <th class="no-export">Delete</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($seoSettings as $key => $seo)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $seo->page_name ?? '-' }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($seo->title, 30) }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($seo->description, 50) }}</td>

                                <td>
                                    <img src="{{ $seo->og_image_url }}" class="seo-img">
                                </td>

                                <td>
                                    <img src="{{ $seo->twitter_image_url }}" class="seo-img">
                                </td>

                                <td>
                                    @if($seo->status == 'active')
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-warning">Inactive</span>
                                    @endif
                                </td>

                                <!-- EDIT -->
                                <td class="no-export">
                                    <a href="{{ route('seo-settings.edit', $seo->id) }}">
                                        <button class="btn btn-warning btn-sm">
                                            <i class="micon dw dw-pencil-1"></i> Edit
                                        </button>
                                    </a>
                                </td>

                                <!-- DELETE -->
                                <td class="no-export">
                                    <form action="{{ route('seo-settings.destroy', $seo->id) }}" method="POST">
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