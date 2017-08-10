@extends('layouts.admin')

@section('title', 'Eatvora - ' . $company->name)

@section('content')

  @php

    $mainOffice = $company->mainOffice();

    $firstOffice = $offices->first();

    $firstOfficeId = !is_null($firstOffice) ? $firstOffice->id : 0;
    $firstOfficeName = !is_null($firstOffice) ? addslashes($firstOffice->name) : '';

    $admin = $mainOffice->admin()->user;

  @endphp

  <div class="app-content-container">
    <div class="container-fluid container-fluid--narrow">
      <div class="row">

        @include('admin.top-navigation', ['header' => $company->name])

        <div class="col-lg-12">
          <div class="action-panel">
            <a href="{{ url("/ap/companies/{$company->id}/offices/create") }}" class="btn btn-primary">
              <i class="glyphicon glyphicon-plus"></i> Create New Office
            </a>
            <a href="{{ url("/ap/companies/{$company->id}/employees") }}" class="btn btn-default">
              <i class="glyphicon glyphicon-user"></i> View All Employee
            </a>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="office-list">
            <div class="office-list__header">
              {{ $company->name }} Offices
            </div>
            @foreach($offices as $office)
              <div class="office-list__item office">
                <div class="office__name-wrapper">
                  <h2 class="office__name">{{ $office->name }}</h2>
                  @if($office->is_main)
                  <div class="office__name-badge">Main Office</div>
                  @endif
                </div>
                @if($office->is_main)
                <div class="office__admin">
                  <div class="office__section-header">
                    Administrator
                  </div>
                  <div class="office__admin-name">{{ $admin->name }}</div>
                  <div class="office__admin-email">{{ $admin->email }}</div>
                </div>
                @endif
                <div class="office__address">
                  <div class="office__section-header">
                    Address
                  </div>
                  <div class="office__address-content">
                    <div class="office__address-address">
                      {!! nl2br($office->address) !!}
                    </div>
                    <div class="office__address-district">
                      [Sub-District], [District]
                    </div>
                    <div class="office__address-city">
                      [City]
                    </div>
                  </div>
                </div>
                <div class="office__contact">
                  @if($office->email)
                  <div class="office__contact-email">
                    <i class="icon-envelope"></i> {{ $office->email}}
                  </div>
                  @endif
                  @if($office->phone)
                  <div class="office__contact-phone">
                    <div class="icon-phone"></div> {{ $office->phone }}
                  </div>
                  @endif
                </div>
                <div class="office__action">
                  @if(!$office->is_main)
                  <a href="#" class="text-danger">Delete</a>
                  @endif
                  <div class="pull-right">
                    <a href="{{ $office->is_main ? url('/ap/companies/' . $company->id . '/edit') : url('/ap/companies/' . $company->id . '/offices/' . $office->id . '/edit') }}">Edit</a>
                    <employee-list-button
                      :office-id="{{ $office->id }}"
                      :office-name="'{{ addslashes($office->name) }}'"
                    ></employee-list-button>
                  </div>
                </div>
              </div>
            @endforeach
            <div class="text-center">
              {{ $offices->render() }}
            </div>
          </div>
        </div>

        <div class="col-md-offset-1 col-md-7">
          <employee-list
            :default-office-id="{{ $firstOfficeId }}"
            default-office-name="{{ $firstOfficeName }}"
          ></employee-list>
        </div>

      </div>
    </div>
  </div>

@endsection

@section('modals')
  <company-show-page-modals></company-show-page-modals>
@endsection

@push('afterScripts')
  <script src="{{ mix('/js/manifest.js') }}"></script>
  <script src="{{ mix('/js/vendor.js') }}"></script>
  <script src="{{ mix('/js/admin/admin.js') }}"></script>
@endpush
