# CoA Implementation - Quick Setup Checklist

## ✅ Completed for You

- [x] Created `app/Models/CoA.php` - Complete model with relationships and helpers
- [x] Created `database/migrations/2025_12_09_create_coas_table.php` - Database table
- [x] Created `app/Livewire/CoAManagement.php` - Full Livewire component
- [x] Created `resources/views/livewire/coa-management.blade.php` - Management interface
- [x] Added "Create CoA" button to Chemical sample dropdown
- [x] Added "Create CoA" button to Solder sample dropdown
- [x] Updated dropdown JavaScript for CoA permissions

## ⚠️ TODO - You Must Do These Steps

### Step 1: Update Sample Model
**File**: `app/Models/Sample.php`

Add this relationship to the Sample model:
```php
public function coas()
{
    return $this->hasMany(\App\Models\CoA::class);
}
```

### Step 2: Run Database Migration
Execute in terminal:
```bash
php artisan migrate
```

### Step 3: Add CoA Permissions to Role Management
**File**: `app/Livewire/RoleManagement.php`

Find the `availablePermissions` array and add this under a new 'CoA Management' section:
```php
'coa_management' => [
    'create_coa' => 'Create CoA',
    'edit_coa' => 'Edit CoA',
    'approve_coa' => 'Approve CoA',
    'release_coa' => 'Release CoA',
    'delete_coa' => 'Delete CoA',
],
```

### Step 4: Update Role Management Template
**File**: `resources/views/livewire/role-management.blade.php`

Add the CoA Management group to the permissions array in the form. Find where other permission groups are defined (like 'Sample Management') and add:
```php
'coa_management' => 'CoA Management',
```

### Step 5: Ensure Route Exists
**File**: `routes/web.php`

Add if not using auto-discovery:
```php
Route::get('/coa-management', \App\Livewire\CoAManagement::class)->middleware('auth');
```

### Step 6: Create Menu Item (Optional)
Add navigation link to your main menu/sidebar pointing to `/coa-management`

## 🧪 Testing the Implementation

1. **Run Migration**:
   ```bash
   php artisan migrate
   ```

2. **Test CoA Creation**:
   - Go to a sample with status "approved"
   - Click the action dropdown
   - Click "Create CoA"
   - Fill in the form and save

3. **Test CoA Management Page**:
   - Navigate to `/coa-management`
   - See your created CoAs
   - Test filters and actions

4. **Test Permissions**:
   - Assign CoA permissions to a role
   - Log in as a user with that role
   - Verify you can/cannot perform restricted actions

## 📝 File Locations

```
app/Models/
├── CoA.php .......................... NEW - CoA Model

database/migrations/
├── 2025_12_09_create_coas_table.php  NEW - CoA Table

app/Livewire/
├── CoAManagement.php ............... NEW - Management Component
└── SampleChemicalSubmission.php .... MODIFIED - Added openCoAForm()
└── SampleSolderSubmission.php ...... MODIFIED - Added openCoAForm()

resources/views/livewire/
├── coa-management.blade.php ........ NEW - Management View
├── sample-chemical-submission/components/
│   └── sample-actions-dropdown.blade.php ... MODIFIED - Added CoA button
└── sample-solder-submission/components/
    └── sample-actions-dropdown.blade.php ... MODIFIED - Added CoA button

app/Livewire/RoleManagement.php ... TO MODIFY - Add permissions
resources/views/livewire/role-management.blade.php ... TO MODIFY - Add form group

routes/web.php ..................... TO MODIFY - Add route (if needed)
```

## 🎯 Key Features Implemented

✅ Complete CRUD operations for CoA
✅ Status workflow (Draft → Pending → Approved → Released)
✅ Permission-based access control
✅ Auto-document numbering (COA-YYMM-xxxx)
✅ Integration with sample actions dropdown
✅ Search and filtering capabilities
✅ Modal-based forms
✅ Status tracking with timestamps

## 🚀 After Setup Complete

Users can:
1. Create CoAs directly from sample actions when sample is approved
2. Manage all CoAs from dedicated management page
3. Filter by document number, status, and date range
4. View full details of any CoA
5. Approve/Release CoAs based on permissions
6. Track who approved/released and when

## ❓ Questions & Troubleshooting

**Q: Migration fails?**
A: Make sure you ran `php artisan migrate` and have proper database setup

**Q: "Create CoA" button doesn't appear?**
A: Check that sample status is exactly "approved" (case-sensitive in JavaScript)

**Q: Permission checks not working?**
A: Verify you added permissions to RoleManagement.php and assigned them to roles

**Q: Page returns 404?**
A: Ensure route is registered in `routes/web.php` or using auto-discovery

## ✨ Next Steps (Optional)

Once basic setup is done, consider:
- Adding PDF generation for CoA documents
- Email notifications on approval/release
- Digital signature fields
- Change history tracking
- Bulk operations
- Document templates per material type
