// Update this page (the content is just a fallback if you fail to update the page)
import { useQuery } from "@tanstack/react-query";
import { apiClient } from "@/lib/api-client";
import { ErrorBanner } from "@/components/ErrorBanner";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Activity, Book, GitMerge, ShieldAlert } from "lucide-react";

interface DashboardStats {
  totalProfiles: number;
  totalRepos: number;
  totalPipelines: number;
  recentErrors: number;
}

const Index = () => {
  const { data: stats, error, isLoading } = useQuery<DashboardStats>({
    queryKey: ['dashboard-stats'],
    queryFn: async () => {
      return apiClient.get('/dashboard/stats');
    }
  });

  return (
    <div className="space-y-6">
      <h1 className="text-3xl font-bold tracking-tight">Dashboard</h1>
      
      {error && <ErrorBanner error={error} />}

      <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">Git Profiles</CardTitle>
            <Activity className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">{isLoading ? '...' : stats?.totalProfiles ?? 0}</div>
            <p className="text-xs text-muted-foreground">Active authorized identities</p>
          </CardContent>
        </Card>

        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">Repositories</CardTitle>
            <Book className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">{isLoading ? '...' : stats?.totalRepos ?? 0}</div>
            <p className="text-xs text-muted-foreground">Connected repositories</p>
          </CardContent>
        </Card>

        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">Pipeline Runs</CardTitle>
            <GitMerge className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">{isLoading ? '...' : stats?.totalPipelines ?? 0}</div>
            <p className="text-xs text-muted-foreground">Historical CI/CD executions</p>
          </CardContent>
        </Card>

        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium text-destructive">Recent Errors</CardTitle>
            <ShieldAlert className="h-4 w-4 text-destructive" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold text-destructive">{isLoading ? '...' : stats?.recentErrors ?? 0}</div>
            <p className="text-xs text-muted-foreground">Unresolved ingestion failures</p>
          </CardContent>
        </Card>
      </div>
    </div>
  );
};

export default Index;
