import React from 'react';
import { useQuery } from '@tanstack/react-query';
import { apiClient } from '../lib/apiClient';
import { ErrorBanner } from '../components/ErrorBanner';

const Dashboard: React.FC = () => {
  const { data, error, isLoading } = useQuery({
    queryKey: ['dashboard-stats'],
    queryFn: async () => {
      const response = await apiClient.get('/git-logs/v2/dashboard-stats');
      return response.Results;
    },
  });

  return (
    <div className="space-y-6">
      <h1 className="text-3xl font-bold tracking-tight">Dashboard</h1>
      <p className="text-muted-foreground">
        Overview of system status and repository metrics.
      </p>

      {error && (
        <ErrorBanner error={error} title="Failed to load dashboard metrics" />
      )}

      {isLoading ? (
        <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
          {[...Array(4)].map((_, i) => (
            <div key={i} className="rounded-xl border bg-card text-card-foreground shadow h-32 animate-pulse" />
          ))}
        </div>
      ) : (
        <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
          <StatCard title="Total Profiles" value={data?.totalProfiles} />
          <StatCard title="Total Repositories" value={data?.totalRepos} />
          <StatCard title="Total Pipelines" value={data?.totalPipelines} />
          <StatCard title="Recent Errors" value={data?.recentErrors} />
        </div>
      )}
    </div>
  );
};

const StatCard: React.FC<{ title: string; value?: number }> = ({ title, value }) => (
  <div className="rounded-xl border bg-card text-card-foreground shadow p-6">
    <h3 className="tracking-tight text-sm font-medium text-muted-foreground mb-2">{title}</h3>
    <div className="text-3xl font-bold">{value ?? '--'}</div>
  </div>
);

export default Dashboard;
