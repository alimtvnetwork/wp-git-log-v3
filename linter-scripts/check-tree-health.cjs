#!/usr/bin/env node
/**
 * check-tree-health.cjs
 *
 * Computes spec/ tree health from disk and fails (exit 1) if below threshold.
 * Designed as a CI gate to lock progress and prevent regressions.
 *
 * Metrics (each module folder under spec/ is a unit):
 *   +1 if 00-overview.md present
 *   +1 if 99-consistency-report.md present
 *   +1 if 97-acceptance-criteria.md present
 *   +1 if 98-changelog.md present (optional, soft credit)
 *
 * Score = (sum of credits / max possible credits) * 100
 *
 * Usage:
 *   node linter-scripts/check-tree-health.cjs            # uses default threshold 75
 *   node linter-scripts/check-tree-health.cjs --min=80   # custom threshold
 *   node linter-scripts/check-tree-health.cjs --report   # print per-module breakdown
 */
const fs = require('fs');
const path = require('path');

const SPEC_DIR = path.resolve(__dirname, '..', 'spec');
const ARCHIVE_PREFIX = '_archive';

const args = process.argv.slice(2);
const minArg = args.find((a) => a.startsWith('--min='));
const MIN_SCORE = minArg ? parseInt(minArg.split('=')[1], 10) : 75;
const SHOW_REPORT = args.includes('--report');

const REQUIRED = ['00-overview.md', '99-consistency-report.md'];
const RECOMMENDED = ['97-acceptance-criteria.md', '98-changelog.md'];

function listModules(dir, prefix = '') {
  const out = [];
  for (const entry of fs.readdirSync(dir, { withFileTypes: true })) {
    if (!entry.isDirectory()) continue;
    if (entry.name.startsWith('.')) continue;
    if (entry.name === ARCHIVE_PREFIX) continue;
    const full = path.join(dir, entry.name);
    const rel = prefix ? `${prefix}/${entry.name}` : entry.name;
    out.push({ rel, full });
    // Recurse one level deeper for nested modules
    for (const sub of fs.readdirSync(full, { withFileTypes: true })) {
      if (!sub.isDirectory()) continue;
      if (sub.name.startsWith('.')) continue;
      const subFull = path.join(full, sub.name);
      // Only count as a module if it has its own 00-overview.md
      if (fs.existsSync(path.join(subFull, '00-overview.md'))) {
        out.push({ rel: `${rel}/${sub.name}`, full: subFull });
      }
    }
  }
  return out;
}

function scoreModule(modPath) {
  const credits = { required: 0, recommended: 0, missing: [] };
  for (const f of REQUIRED) {
    if (fs.existsSync(path.join(modPath, f))) credits.required += 1;
    else credits.missing.push(f);
  }
  for (const f of RECOMMENDED) {
    if (fs.existsSync(path.join(modPath, f))) credits.recommended += 1;
  }
  return credits;
}

function main() {
  const modules = listModules(SPEC_DIR);
  const maxRequired = modules.length * REQUIRED.length;
  const maxRecommended = modules.length * RECOMMENDED.length;

  let totalRequired = 0;
  let totalRecommended = 0;
  const breakdown = [];

  for (const mod of modules) {
    const c = scoreModule(mod.full);
    totalRequired += c.required;
    totalRecommended += c.recommended;
    breakdown.push({ rel: mod.rel, ...c });
  }

  // Required is weighted 75%, recommended 25%
  const requiredPct = (totalRequired / maxRequired) * 75;
  const recommendedPct = (totalRecommended / maxRecommended) * 25;
  const score = Math.round(requiredPct + recommendedPct);

  console.log('━━━ Spec Tree Health ━━━');
  console.log(`Modules scanned:        ${modules.length}`);
  console.log(`Required files present: ${totalRequired} / ${maxRequired}`);
  console.log(`Recommended present:    ${totalRecommended} / ${maxRecommended}`);
  console.log(`Score:                  ${score} / 100`);
  console.log(`Threshold:              ${MIN_SCORE}`);
  console.log('');

  if (SHOW_REPORT) {
    console.log('━━━ Per-module breakdown ━━━');
    for (const b of breakdown.sort((a, b) => b.missing.length - a.missing.length)) {
      const status = b.missing.length === 0 ? '✓' : '✗';
      console.log(
        `${status} ${b.rel.padEnd(60)} req=${b.required}/${REQUIRED.length} rec=${b.recommended}/${RECOMMENDED.length}` +
          (b.missing.length ? ` missing: ${b.missing.join(', ')}` : '')
      );
    }
    console.log('');
  }

  if (score < MIN_SCORE) {
    console.error(`✗ FAIL: tree health ${score} is below threshold ${MIN_SCORE}`);
    process.exit(1);
  }
  console.log(`✓ PASS: tree health ${score} ≥ threshold ${MIN_SCORE}`);
}

main();
