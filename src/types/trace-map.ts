/**
 * Shape of `.lovable/memory/audit/trace-map.json` produced by
 * `linter-scripts/generate-trace-map.py`. Mirrored at /trace-map.json.
 */
export type TraceKind =
  | "function"
  | "endpoint"
  | "config"
  | "workflow"
  | "cli-flag"
  | "env-var"
  | null;

export interface CodeTarget {
  file: string;
  symbol: string | null;
  kind: TraceKind;
  note: string | null;
}

export interface TraceMapSummary {
  ac_total: number;
  ac_traced: number;
  ac_drifted: number;
  code_total: number;
  code_referenced: number;
  code_orphan: number;
  trace_entries: number;
  missing_ac: number;
  missing_file: number;
}

export interface TraceMap {
  ac_to_code: Record<string, CodeTarget[]>;
  code_to_ac: Record<string, string[]>;
  drift: string[];
  orphan: string[];
  errors: { missing_ac: string[]; missing_file: string[] };
  summary: TraceMapSummary;
}

/** "27-spec-toolchain/31-audit-spec-vs-code-v2.md#AC-31-06" → module folder. */
export function moduleOf(acId: string): string {
  const slash = acId.indexOf("/");
  return slash === -1 ? acId : acId.slice(0, slash);
}

/** AC id → just the AC code, e.g. "AC-31-06". */
export function acCode(acId: string): string {
  const hash = acId.indexOf("#");
  return hash === -1 ? acId : acId.slice(hash + 1);
}

/** AC id → spec relpath (everything before #). */
export function specPath(acId: string): string {
  const hash = acId.indexOf("#");
  return hash === -1 ? acId : acId.slice(0, hash);
}
