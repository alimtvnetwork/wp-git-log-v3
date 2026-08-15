import React from 'react';
import { useQuery } from '@tanstack/react-query';
import { apiClient } from '../lib/apiClient';
import { Pipeline } from '../types/models';
import { ErrorBanner } from '../components/ErrorBanner';
import { Button } from '@/components/ui/button';
import { GitMerge, CheckCircle, XCircle } from 'lucide-react';
import { Link } from 'react-router-dom';

const Pipelines: React.FC = () => {
  const { data, error, isLoading } = useQuery({
    queryKey: ['pipelines'],
    queryFn: async () => {
      // In a real app, we'd fetch all pipelines or filter by repo.
      // For now, we assume an endpoint exists that returns recent pipelines.
      const response = await apiClient.get('/git-logs/v2/pipelines');
      return response.Results as Pipeline[];
    },
  });

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold tracking-tight">Pipelines</h1>
          <p className="text-muted-foreground">
            View CI/CD pipeline runs and their execution history.
          </p>
        </div>
      </div>

      {error && <ErrorBanner error={error} />}

      {isLoading ? (
        <div className="animate-pulse h-64 bg-card rounded-xl border"></div>
      ) : (
        <div className="border rounded-xl bg-card">
          <table className="w-full text-sm text-left">
            <thead className="text-xs text-muted-foreground uppercase bg-muted/50 border-b">
              <tr>
                <th className="px-6 py-3">Status</th>
                <th className="px-6 py-3">Pipeline / Branch</th>
                <th className="px-6 py-3">RepoVersion ID</th>
                <th className="px-6 py-3">Last Updated</th>
                <th className="px-6 py-3 text-right">Actions</th>
              </tr>
            </thead>
            <tbody>
              {data?.length === 0 ? (
                <tr>
                  <td colSpan={5} className="px-6 py-8 text-center text-muted-foreground">
                    No Pipeline runs found.
                  </td>
                </tr>
              ) : (
                data?.map((pipeline) => (
                  <tr key={pipeline.PipelineId} className="border-b last:border-0 hover:bg-muted/30">
                    <td className="px-6 py-4">
                      {pipeline.HasError ? (
                        <div className="flex items-center gap-2 text-destructive">
                          <XCircle className="h-5 w-5" />
                          <span className="font-medium">Failed</span>
                        </div>
                      ) : (
                        <div className="flex items-center gap-2 text-primary">
                          <CheckCircle className="h-5 w-5" />
                          <span className="font-medium">Success</span>
                        </div>
                      )}
                    </td>
                    <td className="px-6 py-4 font-medium">
                      <div className="flex items-center gap-2">
                        <GitMerge className="h-4 w-4 text-muted-foreground" />
                        {pipeline.Pipeline}
                      </div>
                      <div className="text-xs text-muted-foreground mt-1">
                        branch: {pipeline.Branch}
                      </div>
                    </td>
                    <td className="px-6 py-4 font-mono">{pipeline.RepoVersionId}</td>
                    <td className="px-6 py-4">
                      {new Date(pipeline.UpdatedAt * 1000).toLocaleString()}
                    </td>
                    <td className="px-6 py-4 text-right">
                      <Button variant="ghost" size="sm" asChild>
                        <Link to={`/pipelines/${pipeline.PipelineId}`}>View Logs</Link>
                      </Button>
                    </td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>
      )}
    </div>
  );
};

export default Pipelines;
