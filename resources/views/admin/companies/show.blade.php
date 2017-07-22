@extends('layouts.admin')

@section('title', 'Eatvora - ' . $company->name)

@section('content')

  @php

    $mainOffice = $company->mainOffice();

    $admin = $mainOffice->admin()->user;

  @endphp

  <div class="app-content-container">
    <div class="container-fluid container-fluid--narrow">
      <div class="row">

        @include('admin.top-navigation', ['header' => $company->name])

        <div class="col-lg-12">
          <div class="action-panel" style="margin-bottom: 15px;">
            <a href="{{ url("/ap/companies/{$company->id}/offices/create") }}" class="btn btn-primary">
              <i class="glyphicon glyphicon-plus"></i> Create New Office
            </a>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="office-list">
            <div class="office-list__header">
              {{ $company->name }} Offices
            </div>
            @foreach($company->offices as $office)
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
                      {{ $office->address }}
                    </div>
                    <div class="office__address-district">
                      Slipi, Palmerah
                    </div>
                    <div class="office__address-city">
                      Jakarta Barat
                    </div>
                  </div>
                </div>
                <div class="office__contact">
                  @if($office->email)
                  <div class="office__contact-email">
                    <i class="icon-envelope"></i> something@mail.com
                  </div>
                  @endif
                  @if($office->phone)
                  <div class="office__contact-phone">
                    <div class="icon-phone"></div> +62 852 5852
                  </div>
                  @endif
                </div>
                <div class="office__action">
                  @if(!$office->is_main)
                  <a href="#" class="text-danger">Delete</a>
                  @endif
                  <div class="pull-right">
                    <a href="{{ $office->is_main ? url('/ap/companies/' . $company->id . '/edit') : '#' }}">Edit</a>
                    <employee-list-button
                      :office-id="{{ $office->id }}"
                      :office-name="'{{ $office->name }}'"
                    ></employee-list-button>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>

        <div class="col-md-offset-1 col-md-6">
          <employee-list
            :default-office-id="{{ $mainOffice->id }}"
            default-office-name="{{ $mainOffice->name }}"
          ></employee-list>
        </div>

      </div>
    </div>
  </div>

@endsection

@push('afterScripts')
  <script src="/js/admin/admin.js"></script>
@endpush
