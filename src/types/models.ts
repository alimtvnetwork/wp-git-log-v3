export interface ErrorEnvelope {
  Status: {
    IsSuccess: boolean;
    Code: string; // e.g., 'GL-AUTH-INVALID-TOKEN'
    Severity: 'info' | 'warn' | 'error' | 'fatal';
    Message: string;
    TraceId?: string;
  };
}

export interface ApiResponse<T> extends ErrorEnvelope {
  Results: T;
}

export interface GitProfile {
  GitProfileId: number;
  ProviderId: number;
  OwnerName: string;
  IsOrganization: boolean;
  AcceptanceId: number;
  SelectedRepoUrl?: string;
  IsRestrictInBranch: boolean;
  StrictBranch?: string;
  ProfileUrl?: string;
  CreatedAt: number;
  UpdatedAt: number;
}

export interface Repo {
  RepoId: number;
  GitProfileId: number;
  RootRepoName: string;
  RepoUrl: string;
  CreatedAt: number;
}

export interface RepoVersion {
  RepoVersionId: number;
  RepoId: number;
  VersionPattern: string;
  BranchPattern: string;
  CreatedAt: number;
}

export interface Pipeline {
  PipelineId: number;
  RepoVersionId: number;
  Branch: string;
  Pipeline: string;
  HasError: boolean;
  PreviousHasError: boolean;
  CreatedAt: number;
  UpdatedAt: number;
}
