#!/usr/bin/env node
/**
 * check-tree-health.cjs
 * Computes spec/ tree health from disk and fails (exit 1) if below threshold.
 * Usage: node linter-scripts/check-tree-health.cjs [--min=70] [--report]
 */
const fs = require('fs');
const path = require('path');

const SPEC_DIR = path.resolve(__dirname, '..', 'spec');
const ARCHIVE_PREFIX = '_archive';
const args = process.argv.slice(2);
const minArg = args.find((a) => a.startsWith('--min='));
const MIN_SCORE = minArg ? parseInt(minArg.split('=')[1], 10) : 70;
const SHOW_REPORT = args.includes('--report');

const REQUIRED = ['00-overview.md', '99-consistency-report.md'];
const RECOMMENDED = ['97-acceptance-criteria.md', '98-changelog.md'];

function listModules(dir, prefix = '') {
  const out = [];
  for (const entry of fs.readdirSync(dir, { withFileTypes: true })) {
    if (!entry.isDirectory() || entry.name.startsWith('.') || entry.name === ARCHIVE_PREFIX) continue;
    const full = path.join(dir, entry.name);
    const rel = prefix ? `${prefix}/${entry.name}` : entry.name;
    out.push({ rel, full });
    for (const sub of fs.readdirSync(full, { withFileTypes: true })) {
      if (!sub.isDirectory() || sub.name.startsWith('.')) continue;
      const subFull = path.join(full, sub.name);
      if (fs.existsSync(path.join(subFull, '00-overview.md'))) {
        out.push({ rel: `${rel}/${sub.name}`, full: subFull });
      }
    }
  }
  return out;
}

function scoreModule(modPath) {
  const c = { required: 0, recommended: 0, missing: [] };
  for (const f of REQUIRED) {
    if (fs.existsSync(path.join(modPath, f))) c.required += 1;
    else c.missing.push(f);
  }
  for (const f of RECOMMENDED) {
    if (fs.existsSync(path.join(modPath, f))) c.recommended += 1;
  }
  return c;
}

function main() {
  const modules = listModules(SPEC_DIR);
  const maxR = modules.length * REQUIRED.length;
  const maxRec = modules.length * RECOMMENDED.length;
  let totR = 0, totRec = 0;
  const breakdown = [];
  for (const mod of modules) {
    const c = scoreModule(mod.full);
    totR += c.required; totRec += c.recommended;
    breakdown.push({ rel: mod.rel, ...c });
  }
  const score = Math.round((totR / maxR) * 75 + (totRec / maxRec) * 25);
  console.log('━━━ Spec Tree Health ━━━');
  console.log(`Modules scanned:        ${modules.length}`);
  console.log(`Required files present: ${totR} / ${maxR}`);
  console.log(`Recommended present:    ${totRec} / ${maxRec}`);
  console.log(`Score:                  ${score} / 100`);
  console.log(`Threshold:              ${MIN_SCORE}\n`);
  if (SHOW_REPORT) {
    console.log('━━━ Per-module breakdown ━━━');
    for (const b of breakdown.sort((a, b) => b.missing.length - a.missing.length)) {
      const status = b.missing.length === 0 ? '✓' : '✗';
      console.log(`${status} ${b.rel.padEnd(60)} req=${b.required}/${REQUIRED.length} rec=${b.recommended}/${RECOMMENDED.length}` + (b.missing.length ? ` missing: ${b.missing.join(', ')}` : ''));
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
