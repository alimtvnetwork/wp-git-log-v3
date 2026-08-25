# Subtask: 02-braces-and-returns

## Instructions
Fix the following coding guideline violations. Do NOT guess. Verify each file exists before modifying. Follow the "Minimum correct fix" rule.

### File: src\pages\Dashboard.tsx
- [ ] Step 1: (Line 11) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return response.Results;

### File: src\pages\GitProfiles.tsx
- [ ] Step 2: (Line 17) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return response.Results as GitProfile[];

### File: src\pages\LogViewer.tsx
- [ ] Step 3: (Line 32) [magic_string] Extract magic string to a named Enum or constant.
  - Match: queryFn: async () => apiClient.get(`/pipelines/${id}/${tab === 'errors' ? 'errors' : 'logs'}`),
- [ ] Step 4: (Line 72) [magic_string] Extract magic string to a named Enum or constant.
  - Match: {tab === 'errors' ? (

### File: src\pages\PipelineDetail.tsx
- [ ] Step 5: (Line 19) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return response.Results as Pipeline;

### File: src\pages\Pipelines.tsx
- [ ] Step 6: (Line 17) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return response.Results as Pipeline[];

### File: src\pages\Profiles.tsx
- [ ] Step 7: (Line 28) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return map[id] || 'Unknown';

### File: src\pages\Repos.tsx
- [ ] Step 8: (Line 16) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return response.Results as Repo[];

### File: src\pages\TraceViewer.tsx
- [ ] Step 9: (Line 42) [magic_string] Extract magic string to a named Enum or constant.
  - Match: (typeof window !== "undefined" &&
- [ ] Step 10: (Line 56) [missing_braces_on_if] Wrap if statement body in braces.
  - Match: if (!kind) return null;
- [ ] Step 11: (Line 82) [missing_braces_on_if] Wrap if statement body in braces.
  - Match: if (!repoBase) return null;
- [ ] Step 12: (Line 83) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return `${repoBase.replace(/\/$/, "")}/blob/main/${file}`;
- [ ] Step 13: (Line 101) [missing_braces_on_if] Wrap if statement body in braces.
  - Match: if (!r.ok) throw new Error(`HTTP ${r.status}`);
- [ ] Step 14: (Line 102) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return r.json() as Promise<TraceMap>;
- [ ] Step 15: (Line 112) [missing_braces_on_if] Wrap if statement body in braces.
  - Match: if (!data) return [];
- [ ] Step 16: (Line 123) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return [...traced, ...drifted].sort((a, b) => a.id.localeCompare(b.id));
- [ ] Step 17: (Line 127) [missing_braces_on_if] Wrap if statement body in braces.
  - Match: if (!data) return [];
- [ ] Step 18: (Line 128) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return data.orphan.map((file) => ({
- [ ] Step 19: (Line 138) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return ["all", ...Array.from(set).sort()];
- [ ] Step 20: (Line 144) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return allAcRows.filter((row) => {
- [ ] Step 21: (Line 145) [magic_string] Extract magic string to a named Enum or constant.
  - Match: if (statusFilter !== "all" && statusFilter !== "orphan" && row.status !== statusFilter)
- [ ] Step 22: (Line 146) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return false;
- [ ] Step 23: (Line 147) [missing_braces_on_if] Wrap if statement body in braces.
  - Match: if (statusFilter === "orphan") return false; // handled separately
- [ ] Step 24: (Line 147) [magic_string] Extract magic string to a named Enum or constant.
  - Match: if (statusFilter === "orphan") return false; // handled separately
- [ ] Step 25: (Line 148) [missing_braces_on_if] Wrap if statement body in braces.
  - Match: if (moduleFilter !== "all" && moduleOf(row.id) !== moduleFilter) return false;
- [ ] Step 26: (Line 148) [magic_string] Extract magic string to a named Enum or constant.
  - Match: if (moduleFilter !== "all" && moduleOf(row.id) !== moduleFilter) return false;
- [ ] Step 27: (Line 149) [magic_string] Extract magic string to a named Enum or constant.
  - Match: if (kindFilter !== "all") {
- [ ] Step 28: (Line 150) [missing_braces_on_if] Wrap if statement body in braces.
  - Match: if (row.status === "drift") return false;
- [ ] Step 29: (Line 150) [magic_string] Extract magic string to a named Enum or constant.
  - Match: if (row.status === "drift") return false;
- [ ] Step 30: (Line 151) [missing_braces_on_if] Wrap if statement body in braces.
  - Match: if (!row.targets.some((t) => t.kind === kindFilter)) return false;
- [ ] Step 31: (Line 160) [missing_braces_on_if] Wrap if statement body in braces.
  - Match: if (!hay.includes(q)) return false;
- [ ] Step 32: (Line 167) [missing_braces_on_if] Wrap if statement body in braces.
  - Match: if (statusFilter !== "all" && statusFilter !== "orphan") return [];
- [ ] Step 33: (Line 167) [magic_string] Extract magic string to a named Enum or constant.
  - Match: if (statusFilter !== "all" && statusFilter !== "orphan") return [];
- [ ] Step 34: (Line 169) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return orphanRows.filter((r) => !q || r.file.toLowerCase().includes(q));
- [ ] Step 35: (Line 318) [magic_string] Extract magic string to a named Enum or constant.
  - Match: {m === "all" ? "All modules" : m}
- [ ] Step 36: (Line 378) [magic_string] Extract magic string to a named Enum or constant.
  - Match: {row.status === "drift" ? (
- [ ] Step 37: (Line 627) [magic_string] Extract magic string to a named Enum or constant.
  - Match: tone === "primary"
- [ ] Step 38: (Line 629) [magic_string] Extract magic string to a named Enum or constant.
  - Match: : tone === "warning"
- [ ] Step 39: (Line 631) [magic_string] Extract magic string to a named Enum or constant.
  - Match: : tone === "destructive"
- [ ] Step 40: (Line 641) [magic_string] Extract magic string to a named Enum or constant.
  - Match: {typeof total === "number" && (

### File: src\types\trace-map.ts
- [ ] Step 41: (Line 45) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return slash === -1 ? acId : acId.slice(0, slash);
- [ ] Step 42: (Line 51) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return hash === -1 ? acId : acId.slice(hash + 1);
- [ ] Step 43: (Line 57) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return hash === -1 ? acId : acId.slice(0, hash);

### File: laravel-git-log\app\Http\Controllers\LaneA\AppController.php
- [ ] Step 44: (Line 15) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return ApiResponse::success($items->toArray());
- [ ] Step 45: (Line 35) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return ApiResponse::success(['deleted' => true]);

### File: laravel-git-log\app\Http\Controllers\LaneA\AppLinkController.php
- [ ] Step 46: (Line 15) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return ApiResponse::success($items->toArray());
- [ ] Step 47: (Line 35) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return ApiResponse::success(['deleted' => true]);

### File: laravel-git-log\app\Http\Controllers\LaneA\AuditTrailController.php
- [ ] Step 48: (Line 15) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return ApiResponse::success($items->toArray());
- [ ] Step 49: (Line 35) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return ApiResponse::success(['deleted' => true]);

