# TESTING HANDOVER FLOW - LIMS PROJECT
**Date:** November 26, 2025

## Prerequisites

1. ✅ MySQL running (start via Laragon)
2. ✅ Database migrated: `php artisan migrate:fresh`
3. ✅ StatusSeeder sudah run dengan status "Hand Over"

---

## TEST 1: Run StatusSeeder

```bash
php artisan db:seed --class=StatusSeeder
```

**Expected Result:**
- Status "hand_over" created with color orange
- No errors

**Verify:**
```bash
php artisan tinker
```
```php
use App\Models\Status;
Status::where('name', 'hand_over')->first();
// Should return Status object with display_name "Hand Over"
```

---

## TEST 2: Test Models via Tinker

```bash
php artisan tinker
```

### A. Test Category Model

```php
use App\Models\Category;

// Create categories
$catRaw = Category::create(['type' => 'raw_material', 'name' => 'Flour']);
$catSolder = Category::create(['type' => 'solder', 'name' => 'Lead-Free Solder']);

// Test scopes
Category::rawMaterial()->get(); // Should return only raw_material
Category::solder()->get();      // Should return only solder

// Test relations
$catRaw->materials;  // Empty collection
```

### B. Test Material Model

```php
use App\Models\Material;

// Create material
$mat = Material::create(['name' => 'All Purpose Flour', 'category_id' => $catRaw->id]);

// Test relations
$mat->category;      // Return Category object
$mat->references;    // Empty collection
$mat->samples;       // Empty collection
```

### C. Test Reference Model

```php
use App\Models\Reference;

// Create reference
$ref = Reference::create(['name' => 'Standard Reference', 'material_id' => $mat->id]);

// Test relations
$ref->material;         // Return Material object
$ref->specifications;   // Empty collection

// Test helper
$ref->getSpecValue(1);  // Return null (no spec attached yet)
```

### D. Test Sample Model

```php
use App\Models\{Sample, Status, User};

// Get status and user
$pendingStatus = Status::where('name', 'pending')->first();
$user = User::first();

// Create sample
$sample = Sample::create([
    'sample_type' => 'raw_material',
    'category_id' => $catRaw->id,
    'material_id' => $mat->id,
    'reference_id' => $ref->id,
    'supplier' => 'Test Supplier Inc.',
    'batch_lot' => 'BATCH-2025-001',
    'vehicle_container_number' => 'CONT-12345',
    'has_coa' => false,
    'submission_time' => now(),
    'entry_time' => now(),
    'submitted_by' => $user->id,
    'status_id' => $pendingStatus->id,
    'notes' => 'Test sample for handover flow'
]);

// Test scopes
Sample::rawMaterial()->count(); // Should be 1
Sample::solder()->count();      // Should be 0

// Test helpers
$sample->isPending();           // true
$sample->isInAnalysis();        // false
$sample->hasActiveHandover();   // false
```

### E. Test SampleHandover Model

```php
use App\Models\SampleHandover;

// Update sample to in_progress first
$inProgressStatus = Status::where('name', 'in_progress')->first();
$sample->update([
    'status_id' => $inProgressStatus->id,
    'primary_analyst_id' => $user->id,
    'analysis_started_at' => now()
]);

// Get another user for handover
$toUser = User::where('id', '!=', $user->id)->first();

// Create handover
$handover = SampleHandover::create([
    'sample_id' => $sample->id,
    'from_analyst_id' => $user->id,
    'to_analyst_id' => $toUser->id,
    'reason' => 'Shift ended',
    'notes' => 'Please continue analysis',
    'handed_over_at' => now(),
    'handed_over_by' => $user->id,
    'status' => 'pending'
]);

// Test scopes
SampleHandover::pending()->count();         // Should be 1
SampleHandover::forUser($toUser->id)->get(); // Should return 1 handover

// Test helpers
$handover->isPending();     // true
$handover->isCompleted();   // false

// Test sample helpers
$sample->hasActiveHandover();   // true
$sample->getActiveHandover();   // Return handover object
```

---

## TEST 3: Test Handover Flow (Full Cycle)

### Step 1: Create Sample

```php
use App\Models\{Sample, Status, User, Category, Material, Reference};

// Setup
$user1 = User::find(1); // Primary analyst
$user2 = User::find(2); // Secondary analyst

$category = Category::create(['type' => 'raw_material', 'name' => 'Sugar']);
$material = Material::create(['name' => 'White Sugar', 'category_id' => $category->id]);
$reference = Reference::create(['name' => 'Ref Sugar', 'material_id' => $material->id]);

$pendingStatus = Status::where('name', 'pending')->first();

$sample = Sample::create([
    'sample_type' => 'raw_material',
    'category_id' => $category->id,
    'material_id' => $material->id,
    'reference_id' => $reference->id,
    'supplier' => 'Sweet Corp',
    'batch_lot' => 'SUGAR-001',
    'vehicle_container_number' => 'VEH-001',
    'has_coa' => false,
    'submission_time' => now(),
    'entry_time' => now(),
    'submitted_by' => $user1->id,
    'status_id' => $pendingStatus->id
]);

echo "✅ Sample created with ID: " . $sample->id . "\n";
echo "✅ Status: " . $sample->status->display_name . "\n";
```

### Step 2: Start Analysis

```php
$inProgressStatus = Status::where('name', 'in_progress')->first();

$sample->update([
    'status_id' => $inProgressStatus->id,
    'primary_analyst_id' => $user1->id,
    'analysis_method' => 'individual',
    'analysis_started_at' => now()
]);

echo "✅ Analysis started by: " . $sample->primaryAnalyst->name . "\n";
echo "✅ Status: " . $sample->status->display_name . "\n";
```

### Step 3: Submit to Hand Over

```php
use App\Models\SampleHandover;

$handOverStatus = Status::where('name', 'hand_over')->first();

// Create handover record
$handover = SampleHandover::create([
    'sample_id' => $sample->id,
    'from_analyst_id' => $user1->id,
    'to_analyst_id' => $user2->id,
    'reason' => 'Shift change',
    'notes' => 'Continue from step 3',
    'handed_over_at' => now(),
    'handed_over_by' => $user1->id,
    'status' => 'pending'
]);

// Update sample status
$sample->update([
    'status_id' => $handOverStatus->id
]);

echo "✅ Handover created with ID: " . $handover->id . "\n";
echo "✅ From: " . $handover->fromAnalyst->name . "\n";
echo "✅ To: " . $handover->toAnalyst->name . "\n";
echo "✅ Sample status: " . $sample->status->display_name . "\n";
echo "✅ Handover status: " . $handover->status . "\n";
```

### Step 4: Take Over (by user2)

```php
// Simulate user2 taking over
$handover->update([
    'status' => 'completed',
    'taken_at' => now(),
    'taken_by' => $user2->id
]);

$sample->update([
    'status_id' => $inProgressStatus->id,
    'primary_analyst_id' => $user2->id
]);

echo "✅ Sample taken over by: " . $sample->primaryAnalyst->name . "\n";
echo "✅ Sample status: " . $sample->status->display_name . "\n";
echo "✅ Handover status: " . $handover->status . "\n";
echo "✅ Taken at: " . $handover->taken_at->format('Y-m-d H:i:s') . "\n";
```

### Step 5: Verify Complete Flow

```php
// Check sample
echo "\n=== SAMPLE INFO ===\n";
echo "ID: " . $sample->id . "\n";
echo "Type: " . $sample->sample_type . "\n";
echo "Status: " . $sample->status->display_name . "\n";
echo "Primary Analyst: " . $sample->primaryAnalyst->name . "\n";
echo "Has Active Handover: " . ($sample->hasActiveHandover() ? 'Yes' : 'No') . "\n";

// Check handover
echo "\n=== HANDOVER INFO ===\n";
echo "Status: " . $handover->status . "\n";
echo "From: " . $handover->fromAnalyst->name . "\n";
echo "To: " . $handover->toAnalyst->name . "\n";
echo "Handed over at: " . $handover->handed_over_at->format('Y-m-d H:i:s') . "\n";
echo "Taken at: " . $handover->taken_at->format('Y-m-d H:i:s') . "\n";
echo "Reason: " . $handover->reason . "\n";

// Check all handovers for sample
echo "\n=== ALL HANDOVERS ===\n";
foreach($sample->handovers as $h) {
    echo "- " . $h->fromAnalyst->name . " → " . $h->toAnalyst->name;
    echo " [" . $h->status . "]\n";
}
```

**Expected Output:**
```
✅ Sample created with ID: 1
✅ Status: Pending
✅ Analysis started by: User 1
✅ Status: In Progress
✅ Handover created with ID: 1
✅ From: User 1
✅ To: User 2
✅ Sample status: Hand Over
✅ Handover status: pending
✅ Sample taken over by: User 2
✅ Sample status: In Progress
✅ Handover status: completed
✅ Taken at: 2025-11-26 10:30:00

=== SAMPLE INFO ===
ID: 1
Type: raw_material
Status: In Progress
Primary Analyst: User 2
Has Active Handover: No

=== HANDOVER INFO ===
Status: completed
From: User 1
To: User 2
Handed over at: 2025-11-26 10:25:00
Taken at: 2025-11-26 10:30:00
Reason: Shift change

=== ALL HANDOVERS ===
- User 1 → User 2 [completed]
```

---

## TEST 4: Test Livewire Components (Browser)

### Prerequisites:
1. Login as Operator/Analyst
2. Go to `/sample-rawmat-submission`

### A. Test Create Sample

1. Klik button "Submit Sample"
2. Modal muncul
3. Pilih Category (raw_material type)
4. Pilih Material (cascade load)
5. Pilih Reference (cascade load)
6. Isi semua field required
7. Submit
8. **Expected:** Flash message success, sample muncul di tabel

### B. Test Start Analysis

1. Pada sample dengan status "Pending"
2. Klik Actions → "Start Analysis"
3. Modal muncul
4. Pilih Analysis Method (Individual/Joint)
5. Submit
6. **Expected:** Redirect ke analysis page, status jadi "In Progress"

### C. Test Submit to Hand Over

1. Pada sample dengan status "In Progress" (yang Anda sedang kerjakan)
2. Klik Actions → "Submit to Hand Over"
3. Modal muncul
4. Pilih operator tujuan
5. Isi reason (required)
6. Isi notes (optional)
7. Submit
8. **Expected:**
   - Flash message success
   - Sample hilang dari list
   - Status sample jadi "Hand Over"

### D. Test Take Over

1. Logout
2. Login sebagai operator yang ditunjuk (to_analyst)
3. Go to `/sample-rawmat-submission`
4. **Expected:**
   - Notification orange muncul di atas
   - "Sample Handover Menunggu Anda (1)"
   - Sample info tampil
5. Klik button "Ambil Sample"
6. **Expected:**
   - Flash message success
   - Notification hilang
   - Sample muncul di tabel dengan status "In Progress"
   - Primary analyst berubah jadi user yang take over

### E. Test My Handovers Info

1. Login sebagai user yang submit handover
2. Go to `/sample-rawmat-submission`
3. **Expected:**
   - Notification blue muncul
   - "Sample yang Anda Hand Over (1)"
   - Info sample tampil
   - Text "⏳ Menunggu diambil..."

---

## TEST 5: Test Solder (Same as Rawmat)

Repeat all tests above but:
- Use `/sample-solder-submission` route
- Select category with type "solder"
- All logic should work the same

---

## TROUBLESHOOTING

### Issue: "Class Category not found"
**Fix:** Clear cache
```bash
php artisan cache:clear
php artisan config:clear
composer dump-autoload
```

### Issue: "Status 'hand_over' not found"
**Fix:** Run StatusSeeder
```bash
php artisan db:seed --class=StatusSeeder
```

### Issue: "Column 'sample_type' not found"
**Fix:** Migration belum run
```bash
php artisan migrate:fresh
php artisan db:seed --class=StatusSeeder
```

### Issue: Livewire component not found
**Fix:** Verify file exists dan namespace correct
```bash
php artisan livewire:list
```

### Issue: Notification tidak muncul
**Check:**
1. `$pendingHandovers` di render() method
2. `@if($pendingHandovers && $pendingHandovers->count() > 0)` di blade
3. Query `where('to_analyst_id', auth()->id())`

---

## SUCCESS CRITERIA

✅ All models created without errors
✅ StatusSeeder run successfully
✅ Sample created with sample_type filter
✅ Handover created and status transitions correct
✅ Notifications display properly
✅ Take over button works
✅ Sample ownership transferred
✅ Both rawmat & solder work identically

---

## NEXT STEPS AFTER TESTING

1. ✅ Fix any bugs found during testing
2. ✅ Test with multiple samples
3. ✅ Test with multiple handovers per sample
4. ✅ Test edge cases (unauthorized take over, duplicate handover, etc.)
5. ✅ Write automated tests (PHPUnit/Pest)
6. ✅ Deploy to staging for UAT

---

**End of Testing Documentation**
