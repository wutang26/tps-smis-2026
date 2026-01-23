@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/tps-rms" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="">Beats</a></li>
        <li class="breadcrumb-item active"><a href="">Guards and Patrols</a></li>
      </ol>
    </nav>
  </div>
</nav>
<!-- Scrumb ends -->

@endsection
@section('content')
  <!-- @session('success')
    <div class="alert alert-success" role="alert">
    {{ $value }}
    </div>
  @endsession -->

    @if(session('success'))
        <div style="color: green">{{ session('success') }}</div>
    @endif

<div class="container">


<h2>Beats for {{ $date ?? ''}}</h2>
    
    <form action="{{ route('beats.byDate') }}" method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <input type="date" name="date" class="form-control" value="{{ $date }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>



    <ul class="nav nav-tabs" id="companyTabs" role="tablist">
        @foreach($companies as $company)
            <li class="nav-item" role="presentation">
                <button class="nav-link @if($loop->first) active @endif" id="tab-{{ $company->id }}" data-bs-toggle="tab" data-bs-target="#company-{{ $company->id }}" type="button" role="tab" aria-controls="company-{{ $company->id }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                    {{ $company->description}} 
                </button>
            </li>
        @endforeach
    </ul>
    <div class="tab-content" id="companyTabContent">
        @foreach($companies as $company)
            <div class="tab-pane fade @if($loop->first) show active @endif" id="company-{{ $company->id }}" role="tabpanel" aria-labelledby="tab-{{ $company->id }}">
                <h3>Guard Areas</h3>
                @foreach($company->guardAreas as $area)
                    <div class="card my-3">
                        <div class="card-header">
                            {{ $area->name }}
                        </div>
                        <div class="card-body">
                            @foreach($area->beats as $beat)
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <strong>Beat Date:</strong> {{ $beat->date }}
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Start Time:</strong> {{ $beat->start_at }}
                                    </div>
                                    <div class="col-md-2">
                                        <strong>End Time:</strong> {{ $beat->end_at }}
                                    </div>
                                    <div class="col-md-3">
                                        <a href="{{ route('beats.show', ['beat' => $beat->id]) }}" class="btn btn-primary">View Students</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <h3>Patrol Areas</h3>
                @foreach($company->patrolAreas as $area)
                    <div class="card my-3">
                        <div class="card-header">
                            {{ $area->start_area }} -  {{ $area->end_area }}
                        </div>
                        <div class="card-body">
                            @foreach($area->beats as $beat)
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <strong>Beat Date:</strong> {{ $beat->date }}
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Start Time:</strong> {{ $beat->start_at }}
                                    </div>
                                    <div class="col-md-2">
                                        <strong>End Time:</strong> {{ $beat->end_at }}
                                    </div>
                                    <div class="col-md-3">
                                        <a href="{{ route('beats.show', ['beat' => $beat->id]) }}" class="btn btn-primary">View Students</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
        
    @if($companies->isEmpty())
        <p>No beats found for the selected date.</p>
    @endif
    </div>
</div>
@endsection