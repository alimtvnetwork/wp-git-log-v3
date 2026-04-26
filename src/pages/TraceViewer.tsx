import { useEffect, useMemo, useState } from "react";
import { Link } from "react-router-dom";
import {
  AlertCircle,
  ArrowLeft,
  Check,
  Copy,
  ExternalLink,
  FileCode,
  FileText,
  Filter,
  Search,
} from "lucide-react";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { Card } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { ScrollArea } from "@/components/ui/scroll-area";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { Separator } from "@/components/ui/separator";
import { ToggleGroup, ToggleGroupItem } from "@/components/ui/toggle-group";
import { toast } from "@/hooks/use-toast";
import {
  acCode,
  moduleOf,
  specPath,
  type CodeTarget,
  type TraceKind,
  type TraceMap,
} from "@/types/trace-map";

type StatusFilter = "all" | "traced" | "drift" | "orphan";

/** Optional repo base for "open in GitHub" — set via window.__TRACE_REPO__ or fallback. */
const DEFAULT_REPO_BASE =
  (typeof window !== "undefined" &&
    (window as unknown as { __TRACE_REPO__?: string }).__TRACE_REPO__) ||
  "";

const KIND_VARIANT: Record<string, string> = {
  function: "bg-primary/10 text-primary border-primary/20",
  endpoint: "bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-500/20",
  config: "bg-amber-500/10 text-amber-700 dark:text-amber-400 border-amber-500/20",
  workflow: "bg-purple-500/10 text-purple-600 dark:text-purple-400 border-purple-500/20",
  "cli-flag": "bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20",
  "env-var": "bg-pink-500/10 text-pink-600 dark:text-pink-400 border-pink-500/20",
};

function KindBadge({ kind }: { kind: TraceKind }) {
  if (!kind) return null;
  return (
    <span
      className={`inline-flex items-center rounded-md border px-1.5 py-0.5 text-[10px] font-mono uppercase tracking-wide ${
        KIND_VARIANT[kind] ?? "bg-muted text-muted-foreground border-border"
      }`}
    >
      {kind}
    </span>
  );
}

async function copy(text: string, label: string) {
  try {
    await navigator.clipboard.writeText(text);
    toast({ title: "Copied", description: `${label}: ${text}` });
  } catch {
    toast({
      title: "Copy failed",
      description: "Clipboard not available in this context.",
      variant: "destructive",
    });
  }
}

function githubUrl(file: string, repoBase: string): string | null {
  if (!repoBase) return null;
  return `${repoBase.replace(/\/$/, "")}/blob/main/${file}`;
}

const TraceViewer = () => {
  const [data, setData] = useState<TraceMap | null>(null);
  const [error, setError] = useState<string | null>(null);
  const [search, setSearch] = useState("");
  const [statusFilter, setStatusFilter] = useState<StatusFilter>("all");
  const [moduleFilter, setModuleFilter] = useState<string>("all");
  const [kindFilter, setKindFilter] = useState<string>("all");
  // Selection: either an AC id (left side) or a code file (right-side flip).
  const [selectedAc, setSelectedAc] = useState<string | null>(null);
  const [selectedFile, setSelectedFile] = useState<string | null>(null);
  const [repoBase, setRepoBase] = useState(DEFAULT_REPO_BASE);

  useEffect(() => {
    fetch("/trace-map.json", { cache: "no-cache" })
      .then((r) => {
        if (!r.ok) throw new Error(`HTTP ${r.status}`);
        return r.json() as Promise<TraceMap>;
      })
      .then((d) => setData(d))
      .catch((e: unknown) =>
        setError(e instanceof Error ? e.message : String(e)),
      );
  }, []);

  // ----- derived collections -----
  const allAcRows = useMemo(() => {
    if (!data) return [];
    const traced = Object.entries(data.ac_to_code).map(([id, targets]) => ({
      id,
      status: "traced" as const,
      targets,
    }));
    const drifted = data.drift.map((id) => ({
      id,
      status: "drift" as const,
      targets: [] as CodeTarget[],
    }));
    return [...traced, ...drifted].sort((a, b) => a.id.localeCompare(b.id));
  }, [data]);

  const orphanRows = useMemo(() => {
    if (!data) return [];
    return data.orphan.map((file) => ({
      id: file,
      status: "orphan" as const,
      file,
    }));
  }, [data]);

  const modules = useMemo(() => {
    const set = new Set<string>();
    allAcRows.forEach((r) => set.add(moduleOf(r.id)));
    return ["all", ...Array.from(set).sort()];
  }, [allAcRows]);

  // ----- filter pipeline -----
  const filteredAc = useMemo(() => {
    const q = search.trim().toLowerCase();
    return allAcRows.filter((row) => {
      if (statusFilter !== "all" && statusFilter !== "orphan" && row.status !== statusFilter)
        return false;
      if (statusFilter === "orphan") return false; // handled separately
      if (moduleFilter !== "all" && moduleOf(row.id) !== moduleFilter) return false;
      if (kindFilter !== "all") {
        if (row.status === "drift") return false;
        if (!row.targets.some((t) => t.kind === kindFilter)) return false;
      }
      if (q) {
        const hay = [
          row.id,
          ...row.targets.map((t) => `${t.file} ${t.symbol ?? ""} ${t.kind ?? ""}`),
        ]
          .join(" ")
          .toLowerCase();
        if (!hay.includes(q)) return false;
      }
      return true;
    });
  }, [allAcRows, search, statusFilter, moduleFilter, kindFilter]);

  const filteredOrphans = useMemo(() => {
    if (statusFilter !== "all" && statusFilter !== "orphan") return [];
    const q = search.trim().toLowerCase();
    return orphanRows.filter((r) => !q || r.file.toLowerCase().includes(q));
  }, [orphanRows, search, statusFilter]);

  // ----- detail data -----
  const detailAc = selectedAc && data ? data.ac_to_code[selectedAc] ?? [] : null;
  const detailFileAcs =
    selectedFile && data ? data.code_to_ac[selectedFile] ?? [] : null;

  // ===== render =====
  if (error) {
    return (
      <div className="min-h-screen bg-background flex items-center justify-center p-8">
        <Card className="p-6 max-w-md">
          <div className="flex items-start gap-3">
            <AlertCircle className="h-5 w-5 text-destructive mt-0.5" />
            <div>
              <h2 className="font-semibold">Failed to load trace map</h2>
              <p className="text-sm text-muted-foreground mt-1">{error}</p>
              <p className="text-xs text-muted-foreground mt-3">
                Expected at <code className="font-mono">/trace-map.json</code>.
                Run{" "}
                <code className="font-mono">
                  python3 linter-scripts/generate-trace-map.py
                </code>{" "}
                then copy{" "}
                <code className="font-mono">
                  .lovable/memory/audit/trace-map.json
                </code>{" "}
                to <code className="font-mono">public/</code>.
              </p>
            </div>
          </div>
        </Card>
      </div>
    );
  }

  if (!data) {
    return (
      <div className="min-h-screen bg-background flex items-center justify-center">
        <p className="text-muted-foreground text-sm">Loading trace map…</p>
      </div>
    );
  }

  const s = data.summary;

  return (
    <div className="min-h-screen bg-background">
      {/* Header */}
      <header className="border-b bg-card">
        <div className="max-w-[1600px] mx-auto px-6 py-4">
          <div className="flex items-center justify-between gap-4 flex-wrap">
            <div className="flex items-center gap-3">
              <Link
                to="/"
                className="inline-flex items-center gap-1.5 text-xs text-muted-foreground hover:text-foreground"
              >
                <ArrowLeft className="h-3.5 w-3.5" />
                Home
              </Link>
              <Separator orientation="vertical" className="h-4" />
              <h1 className="text-lg font-semibold tracking-tight">
                Spec ↔ Code Trace Viewer
              </h1>
            </div>
            <div className="flex items-center gap-2">
              <Input
                value={repoBase}
                onChange={(e) => setRepoBase(e.target.value)}
                placeholder="Repo base URL (optional, e.g. https://github.com/org/repo)"
                className="h-8 w-[340px] text-xs font-mono"
              />
            </div>
          </div>

          {/* Summary chips */}
          <div className="flex flex-wrap gap-2 mt-3">
            <SummaryChip
              label="Traced"
              value={s.ac_traced}
              total={s.ac_total}
              tone="primary"
            />
            <SummaryChip label="Drift" value={s.ac_drifted} tone="warning" />
            <SummaryChip label="Orphan code" value={s.code_orphan} tone="warning" />
            <SummaryChip
              label="Code linked"
              value={s.code_referenced}
              total={s.code_total}
              tone="muted"
            />
            <SummaryChip
              label="Trace entries"
              value={s.trace_entries}
              tone="muted"
            />
            {(s.missing_ac > 0 || s.missing_file > 0) && (
              <SummaryChip
                label="Errors"
                value={s.missing_ac + s.missing_file}
                tone="destructive"
              />
            )}
          </div>
        </div>
      </header>

      {/* Filters */}
      <div className="border-b bg-muted/30">
        <div className="max-w-[1600px] mx-auto px-6 py-3 flex flex-wrap items-center gap-3">
          <div className="relative flex-1 min-w-[240px] max-w-md">
            <Search className="absolute left-2.5 top-1/2 -translate-y-1/2 h-3.5 w-3.5 text-muted-foreground" />
            <Input
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              placeholder="Search AC id, file path, symbol…"
              className="h-8 pl-8 text-sm"
            />
          </div>

          <ToggleGroup
            type="single"
            value={statusFilter}
            onValueChange={(v) => v && setStatusFilter(v as StatusFilter)}
            className="gap-0.5"
          >
            <ToggleGroupItem value="all" size="sm" className="h-8 text-xs">
              All
            </ToggleGroupItem>
            <ToggleGroupItem value="traced" size="sm" className="h-8 text-xs">
              Traced
            </ToggleGroupItem>
            <ToggleGroupItem value="drift" size="sm" className="h-8 text-xs">
              Drift
            </ToggleGroupItem>
            <ToggleGroupItem value="orphan" size="sm" className="h-8 text-xs">
              Orphan
            </ToggleGroupItem>
          </ToggleGroup>

          <Select value={moduleFilter} onValueChange={setModuleFilter}>
            <SelectTrigger className="h-8 w-[220px] text-xs">
              <Filter className="h-3.5 w-3.5 mr-1" />
              <SelectValue />
            </SelectTrigger>
            <SelectContent>
              {modules.map((m) => (
                <SelectItem key={m} value={m} className="text-xs font-mono">
                  {m === "all" ? "All modules" : m}
                </SelectItem>
              ))}
            </SelectContent>
          </Select>

          <Select value={kindFilter} onValueChange={setKindFilter}>
            <SelectTrigger className="h-8 w-[160px] text-xs">
              <SelectValue />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="all" className="text-xs">
                All kinds
              </SelectItem>
              {["function", "endpoint", "config", "workflow", "cli-flag", "env-var"].map(
                (k) => (
                  <SelectItem key={k} value={k} className="text-xs font-mono">
                    {k}
                  </SelectItem>
                ),
              )}
            </SelectContent>
          </Select>

          <div className="text-xs text-muted-foreground ml-auto">
            {filteredAc.length} AC{filteredAc.length === 1 ? "" : "s"}
            {filteredOrphans.length > 0 && ` · ${filteredOrphans.length} orphan`}
          </div>
        </div>
      </div>

      {/* Split pane */}
      <main className="max-w-[1600px] mx-auto px-6 py-4 grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_minmax(0,1.1fr)] gap-4">
        {/* LEFT: AC list */}
        <Card className="overflow-hidden">
          <div className="px-4 py-2 border-b bg-muted/40 text-xs font-medium uppercase tracking-wide text-muted-foreground">
            Acceptance Criteria
          </div>
          <ScrollArea className="h-[calc(100vh-260px)]">
            <ul className="divide-y">
              {filteredAc.map((row) => {
                const active = row.id === selectedAc;
                return (
                  <li key={row.id}>
                    <button
                      onClick={() => {
                        setSelectedAc(row.id);
                        setSelectedFile(null);
                      }}
                      className={`w-full text-left px-4 py-2.5 hover:bg-accent transition-colors ${
                        active ? "bg-accent" : ""
                      }`}
                    >
                      <div className="flex items-start gap-2">
                        <FileText className="h-3.5 w-3.5 mt-0.5 shrink-0 text-muted-foreground" />
                        <div className="min-w-0 flex-1">
                          <div className="flex items-center gap-2 flex-wrap">
                            <span className="font-mono text-xs font-semibold">
                              {acCode(row.id)}
                            </span>
                            {row.status === "drift" ? (
                              <Badge
                                variant="outline"
                                className="h-4 px-1.5 text-[10px] border-amber-500/40 text-amber-700 dark:text-amber-400"
                              >
                                drift
                              </Badge>
                            ) : (
                              <Badge
                                variant="outline"
                                className="h-4 px-1.5 text-[10px] border-primary/40 text-primary"
                              >
                                {row.targets.length} target
                                {row.targets.length === 1 ? "" : "s"}
                              </Badge>
                            )}
                          </div>
                          <div className="text-xs text-muted-foreground font-mono truncate mt-0.5">
                            {specPath(row.id)}
                          </div>
                        </div>
                      </div>
                    </button>
                  </li>
                );
              })}

              {filteredOrphans.length > 0 && (
                <li className="px-4 py-2 bg-muted/30 text-[10px] font-medium uppercase tracking-wide text-muted-foreground">
                  Orphan code (no AC)
                </li>
              )}
              {filteredOrphans.map((row) => {
                const active = row.file === selectedFile;
                return (
                  <li key={row.file}>
                    <button
                      onClick={() => {
                        setSelectedFile(row.file);
                        setSelectedAc(null);
                      }}
                      className={`w-full text-left px-4 py-2.5 hover:bg-accent transition-colors ${
                        active ? "bg-accent" : ""
                      }`}
                    >
                      <div className="flex items-start gap-2">
                        <FileCode className="h-3.5 w-3.5 mt-0.5 shrink-0 text-amber-600" />
                        <div className="min-w-0 flex-1">
                          <div className="font-mono text-xs truncate">{row.file}</div>
                          <div className="text-[10px] text-muted-foreground mt-0.5">
                            no spec coverage
                          </div>
                        </div>
                      </div>
                    </button>
                  </li>
                );
              })}

              {filteredAc.length === 0 && filteredOrphans.length === 0 && (
                <li className="p-8 text-center text-sm text-muted-foreground">
                  No matches.
                </li>
              )}
            </ul>
          </ScrollArea>
        </Card>

        {/* RIGHT: detail panel */}
        <Card className="overflow-hidden">
          <div className="px-4 py-2 border-b bg-muted/40 text-xs font-medium uppercase tracking-wide text-muted-foreground">
            {selectedFile ? "Code → ACs" : "AC → Code"}
          </div>
          <ScrollArea className="h-[calc(100vh-260px)]">
            <div className="p-5">
              {!selectedAc && !selectedFile && (
                <div className="text-center py-16 text-sm text-muted-foreground">
                  Select an AC on the left to see its code targets,
                  <br /> or click a code file to flip into reverse view.
                </div>
              )}

              {/* AC → Code */}
              {selectedAc && detailAc !== null && (
                <div className="space-y-4">
                  <div>
                    <div className="text-[10px] uppercase tracking-wide text-muted-foreground mb-1">
                      Acceptance Criterion
                    </div>
                    <h2 className="font-mono text-lg font-semibold">
                      {acCode(selectedAc)}
                    </h2>
                    <button
                      onClick={() => copy(selectedAc, "AC id")}
                      className="text-xs text-muted-foreground hover:text-foreground font-mono mt-1 inline-flex items-center gap-1"
                    >
                      {specPath(selectedAc)} <Copy className="h-3 w-3" />
                    </button>
                  </div>

                  <Separator />

                  {detailAc.length === 0 ? (
                    <div className="rounded-md border border-amber-500/30 bg-amber-500/5 p-3 text-xs">
                      <div className="flex items-center gap-2 font-medium text-amber-700 dark:text-amber-400">
                        <AlertCircle className="h-4 w-4" />
                        Drift — no code link
                      </div>
                      <p className="mt-1 text-muted-foreground">
                        Add a <code className="font-mono">[[trace]]</code> entry
                        in <code className="font-mono">linter-scripts/trace-map.toml</code>{" "}
                        pointing to the file/symbol that satisfies this AC.
                      </p>
                    </div>
                  ) : (
                    <div>
                      <div className="text-[10px] uppercase tracking-wide text-muted-foreground mb-2">
                        Code targets ({detailAc.length})
                      </div>
                      <div className="space-y-2">
                        {detailAc.map((t, i) => (
                          <CodeTargetRow
                            key={`${t.file}-${i}`}
                            target={t}
                            repoBase={repoBase}
                            onFileClick={() => {
                              setSelectedFile(t.file);
                              setSelectedAc(null);
                            }}
                          />
                        ))}
                      </div>
                    </div>
                  )}
                </div>
              )}

              {/* Code → AC reverse view */}
              {selectedFile && detailFileAcs !== null && (
                <div className="space-y-4">
                  <div>
                    <div className="flex items-center gap-2">
                      <button
                        onClick={() => {
                          setSelectedFile(null);
                          if (detailFileAcs[0]) setSelectedAc(detailFileAcs[0]);
                        }}
                        className="inline-flex items-center gap-1 text-xs text-muted-foreground hover:text-foreground"
                      >
                        <ArrowLeft className="h-3 w-3" />
                        Back to AC view
                      </button>
                    </div>
                    <div className="text-[10px] uppercase tracking-wide text-muted-foreground mt-2 mb-1">
                      Code file
                    </div>
                    <h2 className="font-mono text-base font-semibold break-all">
                      {selectedFile}
                    </h2>
                    <div className="flex items-center gap-2 mt-1">
                      <Button
                        variant="outline"
                        size="sm"
                        className="h-7 text-xs"
                        onClick={() => copy(selectedFile, "File path")}
                      >
                        <Copy className="h-3 w-3 mr-1" /> Copy path
                      </Button>
                      {githubUrl(selectedFile, repoBase) && (
                        <Button
                          variant="outline"
                          size="sm"
                          className="h-7 text-xs"
                          asChild
                        >
                          <a
                            href={githubUrl(selectedFile, repoBase) ?? "#"}
                            target="_blank"
                            rel="noreferrer"
                          >
                            <ExternalLink className="h-3 w-3 mr-1" /> Open on GitHub
                          </a>
                        </Button>
                      )}
                    </div>
                  </div>

                  <Separator />

                  <div>
                    <div className="text-[10px] uppercase tracking-wide text-muted-foreground mb-2">
                      Backed acceptance criteria ({detailFileAcs.length})
                    </div>
                    {detailFileAcs.length === 0 ? (
                      <div className="rounded-md border border-amber-500/30 bg-amber-500/5 p-3 text-xs">
                        <div className="flex items-center gap-2 font-medium text-amber-700 dark:text-amber-400">
                          <AlertCircle className="h-4 w-4" />
                          Orphan — no spec coverage
                        </div>
                      </div>
                    ) : (
                      <ul className="space-y-1.5">
                        {detailFileAcs.map((id) => (
                          <li key={id}>
                            <button
                              onClick={() => {
                                setSelectedAc(id);
                                setSelectedFile(null);
                              }}
                              className="w-full text-left rounded-md border bg-card hover:bg-accent transition-colors px-3 py-2"
                            >
                              <div className="flex items-center gap-2">
                                <span className="font-mono text-xs font-semibold">
                                  {acCode(id)}
                                </span>
                                <span className="font-mono text-[11px] text-muted-foreground truncate">
                                  {specPath(id)}
                                </span>
                              </div>
                            </button>
                          </li>
                        ))}
                      </ul>
                    )}
                  </div>
                </div>
              )}
            </div>
          </ScrollArea>
        </Card>
      </main>
    </div>
  );
};

// ─── helper components ─────────────────────────────────────────────

function SummaryChip({
  label,
  value,
  total,
  tone,
}: {
  label: string;
  value: number;
  total?: number;
  tone: "primary" | "warning" | "muted" | "destructive";
}) {
  const toneClass =
    tone === "primary"
      ? "border-primary/30 bg-primary/5 text-primary"
      : tone === "warning"
      ? "border-amber-500/30 bg-amber-500/5 text-amber-700 dark:text-amber-400"
      : tone === "destructive"
      ? "border-destructive/30 bg-destructive/5 text-destructive"
      : "border-border bg-muted/40 text-muted-foreground";
  return (
    <div
      className={`inline-flex items-baseline gap-1.5 rounded-md border px-2.5 py-1 text-xs ${toneClass}`}
    >
      <span className="font-medium">{label}</span>
      <span className="font-mono font-semibold">
        {value}
        {typeof total === "number" && (
          <span className="opacity-60">/{total}</span>
        )}
      </span>
    </div>
  );
}

function CodeTargetRow({
  target,
  repoBase,
  onFileClick,
}: {
  target: CodeTarget;
  repoBase: string;
  onFileClick: () => void;
}) {
  const [copied, setCopied] = useState(false);
  const ref = `${target.file}${target.symbol ? `#${target.symbol}` : ""}`;
  const url = githubUrl(target.file, repoBase);

  return (
    <div className="rounded-md border bg-card hover:bg-accent/40 transition-colors p-3">
      <div className="flex items-start justify-between gap-2">
        <button
          onClick={onFileClick}
          className="text-left flex-1 min-w-0 group"
          title="Show all ACs that reference this file"
        >
          <div className="flex items-center gap-2 flex-wrap">
            <FileCode className="h-3.5 w-3.5 text-muted-foreground" />
            <span className="font-mono text-sm font-medium group-hover:underline break-all">
              {target.file}
            </span>
            <KindBadge kind={target.kind} />
          </div>
          {target.symbol && (
            <div className="font-mono text-xs text-muted-foreground mt-1 ml-5">
              <span className="text-foreground/70">symbol:</span> {target.symbol}
            </div>
          )}
          {target.note && (
            <div className="text-xs text-muted-foreground mt-1.5 ml-5 italic">
              {target.note}
            </div>
          )}
        </button>
        <div className="flex items-center gap-1 shrink-0">
          <Button
            variant="ghost"
            size="sm"
            className="h-7 w-7 p-0"
            title="Copy file#symbol"
            onClick={async () => {
              await copy(ref, "Reference");
              setCopied(true);
              setTimeout(() => setCopied(false), 1200);
            }}
          >
            {copied ? <Check className="h-3.5 w-3.5" /> : <Copy className="h-3.5 w-3.5" />}
          </Button>
          {url && (
            <Button
              variant="ghost"
              size="sm"
              className="h-7 w-7 p-0"
              title="Open on GitHub"
              asChild
            >
              <a href={url} target="_blank" rel="noreferrer">
                <ExternalLink className="h-3.5 w-3.5" />
              </a>
            </Button>
          )}
        </div>
      </div>
    </div>
  );
}

export default TraceViewer;
