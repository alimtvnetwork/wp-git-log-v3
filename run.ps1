param (
    [switch]$Install = $false,
    [switch]$Test = $false
)

Write-Host "WP Git Log Project Runner" -ForegroundColor Green

$frontendDir = $PSScriptRoot
$backendDir = Join-Path $PSScriptRoot "laravel-git-log"

# Function to run tests
if ($Test) {
    Write-Host "Running Backend Tests..." -ForegroundColor Cyan
    if (Test-Path $backendDir) {
        Push-Location $backendDir
        php artisan test
        Pop-Location
    }

    Write-Host "Running Frontend Tests..." -ForegroundColor Cyan
    Push-Location $frontendDir
    if (Get-Command bun -ErrorAction SilentlyContinue) {
        bun run test
    } elseif (Get-Command npm -ErrorAction SilentlyContinue) {
        npm run test
    }
    Pop-Location
    exit
}

# Install dependencies if requested
if ($Install) {
    Write-Host "Installing Backend Dependencies..." -ForegroundColor Cyan
    if (Test-Path $backendDir) {
        Push-Location $backendDir
        if (Get-Command composer -ErrorAction SilentlyContinue) {
            composer install
        } else {
            Write-Host "Composer not found! Please install composer to manage PHP dependencies." -ForegroundColor Yellow
        }
        Pop-Location
    }

    Write-Host "Installing Frontend Dependencies..." -ForegroundColor Cyan
    Push-Location $frontendDir
    if (Get-Command bun -ErrorAction SilentlyContinue) {
        bun install
    } elseif (Get-Command npm -ErrorAction SilentlyContinue) {
        npm install
    }
    Pop-Location
}

# Start Backend
if (Test-Path $backendDir) {
    Write-Host "Starting Laravel Backend..." -ForegroundColor Cyan
    Start-Process -NoNewWindow -FilePath "php" -ArgumentList "artisan serve" -WorkingDirectory $backendDir
}

# Start Frontend
Write-Host "Starting React Frontend..." -ForegroundColor Cyan
if (Get-Command bun -ErrorAction SilentlyContinue) {
    bun run dev
} elseif (Get-Command npm -ErrorAction SilentlyContinue) {
    npm run dev
} else {
    Write-Host "Could not find bun or npm to start the frontend." -ForegroundColor Red
}
