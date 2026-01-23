<div class="mb-3">
    <label class="form-label">Movement ID</label>
    <input type="text" name="movement_id" class="form-control" value="{{ old('movement_id', $movement->movement_id ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Weapon</label>
    <select name="weapon_id" class="form-control">
        @foreach($weapons as $weapon)
            <option value="{{ $weapon->id }}" {{ (old('weapon_id', $movement->weapon_id ?? '') == $weapon->id) ? 'selected' : '' }}>
                {{ $weapon->make_model }} ({{ $weapon->weapon_id }})
            </option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label class="form-label">Movement Type</label>
    <select name="movement_type" class="form-control">
        @foreach(['Issue', 'Return', 'Transfer', 'Maintenance Out', 'Maintenance In'] as $type)
            <option value="{{ $type }}" {{ (old('movement_type', $movement->movement_type ?? '') == $type) ? 'selected' : '' }}>{{ $type }}</option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label class="form-label">Purpose</label>
    <input type="text" name="purpose" class="form-control" value="{{ old('purpose', $movement->purpose ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Issue Date & Time</label>
    <input type="datetime-local" name="issue_date_time" class="form-control" value="{{ old('issue_date_time', isset($movement) ? date('Y-m-d\TH:i', strtotime($movement->issue_date_time)) : '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Issued By</label>
    <select name="issued_by_officer_id" class="form-control">
        @foreach($officers as $officer)
            <option value="{{ $officer->id }}" {{ (old('issued_by_officer_id', $movement->issued_by_officer_id ?? '') == $officer->id) ? 'selected' : '' }}>{{ $officer->full_name }}</option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label class="form-label">Issued To</label>
    <select name="issued_to_officer_id" class="form-control">
        @foreach($officers as $officer)
            <option value="{{ $officer->id }}" {{ (old('issued_to_officer_id', $movement->issued_to_officer_id ?? '') == $officer->id) ? 'selected' : '' }}>{{ $officer->full_name }}</option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label class="form-label">Return Date & Time</label>
    <input type="datetime-local" name="return_date_time" class="form-control" value="{{ old('return_date_time', isset($movement->return_date_time) ? date('Y-m-d\TH:i', strtotime($movement->return_date_time)) : '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Return Condition</label>
    <input type="text" name="return_condition" class="form-control" value="{{ old('return_condition', $movement->return_condition ?? '') }}">
</div>
<div class="mb-3">
    <label class="form-label">Remarks</label>
    <textarea name="remarks" class="form-control">{{ old('remarks', $movement->remarks ?? '') }}</textarea>
</div>
