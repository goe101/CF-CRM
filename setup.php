<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ConsoleCare Setup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #1a1d20; color: #adb5bd; }
        .setup-card { max-width: 550px; margin: 80px auto; border: 1px solid #343a40; border-radius: 12px; overflow: hidden; background-color: #212529; }
        .card-header { background-color: #0d6efd; color: white; padding: 20px; border-bottom: none; }
        .step-indicator { font-size: 0.9rem; color: #6c757d; margin-bottom: 20px; }
        .step-active { color: #0d6efd; font-weight: 600; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card setup-card shadow-lg">
            <div class="card-header text-center">
                <h3 class="mb-0">ConsoleCare Setup</h3>
            </div>
            <div class="card-body p-5">
                
                <?php
                $step = $_GET['step'] ?? 'welcome';
                $zipFile = 'release.zip';
                $setupFile = basename(__FILE__); // setup.php

                if (!file_exists($zipFile)) {
                    echo '<div class="alert alert-danger text-center">
                            <i class="bi bi-exclamation-triangle-fill fs-1"></i><br><br>
                            <strong>release.zip</strong> not found.<br>
                            Please upload both <code>setup.php</code> and <code>release.zip</code> to your server.
                          </div>';
                    exit;
                }
                ?>

                <!-- Step Indicators -->
                <div class="d-flex justify-content-center mb-4 border-bottom pb-3">
                    <div class="mx-3 <?php echo $step === 'welcome' ? 'step-active' : ''; ?>">1. Welcome</div>
                    <div class="mx-3 text-muted">></div>
                    <div class="mx-3 <?php echo $step === 'extract' ? 'step-active' : ''; ?>">2. Extract</div>
                    <div class="mx-3 text-muted">></div>
                    <div class="mx-3">3. Install</div>
                </div>

                <?php if ($step === 'welcome'): ?>
                    <!-- STEP 1: WELCOME -->
                    <div class="text-center">
                        <h4 class="mb-3">Welcome to Setup</h4>
                        <p class="text-muted mb-4">This wizard will extract the ConsoleCare CRM files to your server.</p>
                        
                        <div class="alert alert-info text-start">
                            <small>
                                <i class="bi bi-info-circle me-2"></i><strong>Pre-flight Check:</strong><br>
                                &check; PHP Version: <?php echo phpversion(); ?><br>
                                &check; Zip Extension: <?php echo class_exists('ZipArchive') ? 'Enabled' : '<span class="text-danger">Disabled</span>'; ?><br>
                                &check; Release File: Found (<?php echo round(filesize($zipFile) / 1024 / 1024, 2); ?> MB)
                            </small>
                        </div>

                        <a href="?step=extract" class="btn btn-primary w-100 btn-lg mt-3">
                            Start Installation <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>

                <?php elseif ($step === 'extract'): ?>
                    <!-- STEP 2: EXTRACT -->
                    <div class="text-center">
                        <?php
                        $zip = new ZipArchive;
                        if ($zip->open($zipFile) === TRUE) {
                            $zip->extractTo(__DIR__);
                            $zip->close();
                            
                            // Success UI
                            echo '<div class="text-success mb-3"><i class="bi bi-check-circle-fill" style="font-size: 4rem;"></i></div>';
                            echo '<h4 class="mb-3">Extraction Successful!</h4>';
                            echo '<p class="text-muted mb-4">The files have been unpacked. You are now ready to configure the database.</p>';
                            
                            echo '<a href="install/install.php" class="btn btn-success w-100 btn-lg">
                                    Launch Configuration <i class="bi bi-rocket-takeoff ms-2"></i>
                                  </a>';
                                  
                            // Cleanup option (commented out for safety)
                            // unlink($zipFile);
                            // unlink($setupFile);

                        } else {
                            // Error UI
                            echo '<div class="text-danger mb-3"><i class="bi bi-x-circle-fill" style="font-size: 4rem;"></i></div>';
                            echo '<h4 class="mb-3">Extraction Failed</h4>';
                            echo '<div class="alert alert-danger">Could not open <strong>release.zip</strong>. Check file permissions.</div>';
                            echo '<a href="?step=welcome" class="btn btn-secondary">Try Again</a>';
                        }
                        ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
        <div class="text-center mt-3 text-muted">
            <small>&copy; <?php echo date('Y'); ?> ConsoleCare CRM</small>
        </div>
    </div>
</body>
</html>