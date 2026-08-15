import { useState } from "react";
import { useParams, Link } from "react-router-dom";
import { useQuery } from "@tanstack/react-query";
import { apiClient } from "@/lib/api-client";
import { ErrorBanner } from "@/components/ErrorBanner";
import { Button } from "@/components/ui/button";
import { ArrowLeft, Terminal, AlertTriangle } from "lucide-react";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import { ScrollArea } from "@/components/ui/scroll-area";
import { Badge } from "@/components/ui/badge";

interface LogEntry {
  LogEntryId: number;
  LineNumber: number;
  LogText: string;
  LogSeverityId: number;
  OccurredAt: number;
}

interface LogResponse {
  PipelineId: string;
  Sha: string;
  Logs: LogEntry[];
}

export const LogViewer = () => {
  const { id } = useParams<{ id: string }>();
  const [tab, setTab] = useState<"all" | "errors">("all");

  const { data: logsData, error: logsError, isLoading: logsLoading } = useQuery<LogResponse>({
    queryKey: ['pipeline-logs', id, tab],
    queryFn: async () => apiClient.get(`/pipelines/${id}/${tab === 'errors' ? 'errors' : 'logs'}`),
  });

  return (
    <div className="space-y-4 flex flex-col h-[calc(100vh-8rem)]">
      <div className="flex items-center gap-4">
        <Button variant="outline" size="icon" asChild>
          <Link to="/pipelines"><ArrowLeft className="h-4 w-4" /></Link>
        </Button>
        <div>
          <h1 className="text-2xl font-bold tracking-tight flex items-center gap-2">
            <Terminal className="h-5 w-5 text-muted-foreground" /> 
            Pipeline Execution Logs
          </h1>
          <div className="text-sm text-muted-foreground flex items-center gap-2 mt-1">
            Pipeline #{id} 
            {logsData && <Badge variant="secondary" className="font-mono">{logsData.Sha.substring(0, 7)}</Badge>}
          </div>
        </div>
      </div>

      {logsError && <ErrorBanner error={logsError} />}

      <Tabs value={tab} onValueChange={(v) => setTab(v as "all" | "errors")} className="flex-1 flex flex-col min-h-0">
        <div className="flex items-center justify-between border-b pb-2">
          <TabsList>
            <TabsTrigger value="all">All Logs</TabsTrigger>
            <TabsTrigger value="errors">Errors Only</TabsTrigger>
          </TabsList>
          
          <div className="text-sm text-muted-foreground">
            {logsData ? `${logsData.Logs.length} lines loaded` : 'Loading...'}
          </div>
        </div>

        <TabsContent value={tab} className="flex-1 min-h-0 mt-4 border rounded-md bg-slate-950 text-slate-50 font-mono text-sm overflow-hidden">
          {logsLoading ? (
            <div className="p-8 text-center text-slate-400">Fetching logs from split database...</div>
          ) : logsData?.Logs.length === 0 ? (
            <div className="p-8 text-center text-slate-400">
              {tab === 'errors' ? (
                <div className="flex flex-col items-center justify-center gap-2">
                  <span className="text-green-400">No errors found in this run!</span>
                </div>
              ) : (
                'Log file is empty.'
              )}
            </div>
          ) : (
            <ScrollArea className="h-full w-full">
              <div className="p-4 flex flex-col min-w-max">
                {logsData?.Logs.map((log) => {
                  const isError = log.LogSeverityId >= 4; // 4=Error, 5=Fatal in default seed
                  return (
                    <div 
                      key={log.LogEntryId} 
                      className={`flex gap-4 hover:bg-slate-900 px-2 py-0.5 rounded transition-colors ${
                        isError ? 'text-red-400 bg-red-950/20' : 'text-slate-300'
                      }`}
                    >
                      <span className="opacity-40 select-none w-12 text-right">{log.LineNumber}</span>
                      <span className="whitespace-pre">{log.LogText}</span>
                    </div>
                  );
                })}
              </div>
            </ScrollArea>
          )}
        </TabsContent>
      </Tabs>
    </div>
  );
};

export default LogViewer;
