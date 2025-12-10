# CoA Management System - Complete Implementation Guide

## Files Created

### 1. **Model: `app/Models/CoA.php`**
- Defines the CoA data model with relationships to Sample and User
- Includes status helpers (isDraft, isPending, isApproved, isReleased, isArchived)
- Status color and label attributes for UI display
- Scopes for filtering by status, document number, and date range

### 2. **Migration: `database/migrations/2025_12_09_create_coas_table.php`**
- Creates `coas` table with all necessary fields
- Fields include:
  - `document_number` (unique identifier)
  - `sample_id` (foreign key to samples)
  - `status` enum (draft, pending_review, approved, released, archived)
  - `issued_date`, `expiry_date`, `release_date`
  - `approved_by`, `approved_at`, `released_by`, `released_at`
  - `file_path`, `data` (JSON), `notes`
  - Proper indexes for performance

### 3. **Livewire Component: `app/Livewire/CoAManagement.php`**
- Main component for managing CoAs
- Features:
  - **CRUD Operations**: Create, Read, Update, Delete CoAs
  - **Modal Management**: Separate modals for create/edit and view
  - **Status Management**: Approve and Release CoAs
  - **Filtering**: Search by document number, status, date range
  - **Permissions**: Checks for `approve_coa` and `release_coa` permissions
  - **Auto-number Generation**: Generates document numbers as COA-YYMM-xxxx
  - **Listeners**: Responds to `openCoAForm` event from sample dropdown

### 4. **View Template: `resources/views/livewire/coa-management.blade.php`**
- Complete management interface
- Features:
  - Filter section with document number, status, and date range search
  - Data table displaying all CoAs with status badges
  - Action buttons (View, Edit, Approve, Release, Delete) with permission checks
  - Create/Edit modal for CoA details
  - View modal for viewing CoA information
  - Pagination for large datasets
  - Alert messages for success/error feedback

## Installation Steps

### Step 1: Update Sample Model (Add Relationship)
Add to `app/Models/Sample.php`:
```php
public function coas()
{
    return $this->hasMany(CoA::class);
}
```

### Step 2: Run Migration
```bash
php artisan migrate
```

### Step 3: Add Permissions to Role System
In `app/Livewire/RoleManagement.php`, add to the `availablePermissions` array:
```php
'coa' => [
    'create_coa' => 'Create CoA',
    'edit_coa' => 'Edit CoA',
    'approve_coa' => 'Approve CoA',
    'release_coa' => 'Release CoA',
    'delete_coa' => 'Delete CoA',
]
```

### Step 4: Register Route (if not using convention)
In `routes/web.php`:
```php
Route::get('/coa-management', \App\Livewire\CoAManagement::class)->middleware('auth');
```

### Step 5: Update CheckPermission Middleware
Ensure your middleware supports the new permissions:
- `create_coa`
- `edit_coa`
- `approve_coa`
- `release_coa`
- `delete_coa`

## Usage

### Creating a CoA from Sample Actions Dropdown
1. In sample actions dropdown, click "Create CoA" button (appears when sample status = approved)
2. The modal opens with pre-populated sample data
3. Fill in document number, dates, and notes
4. Click "Create" to save

### Managing CoAs from CoA Management Page
1. Navigate to `/coa-management`
2. Use filters to search for specific CoAs
3. Actions available:
   - **View**: See full details of the CoA
   - **Edit**: Modify draft/pending CoAs (requires `edit_coa` permission)
   - **Approve**: Change status from pending_review to approved (requires `approve_coa`)
   - **Release**: Change status to released (requires `release_coa`)
   - **Delete**: Remove draft CoAs only (requires `delete_coa`)

## Workflow

```
Draft → Pending Review → Approved → Released → Archived
```

**Default Status**: All new CoAs start as "Draft"

## Key Features

✅ **Auto-number Generation**: Document numbers follow pattern COA-YYMM-xxxx
✅ **Permission-based Access**: Full permission integration with existing role system
✅ **Status Badges**: Color-coded status indicators for visual clarity
✅ **Date Range Filtering**: Search CoAs by issued date range
✅ **Relationship Tracking**: Automatic tracking of who approved/released and when
✅ **Sample Integration**: Auto-populates CoA data from sample information
✅ **Modal Isolation**: Separate modals for create/edit vs view operations

## Database Schema

```sql
CREATE TABLE `coas` (
  `id` BIGINT PRIMARY KEY AUTO_INCREMENT,
  `document_number` VARCHAR(255) UNIQUE NOT NULL,
  `sample_id` BIGINT NOT NULL FOREIGN KEY,
  `sample_type` VARCHAR(255) NOT NULL,
  `status` ENUM('draft','pending_review','approved','released','archived') DEFAULT 'draft',
  `issued_date` DATETIME,
  `expiry_date` DATETIME,
  `release_date` DATETIME,
  `approved_by` BIGINT,
  `approved_at` DATETIME,
  `released_by` BIGINT,
  `released_at` DATETIME,
  `file_path` VARCHAR(255),
  `data` LONGTEXT,
  `notes` TEXT,
  `created_at` TIMESTAMP,
  `updated_at` TIMESTAMP
);
```

## Next Steps (Optional Enhancements)

1. **PDF Generation**: Create PDF export functionality
2. **Email Notifications**: Send emails on CoA approval/release
3. **Digital Signatures**: Add signature field validation
4. **Version History**: Track changes to CoA records
5. **Archive Management**: Implement automated archiving policies
6. **Bulk Operations**: Enable bulk approve/release functionality
7. **Template System**: Support different CoA templates by material type
