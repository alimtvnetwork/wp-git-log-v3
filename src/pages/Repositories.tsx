import { useQuery } from "@tanstack/react-query";
import { apiClient } from "@/lib/api-client";
import { ErrorBanner } from "@/components/ErrorBanner";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Button } from "@/components/ui/button";
import { Plus } from "lucide-react";

interface Repo {
  RepoId: number;
  GitProfileId: number;
  RootRepoName: string;
  RepoUrl: string;
}

export const Repositories = () => {
  const { data: repos, error, isLoading } = useQuery<Repo[]>({
    queryKey: ['repos'],
    queryFn: async () => apiClient.get('/repos'),
  });

  return (
    <div className="space-y-6">
      <div className="flex justify-between items-center">
        <h1 className="text-3xl font-bold tracking-tight">Repositories</h1>
        <Button>
          <Plus className="mr-2 h-4 w-4" /> Register Repo
        </Button>
      </div>

      {error && <ErrorBanner error={error} />}

      <div className="border rounded-md">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>ID</TableHead>
              <TableHead>Root Repo Name</TableHead>
              <TableHead>Repository URL</TableHead>
              <TableHead>Linked Profile ID</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            {isLoading ? (
              <TableRow>
                <TableCell colSpan={4} className="text-center">Loading...</TableCell>
              </TableRow>
            ) : repos?.length === 0 ? (
              <TableRow>
                <TableCell colSpan={4} className="text-center text-muted-foreground">No repositories found.</TableCell>
              </TableRow>
            ) : (
              repos?.map((repo) => (
                <TableRow key={repo.RepoId}>
                  <TableCell className="font-medium text-muted-foreground">#{repo.RepoId}</TableCell>
                  <TableCell className="font-bold">{repo.RootRepoName}</TableCell>
                  <TableCell className="font-mono text-sm">{repo.RepoUrl}</TableCell>
                  <TableCell>Profile #{repo.GitProfileId}</TableCell>
                </TableRow>
              ))
            )}
          </TableBody>
        </Table>
      </div>
    </div>
  );
};

export default Repositories;
