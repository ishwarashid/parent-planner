<?php

require_once 'vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use Aws\\S3\\S3Client;

// Get DigitalOcean Spaces configuration
$doKey = $_ENV['DO_SPACES_KEY'];
$doSecret = $_ENV['DO_SPACES_SECRET'];
$doRegion = $_ENV['DO_SPACES_REGION'];
$doBucket = $_ENV['DO_SPACES_BUCKET'];
$doEndpoint = $_ENV['DO_SPACES_ENDPOINT'];

echo "DO_SPACES_KEY: " . ($doKey ? 'Set' : 'Not set') . "\n";
echo "DO_SPACES_SECRET: " . ($doSecret ? 'Set' : 'Not set') . "\n";
echo "DO_SPACES_REGION: " . ($doRegion ?: 'Not set') . "\n";
echo "DO_SPACES_BUCKET: " . ($doBucket ?: 'Not set') . "\n";
echo "DO_SPACES_ENDPOINT: " . ($doEndpoint ?: 'Not set') . "\n";

// Create S3 client for DigitalOcean Spaces
try {
    $s3 = new S3Client([
        'version' => 'latest',
        'region' => $doRegion,
        'endpoint' => $doEndpoint,
        'credentials' => [
            'key' => $doKey,
            'secret' => $doSecret,
        ],
    ]);
    
    // Test listing objects in the bucket
    $result = $s3->listObjects([
        'Bucket' => $doBucket,
        'MaxKeys' => 5
    ]);
    
    echo "\nSuccessfully connected to DigitalOcean Spaces!\n";
    echo "Bucket: " . $doBucket . "\n";
    
    if (isset($result['Contents'])) {
        echo "Files in bucket:\n";
        foreach ($result['Contents'] as $object) {
            echo "- " . $object['Key'] . " (" . $object['Size'] . " bytes)\n";
        }
    } else {
        echo "No files found in bucket.\n";
    }
} catch (Exception $e) {
    echo "\nError connecting to DigitalOcean Spaces:\n";
    echo $e->getMessage() . "\n";
}