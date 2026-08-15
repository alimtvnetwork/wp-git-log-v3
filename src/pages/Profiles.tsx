import { useQuery } from "@tanstack/react-query";
import { apiClient } from "@/lib/api-client";
import { ErrorBanner } from "@/components/ErrorBanner";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { Plus } from "lucide-react";

interface GitProfile {
  GitProfileId: number;
  ProviderId: number;
  OwnerName: string;
  IsOrganization: number;
  AcceptanceId: number;
  SelectedRepoUrl: string | null;
  IsRestrictInBranch: number;
  StrictBranch: string | null;
}

export const Profiles = () => {
  const { data: profiles, error, isLoading } = useQuery<GitProfile[]>({
    queryKey: ['profiles'],
    queryFn: async () => apiClient.get('/profiles'),
  });

  const getProviderName = (id: number) => {
    const map: Record<number, string> = { 1: 'GitHub', 2: 'GitLab', 3: 'Bitbucket', 4: 'Custom' };
    return map[id] || 'Unknown';
  };

  const getAcceptanceRule = (profile: GitProfile) => {
    switch (profile.AcceptanceId) {
      case 1: return 'Accept All Repos';
      case 2: return `Only: ${profile.SelectedRepoUrl}`;
      case 3: return 'All versions of Repo';
      default: return 'Unknown';
    }
  };

  return (
    <div className="space-y-6">
      <div className="flex justify-between items-center">
        <h1 className="text-3xl font-bold tracking-tight">Git Profiles</h1>
        <Button>
          <Plus className="mr-2 h-4 w-4" /> Add Profile
        </Button>
      </div>

      {error && <ErrorBanner error={error} />}

      <div className="border rounded-md">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>Provider</TableHead>
              <TableHead>Owner / Org</TableHead>
              <TableHead>Acceptance Rule</TableHead>
              <TableHead>Branch Restriction</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            {isLoading ? (
              <TableRow>
                <TableCell colSpan={4} className="text-center">Loading...</TableCell>
              </TableRow>
            ) : profiles?.length === 0 ? (
              <TableRow>
                <TableCell colSpan={4} className="text-center text-muted-foreground">No profiles found.</TableCell>
              </TableRow>
            ) : (
              profiles?.map((profile) => (
                <TableRow key={profile.GitProfileId}>
                  <TableCell className="font-medium">
                    <Badge variant="outline">{getProviderName(profile.ProviderId)}</Badge>
                  </TableCell>
                  <TableCell>
                    {profile.OwnerName}
                    {profile.IsOrganization === 1 && <Badge variant="secondary" className="ml-2">Org</Badge>}
                  </TableCell>
                  <TableCell className="text-muted-foreground">
                    {getAcceptanceRule(profile)}
                  </TableCell>
                  <TableCell>
                    {profile.IsRestrictInBranch === 1 ? (
                      <Badge variant="default">Strict: {profile.StrictBranch}</Badge>
                    ) : (
                      <span className="text-muted-foreground text-sm">Any</span>
                    )}
                  </TableCell>
                </TableRow>
              ))
            )}
          </TableBody>
        </Table>
      </div>
    </div>
  );
};

export default Profiles;
