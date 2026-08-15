import React, { useEffect, useState } from 'react';
import { useParams, Link } from 'react-router-dom';
import { useQuery } from '@tanstack/react-query';
import { apiClient } from '../lib/apiClient';
import { Pipeline } from '../types/models';
import { ErrorBanner } from '../components/ErrorBanner';
import { Button } from '@/components/ui/button';
import { ArrowLeft, RefreshCw, Terminal, AlertTriangle } from 'lucide-react';

const PipelineDetail: React.FC = () => {
  const { id } = useParams<{ id: string }>();
  const [logs, setLogs] = useState<string[]>([]);
  const [isStreaming, setIsStreaming] = useState(false);

  const { data: pipeline, error, isLoading } = useQuery({
    queryKey: ['pipeline', id],
    queryFn: async () => {
      const response = await apiClient.get(`/git-logs/v2/pipelines/${id}`);
      return response.Results as Pipeline;
    },
  });

  // Simulated NDJSON Fetch for the demo (since native fetch isn't using axios for streams)
  const fetchLogs = async () => {
    setIsStreaming(true);
    setLogs([]);
    try {
      // In a real app, we'd use `fetch` with a `ReadableStream` and NDJSON parsing.
      // For now, we mock some logs.
      const mockLogs = [
        '[INFO] Starting pipeline execution...',
        '[INFO] Checking out branch...',
        '[DEBUG] Resolving dependencies...',
        pipeline?.HasError ? '[ERROR] Build failed with exit code 1' : '[INFO] Build successful.',
      ];
      
      for (const log of mockLogs) {
        await new Promise(r => setTimeout(r, 500));
        setLogs(prev => [...prev, log]);
      }
    } finally {
      setIsStreaming(false);
    }
  };

  useEffect(() => {
    if (pipeline) {
      fetchLogs();
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [pipeline]);

  return (
    <div className="space-y-6 h-full flex flex-col">
      <div className="flex items-center gap-4">
        <Button variant="ghost" size="icon" asChild>
          <Link to="/pipelines"><ArrowLeft className="h-4 w-4" /></Link>
        </Button>
        <div>
          <h1 className="text-2xl font-bold tracking-tight">Pipeline Logs</h1>
          <p className="text-sm text-muted-foreground">
            {pipeline?.Pipeline} • {pipeline?.Branch}
          </p>
        </div>
        <div className="ml-auto flex gap-2">
          {pipeline?.HasError && (
            <Button variant="outline" className="text-destructive">
              <AlertTriangle className="mr-2 h-4 w-4" /> Clear Error
            </Button>
          )}
          <Button variant="outline" onClick={fetchLogs} disabled={isStreaming}>
            <RefreshCw className={`mr-2 h-4 w-4 ${isStreaming ? 'animate-spin' : ''}`} />
            Refresh
          </Button>
        </div>
      </div>

      {error && <ErrorBanner error={error} />}

      <div className="flex-1 min-h-[400px] bg-zinc-950 rounded-xl border p-4 font-mono text-sm overflow-auto text-zinc-300">
        <div className="flex items-center gap-2 text-zinc-500 mb-4 pb-2 border-b border-zinc-800">
          <Terminal className="h-4 w-4" />
          <span>Execution Output</span>
        </div>
        
        {isLoading ? (
          <div className="animate-pulse flex flex-col gap-2">
            <div className="h-4 w-1/3 bg-zinc-800 rounded"></div>
            <div className="h-4 w-1/2 bg-zinc-800 rounded"></div>
          </div>
        ) : (
          <div className="space-y-1">
            {logs.map((log, i) => (
              <div 
                key={i} 
                className={`${log.includes('[ERROR]') ? 'text-red-400' : log.includes('[WARN]') ? 'text-yellow-400' : ''}`}
              >
                {log}
              </div>
            ))}
            {isStreaming && (
              <div className="flex items-center gap-2 text-zinc-500 mt-4">
                <span className="animate-pulse">_</span>
              </div>
            )}
          </div>
        )}
      </div>
    </div>
  );
};

export default PipelineDetail;
