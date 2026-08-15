import React, { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { apiClient } from '../lib/apiClient';
import { GitProfile } from '../types/models';
import { ErrorBanner } from '../components/ErrorBanner';
import { Button } from '@/components/ui/button';
import { Shield, Plus } from 'lucide-react';

const GitProfiles: React.FC = () => {
  const [isCreating, setIsCreating] = useState(false);
  const queryClient = useQueryClient();

  const { data, error, isLoading } = useQuery({
    queryKey: ['git-profiles'],
    queryFn: async () => {
      const response = await apiClient.get('/git-logs/v2/git-profiles');
      return response.Results as GitProfile[];
    },
  });

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold tracking-tight">Git Profiles</h1>
          <p className="text-muted-foreground">
            Manage repository access profiles and acceptance rules.
          </p>
        </div>
        <Button onClick={() => setIsCreating(true)}>
          <Plus className="mr-2 h-4 w-4" /> Add Profile
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
                <th className="px-6 py-3">Owner</th>
                <th className="px-6 py-3">Organization</th>
                <th className="px-6 py-3">Rules</th>
                <th className="px-6 py-3 text-right">Actions</th>
              </tr>
            </thead>
            <tbody>
              {data?.length === 0 ? (
                <tr>
                  <td colSpan={5} className="px-6 py-8 text-center text-muted-foreground">
                    No Git Profiles found. Create one to get started.
                  </td>
                </tr>
              ) : (
                data?.map((profile) => (
                  <tr key={profile.GitProfileId} className="border-b last:border-0 hover:bg-muted/30">
                    <td className="px-6 py-4 font-mono">{profile.GitProfileId}</td>
                    <td className="px-6 py-4 font-medium">{profile.OwnerName}</td>
                    <td className="px-6 py-4">
                      {profile.IsOrganization ? (
                        <span className="px-2 py-1 bg-primary/10 text-primary rounded-full text-xs">Org</span>
                      ) : (
                        <span className="px-2 py-1 bg-secondary/50 rounded-full text-xs">User</span>
                      )}
                    </td>
                    <td className="px-6 py-4">
                      {profile.IsRestrictInBranch && (
                        <span className="px-2 py-1 bg-destructive/10 text-destructive rounded-full text-xs">
                          Restricted: {profile.StrictBranch}
                        </span>
                      )}
                    </td>
                    <td className="px-6 py-4 text-right">
                      <Button variant="ghost" size="sm">Edit</Button>
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

export default GitProfiles;
