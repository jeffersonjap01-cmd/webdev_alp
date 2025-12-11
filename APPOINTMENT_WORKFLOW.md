# Appointment Workflow System

## Overview
Comprehensive appointment management system with doctor workflow from pending to completion.

## Status Flow

```
Customer Creates → PENDING
                     ↓
Doctor Reviews → ACCEPTED or DECLINED
                     ↓
Doctor Starts → IN_PROGRESS
                     ↓
Doctor Completes → COMPLETED (redirect to medical record/vaccination)
```

## Database Changes

### 1. Appointments Table
- **Added Fields:**
  - `service_type` (string) - Type of service: General Checkup, Vaccination, Surgery, Grooming
  - `duration` (integer) - Duration in minutes (default: 30)

- **Status Enum:**
  - `pending` - Awaiting doctor acceptance
  - `accepted` - Doctor accepted, ready to start
  - `declined` - Doctor declined
  - `in_progress` - Appointment in progress
  - `completed` - Appointment finished
  - `cancelled` - Cancelled by customer/admin

### 2. Invoices Table
- **Added Fields:**
  - `appointment_id` (foreign key, nullable) - Links invoice to appointment

### 3. Doctors Seeder
- Enhanced with 4 doctors:
  - Dr. Ahmad Rizki (General Practice)
  - Dr. Sarah Johnson (Surgery)
  - Dr. Budi Santoso (Internal Medicine)
  - Dr. Lisa Chen (Vaccination Specialist)

## Routes

### Doctor Workflow Actions
- `POST /appointments/{id}/accept` - Doctor accepts appointment
- `POST /appointments/{id}/decline` - Doctor declines appointment
- `POST /appointments/{id}/start` - Doctor starts appointment (marks as in_progress)
- `POST /appointments/{id}/complete` - Doctor completes appointment

### Customer Actions
- `POST /appointments/{id}/cancel` - Customer cancels pending appointment

## Appointment Controller Methods

### `accept($id)`
- **Role:** Doctor only
- **Status:** pending → accepted
- **Validation:** Doctor can only accept their own appointments

### `decline($id)`
- **Role:** Doctor only
- **Status:** pending → declined
- **Validation:** Doctor can only decline their own appointments

### `start($id)`
- **Role:** Doctor only
- **Status:** accepted → in_progress
- **Validation:** Only accepted appointments can be started

### `markCompleted($id)`
- **Role:** Doctor only
- **Status:** accepted/in_progress → completed
- **Action:** Redirects to:
  - `vaccinations.create` if service_type is Vaccination
  - `medical-records.create` for other service types

## Model Updates

### Appointment Model
**New Relationships:**
- `invoice()` - hasOne Invoice
- `medicalRecord()` - hasOne MedicalRecord
- `vaccination()` - hasOne Vaccination

**New Scopes:**
- `scopePending()` - Filter pending appointments
- `scopeAccepted()` - Filter accepted appointments
- `scopeInProgress()` - Filter in-progress appointments

**New Methods:**
- `accept()` - Accept appointment
- `decline()` - Decline appointment
- `startProgress()` - Mark as in progress
- `complete()` - Mark as completed (inherited)

### Invoice Model
**New Fields:**
- `appointment_id` added to fillable

**New Relationships:**
- `appointment()` - belongsTo Appointment

## Views Updated

### `appointments/create.blade.php`
- Removed service types: "Dental Care" and "Emergency"
- Remaining services: General Checkup, Vaccination, Surgery, Grooming

### `appointments/show.blade.php`
- Added doctor workflow buttons:
  - **Pending:** Accept / Decline buttons (green/red)
  - **Accepted:** Start Appointment button (blue)
  - **Accepted/In Progress:** Complete & Record button (indigo)
- Added customer cancel button for pending appointments
- Updated status badges for new status values

### `appointments/index.blade.php`
- Updated status filter dropdown with new statuses
- Updated status badges in appointment list

## Next Steps (Not Yet Implemented)

1. **Medical Records Integration**
   - Update `MedicalRecordController@create` to accept `appointment_id` parameter
   - Auto-populate pet and date from appointment
   - Link medical record to appointment after creation

2. **Vaccination Workflow**
   - Create vaccination type selection page
   - Update `VaccinationController@create` to accept `appointment_id` parameter
   - Link vaccination to appointment

3. **Invoice/Billing System (Tagihan)**
   - Create invoice list page (customer view)
   - Create invoice detail/payment page
   - Add "Tagihan" to navigation menu
   - Implement QRIS payment integration (future)
   - Auto-generate invoice after appointment completion

4. **Dashboard Updates**
   - Show pending appointments count for doctors
   - Show appointment status statistics
   - Recent appointments widget

## Testing Checklist

- [ ] Customer can create appointment (status: pending)
- [ ] Doctor sees pending appointments
- [ ] Doctor can accept appointment
- [ ] Doctor can decline appointment
- [ ] Doctor can start accepted appointment
- [ ] Doctor can complete in-progress appointment
- [ ] Completion redirects to medical record for General Checkup
- [ ] Completion redirects to vaccination for Vaccination service
- [ ] Customer can cancel pending appointment
- [ ] Status badges display correctly
- [ ] Filters work with new statuses

## Notes

- Service type field is now required on appointment creation
- Duration defaults to 30 minutes but can be customized
- Appointments start in "pending" status (not "scheduled")
- Doctor workflow ensures proper status transitions
- Invoice linking enables future billing integration
