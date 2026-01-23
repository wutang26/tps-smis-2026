@extends('layouts.main')

@section('scrumb')
<nav class="navbar navbar-expand-lg bg-body-tertiary bscrumb">
  <div class="container-fluid">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" id="homee">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Companies</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="#">Companies Lists</a></li>
      </ol>
    </nav>
  </div>
</nav>
@endsection

@section('content')
@include('layouts.sweet_alerts.index')
<div class="row gx-4">
  <div class="col-sm-8">
    <div class="card mb-3">
      <div class="card-header"></div>
      <div class="pull-right">
        <a class="btn btn-success mb-2" href="{{ route('companies.create') }}" style="float:right !important; margin-right:1%">
          <i class="fa fa-plus"></i> Create New Company
        </a>
      </div>
      <div class="card-body">
        <div class="table-outer">
          <div class="table-responsive">
            <table class="table table-striped truncate m-0">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Company Name</th>
                  <th width="280px">Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($companies as $key => $company)
                <tr class="company-row" data-id="{{ $company->id }}" style="cursor: pointer;">
                  <td>{{ $loop->iteration }}.</td>
                  <td>{{ $company->description }}</td>
                  <td>
                    <a class="btn btn-info btn-sm" href="{{ route('companies.show',$company->id) }}">
                      <i class="fa-solid fa-list"></i> Show
                    </a>
                    <a class="btn btn-primary btn-sm" href="{{ route('companies.edit',$company->id) }}">
                      <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>
                    <form id="deleteForm{{ $company->id }}" method="POST" action="{{ route('companies.destroy', $company->id) }}" style="display:inline">
                      @csrf
                      @method('DELETE')
                      <button type="button" onclick="confirmDelete('deleteForm{{ $company->id }}','Company')" class="btn btn-danger btn-sm">
                        <i class="fa-solid fa-trash"></i> Delete
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
    </div>
  </div>

  <div class="col-sm-4">
    <div class="card-header"></div>
    <div class="pull-right">
      <button id="addPlatoonBtn" class="btn btn-success mb-3" disabled style="float:right !important; margin-right:1%">
        <i class="fa fa-plus"></i> New Platoon
      </button>
    </div><br>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped" id="platoon-table">
          <thead>
            <tr>
              <th>Name</th>
            </tr>
          </thead>
          <tbody id="platoon-table-body">
            <tr><td colspan="3">Click a company to load platoons</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="platoonModal" tabindex="-1" aria-labelledby="platoonModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      
      <form id="platoonForm" method="POST">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="platoonModalLabel">Create New Platoon</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="platoonName" class="form-label">Platoon Name</label>
              <input type="text" class="form-control" id="platoonName" name="name" required>
            </div>
            <input type="hidden" name="company_id" id="modal-company-id">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="savePlatoon">Save Platoon</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- JavaScript -->
<script>
  let selectedCompanyId = null;

  document.getElementById('addPlatoonBtn').addEventListener('click', function () {
    if (!selectedCompanyId) return;

    // Set the hidden input value for company_id
    document.getElementById('modal-company-id').value = selectedCompanyId;

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('platoonModal'));
    modal.show();
  });

  document.querySelectorAll('.company-row').forEach(function (row) {
    row.addEventListener('click', function () {
      selectedCompanyId = this.dataset.id;

      document.getElementById('addPlatoonBtn').disabled = false;

      const tableBody = document.getElementById('platoon-table-body');
      tableBody.innerHTML = '<tr><td colspan="3">Loading...</td></tr>';

      fetch('/tps-smis/platoons/' + selectedCompanyId)
        .then(response => response.json())
        .then(platoons => {
          tableBody.innerHTML = '';

          if (platoons.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="3">No platoons found.</td></tr>`;
          } else {
            platoons.forEach((platoon) => {
              const formAction ="/tps-smis/companies/"+platoon.id+"/platoon/delete";
              const platoonId = platoon.id;
              //alert(formAction)
              const row = document.createElement('tr');
              row.innerHTML = `
                <td>${platoon.name}</td>
                <td>
                  <form id="deletePlatoon${platoonId}" method="get" action="${formAction}">
                    @csrf
                    <input type="hidden" name="_method" value="GET">
                    <button type="button" onclick="confirmDelete('deletePlatoon${platoonId}','Platoon')" class="btn btn-danger delete-button">Delete</button>
                  </form>
                </td>
              `;
              tableBody.appendChild(row);
            });
          }
        })
        .catch(error => {
          console.error('Error:', error);
          tableBody.innerHTML = `<tr>
            <td colspan="3" class="text-danger">Error loading platoons.</td>
          </tr>`;
        });
    });
  });

    document.getElementById('savePlatoon').addEventListener('click', () => {
    const form = document.getElementById('platoonForm');
    const companyId = document.getElementById('modal-company-id').value;
    // Dynamically set the form's action
    form.action = `/tps-smis/companies/${companyId}/store/platoon`; // adjust URL pattern as needed

    // Optionally, validate form with custom logic
    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }

    // Submit form via JS
    form.submit();
  });
</script>
@endsection
