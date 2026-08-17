param (
    [switch]$Install = $false,
    [switch]$Test = $false,
    [switch]$CI = $false
)

Write-Host "WP Git Log Project Runner" -ForegroundColor Green

$frontendDir = $PSScriptRoot
$backendDir = Join-Path $PSScriptRoot "laravel-git-log"

# Function to run local CI via act
if ($CI) {
    Write-Host "Running CI Pipeline locally using 'act'..." -ForegroundColor Cyan
    if (Get-Command act -ErrorAction SilentlyContinue) {
        act -j phpunit
    } else {
        Write-Host "Error: 'act' CLI not found. Please install nektos/act to run GitHub Actions locally." -ForegroundColor Red
    }
    exit
}

# Function to run tests
if ($Test) {
    Write-Host "Running Backend Tests & Endpoint Checks..." -ForegroundColor Cyan
    if (Test-Path $backendDir) {
        Push-Location $backendDir
        # Mocking or running php artisan test
        if (Get-Command php -ErrorAction SilentlyContinue) {
            Write-Host "Executing PHP tests..."
            php artisan test
            if ($LASTEXITCODE -eq 0) {
                Write-Host "Backend tests passed successfully." -ForegroundColor Green
            } else {
                Write-Host "Backend tests failed. Verify dependencies (vendor/autoload.php)." -ForegroundColor Red
            }
        }
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
    Write-Host "Laravel endpoints available at http://127.0.0.1:8000" -ForegroundColor Green
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
