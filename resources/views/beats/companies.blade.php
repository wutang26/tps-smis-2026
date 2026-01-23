@extends('layouts.main')

@section('scrumb')
<!-- Scrumb starts -->
<nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
                <li class="breadcrumb-item active"><a href="/tps-smis/beats/companies/{{$beatType_id}}">
                Company    
                @if ($beatType_id == 1)
                    guards
                @else
                    patrol
                    @endif
                 </a></li>
            </ol>
        </nav>
    </div>
</nav>
<!-- Scrumb ends -->

@endsection
@section('content')
<div class="table-responsive">
    <table class="table table-striped truncate m-0">
    <thead>
    <?php  $i = 0;?>
      <tr>
      <th></th>
      <th>Company Name</th>
      <th width="280px">Actions</th>
      </tr>
    </thead>
    <tbody>
            @foreach ($companies as $company)
                <tr>
                    <td>{{++$i}}</td>
                    <td>{{$company->name}} </td>
                    <td>
                        @if ($beatType_id == 1)
                        <a href="{{url('/beats/companies/' . $company->id .'/areas')}}"><button class="btn btn-sm btn-info">View</button></a>
                        @else
                        <a href="{{url('/beats/companies/' . $company->id .'/patrol_areas')}}"><button class="btn btn-sm btn-info">View</button></a>
                        @endif
                        
                    </td>
                </tr>
            @endforeach
    </tbody>
    </table>
    </div>
@endsection