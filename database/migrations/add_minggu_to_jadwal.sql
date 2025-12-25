-- Migration: Add 'Minggu' to jadwal_pelajaran.hari ENUM
-- Date: 2025-12-25
-- Description: Allow Sunday to be scheduled in the jadwal_pelajaran table
-- This enables all 7 days to be scheduled, with working days controlled by hari_kerja.is_active

-- Alter the jadwal_pelajaran table to include 'Minggu' in the hari ENUM
-- NOTE: Keep this ENUM list in sync with database/presensi_rfid.sql
ALTER TABLE `jadwal_pelajaran` 
MODIFY COLUMN `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') NOT NULL;

-- Note: This migration is safe to run on existing databases
-- No data will be lost as we're only adding a new value to the ENUM
