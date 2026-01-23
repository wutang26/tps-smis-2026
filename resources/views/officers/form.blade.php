<div class="mb-3">
    <label class="form-label">Officer ID</label>
    <input type="text" name="officer_id" class="form-control" value="{{ old('officer_id', $officer->officer_id ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Service Number</label>
    <input type="text" name="service_number" class="form-control" value="{{ old('service_number', $officer->service_number ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Full Name</label>
    <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $officer->full_name ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Rank</label>
    <input type="text" name="rank" class="form-control" value="{{ old('rank', $officer->rank ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Contact Number</label>
    <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number', $officer->contact_number ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control" value="{{ old('email', $officer->email ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Status</label>
    <select name="status" class="form-control">
        <option value="Active" {{ (old('status', $officer->status ?? '') == 'Active') ? 'selected' : '' }}>Active</option>
        <option value="Inactive" {{ (old('status', $officer->status ?? '') == 'Inactive') ? 'selected' : '' }}>Inactive</option>
        <option value="Transferred" {{ (old('status', $officer->status ?? '') == 'Transferred') ? 'selected' : '' }}>Transferred</option>
    </select>
</div>
