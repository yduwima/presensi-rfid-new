# Database Migration Guide

## Migration: Add 'Minggu' to jadwal_pelajaran

### Purpose
This migration allows Sunday (Minggu) to be scheduled in the `jadwal_pelajaran` table. Previously, the ENUM field only supported Monday through Saturday.

### For Fresh Installations
If you're installing the system for the first time, simply import the main SQL file:
```bash
mysql -u username -p database_name < database/presensi_rfid.sql
```

The updated schema already includes 'Minggu' in the ENUM.

### For Existing Databases
If you already have the database installed, run this migration:
```bash
mysql -u username -p database_name < database/migrations/add_minggu_to_jadwal.sql
```

Or execute directly in MySQL:
```sql
ALTER TABLE `jadwal_pelajaran` 
MODIFY COLUMN `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') NOT NULL;
```

### Impact
- **Safe migration**: No data will be lost
- **Backward compatible**: Existing schedules will continue to work
- All 7 days can now be scheduled
- Working days are controlled by `hari_kerja.is_active` setting
- Users can enable/disable Sunday (or any day) through the Hari Kerja menu

### How It Works
1. The `jadwal_pelajaran` table now accepts all 7 days
2. The dropdown in the schedule form shows only active days from `hari_kerja` table
3. By default, Sunday is inactive (`is_active = 0`) but can be activated if needed
4. This gives flexibility to schools that operate on Sundays

### Testing
After running the migration:
1. Log in to the admin panel
2. Go to "Hari Kerja" menu
3. Activate Sunday if needed
4. Go to "Jadwal Pelajaran" menu
5. Click "Tambah Jadwal"
6. Verify that Sunday appears in the dropdown if it's active
7. Try creating a schedule for Sunday
