<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $sqlPath = 'c:/laragon/www/Absensi/database/if0_40499487_absen.sql';

        if (!File::exists($sqlPath)) {
            $this->command->error("SQL dump file not found at: {$sqlPath}");
            return;
        }

        $this->command->info("Reading SQL dump file...");
        $sql = File::get($sqlPath);

        // Turn off foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clean tables first
        DB::table('absensi')->truncate();
        DB::table('magang')->truncate();
        DB::table('qr')->truncate();
        DB::table('users')->truncate();

        $this->command->info("Truncated existing tables.");

        // Extract all INSERT INTO statements using regex
        preg_match_all('/INSERT INTO\s+`?[a-zA-Z0-9_]+`?[^;]+;/is', $sql, $matches);
        $insertCount = 0;

        $this->command->info("Processing SQL statements...");

        if (isset($matches[0]) && !empty($matches[0])) {
            foreach ($matches[0] as $statement) {
                $statement = trim($statement);
                if (empty($statement)) {
                    continue;
                }

                try {
                    $statement = str_replace("'0000-00-00'", "'1970-01-01'", $statement);
                    DB::unprepared($statement);
                    $insertCount++;
                } catch (\Exception $e) {
                    $this->command->error("Failed to execute statement: " . substr($statement, 0, 100) . "...");
                    $this->command->error("Error: " . $e->getMessage());
                }
            }
        }

        // Turn on foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info("Successfully executed {$insertCount} INSERT statements!");
    }
}
