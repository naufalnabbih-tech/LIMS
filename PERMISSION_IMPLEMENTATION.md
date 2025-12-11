# Sample Actions Dropdown - Permission Implementation Guide

## Overview
Implementasi permission control untuk action buttons di sample dropdown dengan aturan yang sangat spesifik.

## Permission Rules

### 1. **View Details**
- **Permission**: Semua user bisa view (no restriction)
- **Implementation**: Tidak perlu `@can()` check

### 2. **Edit Sample**
- **Permission**: Hanya user yang submit sample atau analyst yang ditugaskan
- **Logic**:
  ```php
  $canEdit = auth()->id() === $sample->submitted_by
          || auth()->id() === $sample->primary_analyst_id
          || auth()->id() === $sample->secondary_analyst_id;
  ```
- **Implementation**:
  ```blade
  @if($canEdit)
      <button>Edit Sample</button>
  @endif
  ```

### 3. **Start Analysis / Continue Analysis**
- **Permission**: Hanya analyst yang ditugaskan untuk sample tersebut
- **Logic**:
  ```php
  $canAnalyze = auth()->id() === $sample->primary_analyst_id
             || auth()->id() === $sample->secondary_analyst_id;
  ```

### 4. **Review Results**
- **Permission**: User dengan permission `review_samples`
- **Status**: Sample harus dalam status `analysis_completed`, `review`, atau `reviewed`
- **Implementation**:
  ```blade
  @can('review_samples')
      @if(in_array($sample->status->name, ['analysis_completed', 'review', 'reviewed']))
          <button>Review Results</button>
      @endif
  @endcan
  ```

### 5. **Approve Sample**
- **Permission**: User dengan permission `approve_samples`
- **Status**: Sample harus dalam status `reviewed`
- **Additional Rule**: User yang review tidak boleh approve (different person principle)
- **Logic**:
  ```php
  $canApprove = auth()->user()->can('approve_samples')
             && $sample->status->name === 'reviewed'
             && auth()->id() !== $sample->reviewed_by;
  ```
- **Implementation**:
  ```blade
  @can('approve_samples')
      @if($sample->status->name === 'reviewed' && auth()->id() !== $sample->reviewed_by)
          <button>Approve Sample</button>
      @endif
  @endcan
  ```

### 6. **Create CoA**
- **Permission**: User dengan permission `create_coa`
- **Status**: Sample harus `approved`
- **Implementation**:
  ```blade
  @can('create_coa')
      @if($sample->status->name === 'approved')
          <button>Create CoA</button>
      @endif
  @endcan
  ```

### 7. **Delete Sample**
- **Permission**: Hanya admin (user dengan permission `manage_samples` atau role admin)
- **Implementation**:
  ```blade
  @can('manage_samples')
      <button>Delete Sample</button>
  @endcan
  ```

  atau

  ```blade
  @if(auth()->user()->role->name === 'admin')
      <button>Delete Sample</button>
  @endif
  ```

### 8. **Hand Over / Take Over**
- **Permission**: Analyst yang ditugaskan (untuk Hand Over) atau analyst yang menerima (untuk Take Over)
- **Logic**: Sudah dihandle oleh `canHandOver` dan `canTakeOver` flags

## Implementation Steps

### Step 1: Update RoleSeeder
Pastikan permissions sudah ada di database:
```php
'review_samples',
'approve_samples',
'create_coa',
'manage_samples',
```

### Step 2: Create Helper Methods di Sample Model
```php
// app/Models/Sample.php

public function canBeEditedBy($userId)
{
    return $this->submitted_by === $userId
        || $this->primary_analyst_id === $userId
        || $this->secondary_analyst_id === $userId;
}

public function canBeReviewedBy($userId)
{
    return User::find($userId)->can('review_samples')
        && in_array(strtolower($this->status->name), ['analysis_completed', 'review', 'reviewed']);
}

public function canBeApprovedBy($userId)
{
    return User::find($userId)->can('approve_samples')
        && strtolower($this->status->name) === 'reviewed'
        && $this->reviewed_by !== $userId;
}
```

### Step 3: Update Dropdown Blade File
Tambahkan permission checks di setiap button section.

### Step 4: Testing Checklist
- [ ] Admin bisa delete sample
- [ ] User biasa tidak bisa delete sample
- [ ] User dengan permission `review_samples` bisa review
- [ ] User dengan permission `approve_samples` bisa approve (tapi bukan reviewer yang sama)
- [ ] User yang submit sample bisa edit
- [ ] Analyst yang ditugaskan bisa edit
- [ ] User lain tidak bisa edit sample yang bukan miliknya

## Security Notes
1. Permission checking harus dilakukan di **backend (Livewire method)** juga, tidak hanya di frontend
2. Frontend check hanya untuk UX (hide/show button)
3. Backend check untuk security (prevent unauthorized action)

## Example Backend Method Protection
```php
// app/Livewire/SampleChemicalSubmission.php

public function deleteSample($sampleId)
{
    // Backend permission check
    if (!auth()->user()->can('manage_samples')) {
        session()->flash('error', 'Unauthorized action.');
        return;
    }

    $sample = Sample::findOrFail($sampleId);
    $sample->delete();

    session()->flash('message', 'Sample deleted successfully.');
    $this->loadSamples();
}

public function approveSample($sampleId)
{
    $sample = Sample::findOrFail($sampleId);

    // Backend permission check
    if (!auth()->user()->can('approve_samples')) {
        session()->flash('error', 'You do not have permission to approve samples.');
        return;
    }

    // Check reviewer tidak sama dengan approver
    if ($sample->reviewed_by === auth()->id()) {
        session()->flash('error', 'You cannot approve a sample you reviewed.');
        return;
    }

    // Check status
    if (strtolower($sample->status->name) !== 'reviewed') {
        session()->flash('error', 'Sample must be reviewed before approval.');
        return;
    }

    // Proceed with approval
    $sample->approved_by = auth()->id();
    $sample->approved_at = now();
    $sample->save();

    session()->flash('message', 'Sample approved successfully.');
}
```
