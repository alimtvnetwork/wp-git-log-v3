param (
    [switch]$Install = $false,
    [switch]$Test = $false,
    [switch]$CI = $false
)

$configPath = Join-Path $PSScriptRoot "run.config.json"
if (-Not (Test-Path $configPath)) {
    Write-Host "Error: run.config.json not found." -ForegroundColor Red
    exit 1
}

$config = Get-Content $configPath | ConvertFrom-Json
$jobs = @()

Write-Host "WP Git Log Project Runner (Dynamic Orchestrator)" -ForegroundColor Green

try {
    if ($CI) {
        Write-Host "--- CI MODE ENABLED ---" -ForegroundColor Cyan
        
        $ciCmd = $config.commands."ci:local"
        if ($null -ne $ciCmd) {
            Write-Host "Running: $ciCmd"
            Invoke-Expression $ciCmd
            if ($LASTEXITCODE -ne 0) { throw "CI Local execution failed." }
        }
        
        $testBeCmd = $config.commands."test:backend"
        if ($null -ne $testBeCmd) {
            Push-Location (Join-Path $PSScriptRoot $config.backend.dir)
            Write-Host "Running Backend tests: $testBeCmd"
            Invoke-Expression $testBeCmd
            if ($LASTEXITCODE -ne 0) { throw "Backend tests failed." }
            Pop-Location
        }
        
        $testFeCmd = $config.commands."test:frontend"
        if ($null -ne $testFeCmd) {
            Push-Location (Join-Path $PSScriptRoot $config.frontend.dir)
            Write-Host "Running Frontend tests: $testFeCmd"
            Invoke-Expression $testFeCmd
            if ($LASTEXITCODE -ne 0) { throw "Frontend tests failed." }
            Pop-Location
        }
        
        Write-Host "CI steps passed successfully." -ForegroundColor Green
        exit 0
    }

    if ($Install) {
        Write-Host "--- INSTALLING DEPENDENCIES ---" -ForegroundColor Cyan
        Push-Location (Join-Path $PSScriptRoot $config.backend.dir)
        $installBe = $config.commands."install:backend"
        Write-Host "Backend: $installBe"
        Invoke-Expression $installBe
        Pop-Location

        Push-Location (Join-Path $PSScriptRoot $config.frontend.dir)
        $installFe = $config.commands."install:frontend"
        Write-Host "Frontend: $installFe"
        Invoke-Expression $installFe
        Pop-Location
    }

    if ($Test) {
        Write-Host "--- RUNNING TESTS ---" -ForegroundColor Cyan
        Push-Location (Join-Path $PSScriptRoot $config.backend.dir)
        $testBe = $config.commands."test:backend"
        Write-Host "Backend: $testBe"
        Invoke-Expression $testBe
        Pop-Location

        Push-Location (Join-Path $PSScriptRoot $config.frontend.dir)
        $testFe = $config.commands."test:frontend"
        Write-Host "Frontend: $testFe"
        Invoke-Expression $testFe
        Pop-Location
        exit 0
    }

    # Start Services Dynamically
    Write-Host "--- STARTING SERVICES ---" -ForegroundColor Cyan
    
    # Backend
    $beDir = Join-Path $PSScriptRoot $config.backend.dir
    $beCmdStr = $config.commands."dev:backend"
    $bePort = $config.backend.port
    $beCmdStr = $beCmdStr -replace '\{bePort\}', $bePort

    Write-Host "Starting Backend on port $bePort..." -ForegroundColor Yellow
    $beProcess = Start-Process -NoNewWindow -PassThru -FilePath "cmd.exe" -ArgumentList "/c cd `"$beDir`" && $beCmdStr"
    $jobs += $beProcess

    # Wait for Backend to be healthy
    Write-Host "Waiting for backend to become responsive..." -ForegroundColor Yellow
    $retries = 0
    $beHealthy = $false
    while ($retries -lt 30) {
        try {
            $resp = Invoke-WebRequest -Uri "http://$($config.host):$bePort" -UseBasicParsing -TimeoutSec 1 -ErrorAction Stop
            $beHealthy = $true
            break
        } catch {
            Start-Sleep -Seconds 1
            $retries++
        }
    }
    if ($beHealthy) {
        Write-Host "Backend is online!" -ForegroundColor Green
    } else {
        Write-Host "Warning: Backend did not respond in time, continuing anyway..." -ForegroundColor Yellow
    }

    # Frontend
    $feDir = Join-Path $PSScriptRoot $config.frontend.dir
    $feCmdStr = $config.commands."dev:frontend"
    $fePort = $config.frontend.port
    $feCmdStr = $feCmdStr -replace '\{fePort\}', $fePort

    Write-Host "Starting Frontend..." -ForegroundColor Yellow
    $feProcess = Start-Process -NoNewWindow -PassThru -FilePath "cmd.exe" -ArgumentList "/c cd `"$feDir`" && $feCmdStr"
    $jobs += $feProcess

    Write-Host "All services started! Press Ctrl+C to gracefully shut down." -ForegroundColor Green
    
    # Keep script running to maintain handle on jobs
    while ($true) {
        Start-Sleep -Seconds 1
    }

} catch {
    Write-Host "Execution aborted: $_" -ForegroundColor Red
    exit 1
} finally {
    Write-Host "`n--- CLEANUP: Terminating child processes ---" -ForegroundColor Cyan
    foreach ($job in $jobs) {
        if ($null -ne $job -and -not $job.HasExited) {
            Write-Host "Killing Process ID $($job.Id)..."
            Stop-Process -Id $job.Id -Force -ErrorAction SilentlyContinue
        }
    }
    Write-Host "Cleanup complete."
}
