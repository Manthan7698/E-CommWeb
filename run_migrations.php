<?php
require 'config.php';

// Array of migration files to run
$migrations = [
    'migrations/add_google_fields_to_users.sql',
    'migrations/create_user_addresses_table.sql'
];

// Function to run SQL file
function runSQLFile($conn, $filename) {
    echo "Running migration: $filename\n";
    
    try {
        $sql = file_get_contents($filename);
        if ($sql === false) {
            throw new Exception("Error reading file: $filename");
        }

        // Split SQL file into individual queries
        $queries = array_filter(array_map('trim', explode(';', $sql)));
        
        foreach ($queries as $query) {
            if (!empty($query)) {
                if (!mysqli_query($conn, $query)) {
                    throw new Exception("Error executing query: " . mysqli_error($conn));
                }
            }
        }
        
        echo "Successfully executed migration: $filename\n";
        return true;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        return false;
    }
}

// Run each migration
foreach ($migrations as $migration) {
    if (!runSQLFile($conn, $migration)) {
        echo "Migration failed for: $migration\n";
        exit(1);
    }
}

echo "All migrations completed successfully!\n";
mysqli_close($conn);
?> 