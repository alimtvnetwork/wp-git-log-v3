# Subtask: 03-misc-and-formatting

## Instructions
Fix the following coding guideline violations. Do NOT guess. Verify each file exists before modifying. Follow the "Minimum correct fix" rule.

### File: laravel-git-log\app\Http\Controllers\LaneA\GitProfileController.php
- [ ] Step 1: (Line 16) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return ApiResponse::success($profiles->toArray());
- [ ] Step 2: (Line 96) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return ApiResponse::success(['deleted' => true]);

### File: laravel-git-log\app\Http\Controllers\LaneA\PermissionController.php
- [ ] Step 3: (Line 15) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return ApiResponse::success($items->toArray());
- [ ] Step 4: (Line 35) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return ApiResponse::success(['deleted' => true]);

### File: laravel-git-log\app\Http\Controllers\LaneA\RepoController.php
- [ ] Step 5: (Line 15) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return ApiResponse::success($repos->toArray());
- [ ] Step 6: (Line 53) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return ApiResponse::success(['deleted' => true]);

### File: laravel-git-log\app\Http\Controllers\LaneA\RepoVersionController.php
- [ ] Step 7: (Line 15) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return ApiResponse::success($items->toArray());
- [ ] Step 8: (Line 35) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return ApiResponse::success(['deleted' => true]);

### File: laravel-git-log\app\Http\Controllers\LaneA\RoleController.php
- [ ] Step 9: (Line 15) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return ApiResponse::success($items->toArray());
- [ ] Step 10: (Line 35) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return ApiResponse::success(['deleted' => true]);

### File: laravel-git-log\app\Http\Controllers\LaneA\SshKeyController.php
- [ ] Step 11: (Line 15) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return ApiResponse::success($items->toArray());
- [ ] Step 12: (Line 35) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return ApiResponse::success(['deleted' => true]);

### File: laravel-git-log\app\Http\Middleware\LaneBMiddleware.php
- [ ] Step 13: (Line 24) [magic_string] Extract magic string to a named Enum or constant.
  - Match: $sshModeRequired = $configMode && $configMode->ValueText === 'required';
- [ ] Step 14: (Line 26) [magic_string] Extract magic string to a named Enum or constant.
  - Match: if ($sshModeRequired && $mode !== 'ssh') {
- [ ] Step 15: (Line 30) [magic_string] Extract magic string to a named Enum or constant.
  - Match: if ($mode === 'ssh') {
- [ ] Step 16: (Line 40) [magic_string] Extract magic string to a named Enum or constant.
  - Match: if ($request->header('X-GL-Auth-Mode') === 'ssh') {
- [ ] Step 17: (Line 68) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return ApiResponse::fail(new ErrorEnvelope('GL-AUTH-PROFILE-INACTIVE', 'Profile inactive', 'error', now()->toIso8601String()), 403);
- [ ] Step 18: (Line 164) [magic_string] Extract magic string to a named Enum or constant.
  - Match: // Signature check placeholder (always valid in local for now unless signature === 'FAIL')
- [ ] Step 19: (Line 165) [magic_string] Extract magic string to a named Enum or constant.
  - Match: if ($signature === 'FAIL') {

### File: laravel-git-log\app\Http\Requests\BaseLaneAFormRequest.php
- [ ] Step 20: (Line 25) [missing_braces_on_if] Wrap if statement body in braces.
  - Match: if ($q === null || $q === '') return;

### File: laravel-git-log\app\Models\BaseModel.php
- [ ] Step 21: (Line 36) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return parent::getAttribute($pascalKey);
- [ ] Step 22: (Line 42) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return parent::setAttribute($pascalKey, $value);

### File: laravel-git-log\app\Services\Database\SqliteShaRegistryRepository.php
- [ ] Step 23: (Line 32) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return $id;
- [ ] Step 24: (Line 46) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return $row ?: null;

### File: laravel-git-log\app\Services\Database\SqliteSplitDbWriter.php
- [ ] Step 25: (Line 40) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return $pdo;

### File: laravel-git-log\app\Services\PdoLogIngestService.php
- [ ] Step 26: (Line 37) [missing_blank_line_before_return] Add a blank line before return statement.
  - Match: return new IngestResult(true);
- [ ] Step 27: (Line 78) [magic_string] Extract magic string to a named Enum or constant.
  - Match: if (str_contains($e->getMessage(), 'database is locked') || $e->getCode() === 'HY000') {

