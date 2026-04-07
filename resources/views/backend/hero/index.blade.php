@extends('backend.layouts.master')

@section('title')
Manage Hero Section
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
                    <h4>Manage Hero Section</h4>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <a class="btn btn-primary" href="{{ route('hero.create') }}">
                        <i class="fa fa-plus"></i> Add Hero
                    </a>
                </div>
            </div>
        </div>

        <!-- Hero Table -->
        <div class="card-box mb-30">
            <div class="pd-20">
                <h4 class="text-blue h4">All Hero Records</h4>
            </div>

            <div class="pb-20">
                <table class="table hover multiple-select-row data-table-export1 nowrap p-3" data-title="Hero Section List">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Name</th>
                            <th>Subtitle</th>
                            <th>Profile Image</th>
                            <th>Background Image</th>
                            <th>Typed Items</th>
                            <th>Status</th>
                            <th class="no-export">Edit</th>
                            <th class="no-export">Delete</th>
                        </tr>
                    </thead>
                    @php use Illuminate\Support\Str; @endphp

                    <tbody>
                        @foreach ($heroes as $key => $hero)
                            <tr>
                                <td>{{ $key + 1 }}</td>

                                <td>{{ $hero->name ?? '-' }}</td>

                                <td>{{ Str::limit($hero->subtitle, 30) }}</td>

                                {{-- Profile Image --}}
                                <td>
                                    <img src="{{ $hero->profile_image }}" class="seo-img">
                                </td>

                                {{-- Background Image --}}
                                <td>
                                    @if($hero->background_image)
                                        @if(Str::endsWith($hero->getRawOriginal('background_image'), '.mp4'))

                                            {{-- ✅ VIDEO --}}
                                            <video width="120" height="60"
                                                autoplay muted loop playsinline
                                                style="border-radius:6px; object-fit:cover;">
                                                <source src="{{ asset('storage/' . $hero->getRawOriginal('background_image')) }}" type="video/mp4">
                                            </video>

                                        @else

                                            {{-- ✅ IMAGE --}}
                                            <img src="{{ $hero->background_image }}" class="seo-img">

                                        @endif
                                    @endif
                                </td>

                                {{-- Typed Items --}}
                                <td>
                                    @foreach($hero->typed_items ?? [] as $item)
                                        <span class="badge badge-primary">{{ $item }}</span>
                                    @endforeach
                                </td>

                                {{-- Status --}}
                                <td>
                                    @if($hero->status == 'active')
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-warning">Inactive</span>
                                    @endif
                                </td>

                                <!-- EDIT -->
                                <td class="no-export">
                                    <a href="{{ route('hero.edit', $hero->id) }}">
                                        <button class="btn btn-warning btn-sm">
                                            <i class="micon dw dw-pencil-1"></i> Edit
                                        </button>
                                    </a>
                                </td>

                                <!-- DELETE -->
                                <td class="no-export">
                                    <form action="{{ route('hero.destroy', $hero->id) }}" method="POST">
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