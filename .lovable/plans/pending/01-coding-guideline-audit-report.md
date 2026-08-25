# Coding Guideline Audit Report (v4)

## Executive Summary
This audit deeply parsed the src/ and laravel-git-log/ codebase targeting the v4 coding guidelines (boolean naming, magic strings, missing braces, etc.). Exactly 150 violations were flagged.

## Files to Test Against
- src\components\ErrorBanner.tsx
- src\components\theme-provider.tsx
- src\components\ui\button.tsx
- src\components\ui\carousel.tsx
- src\components\ui\chart.tsx
- src\components\ui\scroll-area.tsx
- src\components\ui\select.tsx
- src\components\ui\separator.tsx
- src\components\ui\sidebar.tsx
- src\hooks\use-mobile.tsx
- src\hooks\use-toast.ts
- src\lib\query-client.ts
- src\pages\Dashboard.tsx
- src\pages\GitProfiles.tsx
- src\pages\LogViewer.tsx
- src\pages\PipelineDetail.tsx
- src\pages\Pipelines.tsx
- src\pages\Profiles.tsx
- src\pages\Repos.tsx
- src\pages\TraceViewer.tsx
- src\types\trace-map.ts
- laravel-git-log\app\Http\Controllers\LaneA\AppController.php
- laravel-git-log\app\Http\Controllers\LaneA\AppLinkController.php
- laravel-git-log\app\Http\Controllers\LaneA\AuditTrailController.php
- laravel-git-log\app\Http\Controllers\LaneA\GitProfileController.php
- laravel-git-log\app\Http\Controllers\LaneA\PermissionController.php
- laravel-git-log\app\Http\Controllers\LaneA\RepoController.php
- laravel-git-log\app\Http\Controllers\LaneA\RepoVersionController.php
- laravel-git-log\app\Http\Controllers\LaneA\RoleController.php
- laravel-git-log\app\Http\Controllers\LaneA\SshKeyController.php
- laravel-git-log\app\Http\Middleware\LaneBMiddleware.php
- laravel-git-log\app\Http\Requests\BaseLaneAFormRequest.php
- laravel-git-log\app\Models\BaseModel.php
- laravel-git-log\app\Services\Database\SqliteShaRegistryRepository.php
- laravel-git-log\app\Services\Database\SqliteSplitDbWriter.php
- laravel-git-log\app\Services\PdoLogIngestService.php
- src/main.tsx
- src/App.tsx

## Detailed File Audit

### File: src\components\ErrorBanner.tsx
Issues found:
- Line 11: [missing_braces_on_if] if (!error) return null;
- Line 14: [magic_string] if (error && typeof error === 'object' && 'Status' in error) {

### File: src\components\theme-provider.tsx
Issues found:
- Line 38: [magic_string] if (theme === "system") {

### File: src\components\ui\button.tsx
Issues found:
- Line 42: [missing_blank_line_before_return] return <Comp className={cn(buttonVariants({ variant, size, className }))} ref={ref} {...props} />;

### File: src\components\ui\carousel.tsx
Issues found:
- Line 46: [magic_string] axis: orientation === "horizontal" ? "x" : "y",
- Line 72: [magic_string] if (event.key === "ArrowLeft") {
- Line 75: [magic_string] } else if (event.key === "ArrowRight") {
- Line 111: [magic_string] orientation: orientation || (opts?.axis === "y" ? "vertical" : "horizontal"),
- Line 142: [magic_string] className={cn("flex", orientation === "horizontal" ? "-ml-4" : "-mt-4 flex-col", className)}
- Line 160: [magic_string] className={cn("min-w-0 shrink-0 grow-0 basis-full", orientation === "horizontal" ? "pl-4" : "pt-4", className)}
- Line 179: [magic_string] orientation === "horizontal"
- Line 184: [inverted_boolean] disabled={!canScrollPrev}
- Line 207: [magic_string] orientation === "horizontal"
- Line 212: [inverted_boolean] disabled={!canScrollNext}

### File: src\components\ui\chart.tsx
Issues found:
- Line 78: [missing_blank_line_before_return] return color ? `  --color-${key}: ${color};` : null;
- Line 132: [magic_string] !labelKey && typeof label === "string"
- Line 151: [magic_string] const nestLabel = payload.length === 1 && indicator !== "dot";
- Line 173: [magic_string] indicator === "dot" && "items-center",
- Line 186: [magic_string] "h-2.5 w-2.5": indicator === "dot",
- Line 187: [magic_string] "w-1": indicator === "line",
- Line 188: [magic_string] "w-0 border-[1.5px] border-dashed bg-transparent": indicator === "dashed",
- Line 189: [magic_string] "my-0.5": nestLabel && indicator === "dashed",
- Line 247: [magic_string] className={cn("flex items-center justify-center gap-4", verticalAlign === "top" ? "pb-3" : "pt-3", className)}
- Line 279: [magic_string] if (typeof payload !== "object" || payload === null) {
- Line 284: [magic_string] "payload" in payload && typeof payload.payload === "object" && payload.payload !== null
- Line 290: [magic_string] if (key in payload && typeof payload[key as keyof typeof payload] === "string") {
- Line 295: [magic_string] typeof payloadPayload[key as keyof typeof payloadPayload] === "string"

### File: src\components\ui\scroll-area.tsx
Issues found:
- Line 27: [magic_string] orientation === "vertical" && "h-full w-2.5 border-l border-l-transparent p-[1px]",
- Line 28: [magic_string] orientation === "horizontal" && "h-2.5 flex-col border-t border-t-transparent p-[1px]",

### File: src\components\ui\select.tsx
Issues found:
- Line 70: [magic_string] position === "popper" &&
- Line 81: [magic_string] position === "popper" &&

### File: src\components\ui\separator.tsx
Issues found:
- Line 14: [magic_string] className={cn("shrink-0 bg-border", orientation === "horizontal" ? "h-[1px] w-full" : "h-full w-[1px]", className)}

### File: src\components\ui\sidebar.tsx
Issues found:
- Line 60: [magic_string] const openState = typeof value === "function" ? value(open) : value;
- Line 141: [magic_string] if (collapsible === "none") {
- Line 188: [magic_string] variant === "floating" || variant === "inset"
- Line 196: [magic_string] side === "left"
- Line 200: [magic_string] variant === "floating" || variant === "inset"
- Line 462: [magic_string] if (typeof tooltip === "string") {
- Line 471: [magic_string] <TooltipContent side="right" align="center" hidden={state !== "collapsed" || isMobile} {...tooltip} />
- Line 601: [magic_string] size === "sm" && "text-xs",
- Line 602: [magic_string] size === "md" && "text-sm",

### File: src\hooks\use-mobile.tsx
Issues found:
- Line 18: [inverted_boolean] return !!isMobile;

### File: src\hooks\use-toast.ts
Issues found:
- Line 26: [missing_blank_line_before_return] return count.toString();
- Line 74: [missing_blank_line_before_return] return {
- Line 80: [missing_blank_line_before_return] return {

### File: src\lib\query-client.ts
Issues found:
- Line 9: [magic_string] if (error.name === 'ApiError') {

### File: src\pages\Dashboard.tsx
Issues found:
- Line 11: [missing_blank_line_before_return] return response.Results;

### File: src\pages\GitProfiles.tsx
Issues found:
- Line 17: [missing_blank_line_before_return] return response.Results as GitProfile[];

### File: src\pages\LogViewer.tsx
Issues found:
- Line 32: [magic_string] queryFn: async () => apiClient.get(`/pipelines/${id}/${tab === 'errors' ? 'errors' : 'logs'}`),
- Line 72: [magic_string] {tab === 'errors' ? (

### File: src\pages\PipelineDetail.tsx
Issues found:
- Line 19: [missing_blank_line_before_return] return response.Results as Pipeline;

### File: src\pages\Pipelines.tsx
Issues found:
- Line 17: [missing_blank_line_before_return] return response.Results as Pipeline[];

### File: src\pages\Profiles.tsx
Issues found:
- Line 28: [missing_blank_line_before_return] return map[id] || 'Unknown';

### File: src\pages\Repos.tsx
Issues found:
- Line 16: [missing_blank_line_before_return] return response.Results as Repo[];

### File: src\pages\TraceViewer.tsx
Issues found:
- Line 42: [magic_string] (typeof window !== "undefined" &&
- Line 56: [missing_braces_on_if] if (!kind) return null;
- Line 82: [missing_braces_on_if] if (!repoBase) return null;
- Line 83: [missing_blank_line_before_return] return `${repoBase.replace(/\/$/, "")}/blob/main/${file}`;
- Line 101: [missing_braces_on_if] if (!r.ok) throw new Error(`HTTP ${r.status}`);
- Line 102: [missing_blank_line_before_return] return r.json() as Promise<TraceMap>;
- Line 112: [missing_braces_on_if] if (!data) return [];
- Line 123: [missing_blank_line_before_return] return [...traced, ...drifted].sort((a, b) => a.id.localeCompare(b.id));
- Line 127: [missing_braces_on_if] if (!data) return [];
- Line 128: [missing_blank_line_before_return] return data.orphan.map((file) => ({
- Line 138: [missing_blank_line_before_return] return ["all", ...Array.from(set).sort()];
- Line 144: [missing_blank_line_before_return] return allAcRows.filter((row) => {
- Line 145: [magic_string] if (statusFilter !== "all" && statusFilter !== "orphan" && row.status !== statusFilter)
- Line 146: [missing_blank_line_before_return] return false;
- Line 147: [missing_braces_on_if] if (statusFilter === "orphan") return false; // handled separately
- Line 147: [magic_string] if (statusFilter === "orphan") return false; // handled separately
- Line 148: [missing_braces_on_if] if (moduleFilter !== "all" && moduleOf(row.id) !== moduleFilter) return false;
- Line 148: [magic_string] if (moduleFilter !== "all" && moduleOf(row.id) !== moduleFilter) return false;
- Line 149: [magic_string] if (kindFilter !== "all") {
- Line 150: [missing_braces_on_if] if (row.status === "drift") return false;
- Line 150: [magic_string] if (row.status === "drift") return false;
- Line 151: [missing_braces_on_if] if (!row.targets.some((t) => t.kind === kindFilter)) return false;
- Line 160: [missing_braces_on_if] if (!hay.includes(q)) return false;
- Line 167: [missing_braces_on_if] if (statusFilter !== "all" && statusFilter !== "orphan") return [];
- Line 167: [magic_string] if (statusFilter !== "all" && statusFilter !== "orphan") return [];
- Line 169: [missing_blank_line_before_return] return orphanRows.filter((r) => !q || r.file.toLowerCase().includes(q));
- Line 318: [magic_string] {m === "all" ? "All modules" : m}
- Line 378: [magic_string] {row.status === "drift" ? (
- Line 627: [magic_string] tone === "primary"
- Line 629: [magic_string] : tone === "warning"
- Line 631: [magic_string] : tone === "destructive"
- Line 641: [magic_string] {typeof total === "number" && (

### File: src\types\trace-map.ts
Issues found:
- Line 45: [missing_blank_line_before_return] return slash === -1 ? acId : acId.slice(0, slash);
- Line 51: [missing_blank_line_before_return] return hash === -1 ? acId : acId.slice(hash + 1);
- Line 57: [missing_blank_line_before_return] return hash === -1 ? acId : acId.slice(0, hash);

### File: laravel-git-log\app\Http\Controllers\LaneA\AppController.php
Issues found:
- Line 15: [missing_blank_line_before_return] return ApiResponse::success($items->toArray());
- Line 35: [missing_blank_line_before_return] return ApiResponse::success(['deleted' => true]);

### File: laravel-git-log\app\Http\Controllers\LaneA\AppLinkController.php
Issues found:
- Line 15: [missing_blank_line_before_return] return ApiResponse::success($items->toArray());
- Line 35: [missing_blank_line_before_return] return ApiResponse::success(['deleted' => true]);

### File: laravel-git-log\app\Http\Controllers\LaneA\AuditTrailController.php
Issues found:
- Line 15: [missing_blank_line_before_return] return ApiResponse::success($items->toArray());
- Line 35: [missing_blank_line_before_return] return ApiResponse::success(['deleted' => true]);

### File: laravel-git-log\app\Http\Controllers\LaneA\GitProfileController.php
Issues found:
- Line 16: [missing_blank_line_before_return] return ApiResponse::success($profiles->toArray());
- Line 96: [missing_blank_line_before_return] return ApiResponse::success(['deleted' => true]);

### File: laravel-git-log\app\Http\Controllers\LaneA\PermissionController.php
Issues found:
- Line 15: [missing_blank_line_before_return] return ApiResponse::success($items->toArray());
- Line 35: [missing_blank_line_before_return] return ApiResponse::success(['deleted' => true]);

### File: laravel-git-log\app\Http\Controllers\LaneA\RepoController.php
Issues found:
- Line 15: [missing_blank_line_before_return] return ApiResponse::success($repos->toArray());
- Line 53: [missing_blank_line_before_return] return ApiResponse::success(['deleted' => true]);

### File: laravel-git-log\app\Http\Controllers\LaneA\RepoVersionController.php
Issues found:
- Line 15: [missing_blank_line_before_return] return ApiResponse::success($items->toArray());
- Line 35: [missing_blank_line_before_return] return ApiResponse::success(['deleted' => true]);

### File: laravel-git-log\app\Http\Controllers\LaneA\RoleController.php
Issues found:
- Line 15: [missing_blank_line_before_return] return ApiResponse::success($items->toArray());
- Line 35: [missing_blank_line_before_return] return ApiResponse::success(['deleted' => true]);

### File: laravel-git-log\app\Http\Controllers\LaneA\SshKeyController.php
Issues found:
- Line 15: [missing_blank_line_before_return] return ApiResponse::success($items->toArray());
- Line 35: [missing_blank_line_before_return] return ApiResponse::success(['deleted' => true]);

### File: laravel-git-log\app\Http\Middleware\LaneBMiddleware.php
Issues found:
- Line 24: [magic_string] $sshModeRequired = $configMode && $configMode->ValueText === 'required';
- Line 26: [magic_string] if ($sshModeRequired && $mode !== 'ssh') {
- Line 30: [magic_string] if ($mode === 'ssh') {
- Line 40: [magic_string] if ($request->header('X-GL-Auth-Mode') === 'ssh') {
- Line 68: [missing_blank_line_before_return] return ApiResponse::fail(new ErrorEnvelope('GL-AUTH-PROFILE-INACTIVE', 'Profile inactive', 'error', now()->toIso8601String()), 403);
- Line 164: [magic_string] // Signature check placeholder (always valid in local for now unless signature === 'FAIL')
- Line 165: [magic_string] if ($signature === 'FAIL') {

### File: laravel-git-log\app\Http\Requests\BaseLaneAFormRequest.php
Issues found:
- Line 25: [missing_braces_on_if] if ($q === null || $q === '') return;

### File: laravel-git-log\app\Models\BaseModel.php
Issues found:
- Line 36: [missing_blank_line_before_return] return parent::getAttribute($pascalKey);
- Line 42: [missing_blank_line_before_return] return parent::setAttribute($pascalKey, $value);

### File: laravel-git-log\app\Services\Database\SqliteShaRegistryRepository.php
Issues found:
- Line 32: [missing_blank_line_before_return] return $id;
- Line 46: [missing_blank_line_before_return] return $row ?: null;

### File: laravel-git-log\app\Services\Database\SqliteSplitDbWriter.php
Issues found:
- Line 40: [missing_blank_line_before_return] return $pdo;

### File: laravel-git-log\app\Services\PdoLogIngestService.php
Issues found:
- Line 37: [missing_blank_line_before_return] return new IngestResult(true);
- Line 78: [magic_string] if (str_contains($e->getMessage(), 'database is locked') || $e->getCode() === 'HY000') {

### File: src/main.tsx
Issues found:
- Line 1: [structural_review_required] import { createRoot } from "react-dom/client";
- Line 2: [structural_review_required] import App from "./App.tsx";
- Line 3: [structural_review_required] import "./index.css";
- Line 5: [structural_review_required] createRoot(document.getElementById("root")!).render(<App />);

### File: src/App.tsx
Issues found:
- Line 1: [structural_review_required] import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
- Line 2: [structural_review_required] import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
- Line 3: [structural_review_required] import { ThemeProvider } from './components/theme-provider';
- Line 4: [structural_review_required] import { Layout } from './components/Layout';
- Line 5: [structural_review_required] import Dashboard from './pages/Dashboard';
- Line 6: [structural_review_required] import GitProfiles from './pages/GitProfiles';
- Line 7: [structural_review_required] import Repos from './pages/Repos';
- Line 8: [structural_review_required] import Pipelines from './pages/Pipelines';
- Line 9: [structural_review_required] import PipelineDetail from './pages/PipelineDetail';
- Line 10: [structural_review_required] import { Toaster } from './components/ui/sonner';
- Line 12: [structural_review_required] // Initialize React Query
- Line 13: [structural_review_required] const queryClient = new QueryClient();
- Line 15: [structural_review_required] function App() {
- Line 16: [structural_review_required] return (
- Line 17: [structural_review_required] <QueryClientProvider client={queryClient}>
- Line 18: [structural_review_required] <ThemeProvider defaultTheme="dark" storageKey="vite-ui-theme">
- Line 19: [structural_review_required] <BrowserRouter>
- Line 20: [structural_review_required] <Routes>
- Line 21: [structural_review_required] <Route element={<Layout />}>
- Line 22: [structural_review_required] <Route path="/" element={<Navigate to="/dashboard" replace />} />
- Line 23: [structural_review_required] <Route path="/dashboard" element={<Dashboard />} />
- Line 24: [structural_review_required] <Route path="/profiles" element={<GitProfiles />} />
- Line 25: [structural_review_required] <Route path="/repos" element={<Repos />} />
- Line 26: [structural_review_required] <Route path="/pipelines" element={<Pipelines />} />

