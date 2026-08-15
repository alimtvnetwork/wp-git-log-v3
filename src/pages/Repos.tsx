import React, { useState } from 'react';
import { useQuery } from '@tanstack/react-query';
import { apiClient } from '../lib/apiClient';
import { Repo } from '../types/models';
import { ErrorBanner } from '../components/ErrorBanner';
import { Button } from '@/components/ui/button';
import { Book, Plus } from 'lucide-react';

const Repos: React.FC = () => {
  const [isCreating, setIsCreating] = useState(false);

  const { data, error, isLoading } = useQuery({
    queryKey: ['repos'],
    queryFn: async () => {
      const response = await apiClient.get('/git-logs/v2/repos');
      return response.Results as Repo[];
    },
  });

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold tracking-tight">Repositories</h1>
          <p className="text-muted-foreground">
            Manage repositories that are accepting git logs.
          </p>
        </div>
        <Button onClick={() => setIsCreating(true)}>
          <Plus className="mr-2 h-4 w-4" /> Add Repository
        </Button>
      </div>

      {error && <ErrorBanner error={error} />}

      {isLoading ? (
        <div className="animate-pulse h-64 bg-card rounded-xl border"></div>
      ) : (
        <div className="border rounded-xl bg-card">
          <table className="w-full text-sm text-left">
            <thead className="text-xs text-muted-foreground uppercase bg-muted/50 border-b">
              <tr>
                <th className="px-6 py-3">ID</th>
                <th className="px-6 py-3">Repo Name</th>
                <th className="px-6 py-3">Profile ID</th>
                <th className="px-6 py-3">Created</th>
                <th className="px-6 py-3 text-right">Actions</th>
              </tr>
            </thead>
            <tbody>
              {data?.length === 0 ? (
                <tr>
                  <td colSpan={5} className="px-6 py-8 text-center text-muted-foreground">
                    No Repositories found. Add one to start logging.
                  </td>
                </tr>
              ) : (
                data?.map((repo) => (
                  <tr key={repo.RepoId} className="border-b last:border-0 hover:bg-muted/30">
                    <td className="px-6 py-4 font-mono">{repo.RepoId}</td>
                    <td className="px-6 py-4 font-medium flex items-center gap-2">
                      <Book className="h-4 w-4 text-muted-foreground" />
                      {repo.RootRepoName}
                    </td>
                    <td className="px-6 py-4">{repo.GitProfileId}</td>
                    <td className="px-6 py-4">
                      {new Date(repo.CreatedAt * 1000).toLocaleDateString()}
                    </td>
                    <td className="px-6 py-4 text-right">
                      <Button variant="ghost" size="sm" className="text-destructive">Delete</Button>
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

export default Repos;
