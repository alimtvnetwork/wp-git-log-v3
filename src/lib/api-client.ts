import axios, { AxiosError, AxiosInstance, AxiosResponse } from 'axios';

// The Laravel backend returns Universal Error Envelopes on failure
export interface ApiErrorEnvelope {
  ErrorCode: string;
  TraceId: string;
  Message: string;
  Details?: Record<string, unknown>;
}

// Our custom error class that components can catch and display
export class ApiError extends Error {
  public envelope: ApiErrorEnvelope;
  public status?: number;

  constructor(envelope: ApiErrorEnvelope, status?: number) {
    super(envelope.Message);
    this.name = 'ApiError';
    this.envelope = envelope;
    this.status = status;
  }
}

export const apiClient: AxiosInstance = axios.create({
  baseURL: '/git-logs/v2',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
  timeout: 15000,
});

// Request interceptor for local testing overrides
apiClient.interceptors.request.use((config) => {
  // If we are in local development, inject the mock auth header
  // so the headless Laravel doesn't block Lane A requests.
  if (import.meta.env.DEV) {
    config.headers['X-Mock-Auth'] = 'true';
  }
  return config;
});

// Response interceptor to unwrap ErrorEnvelopes and standard Responses
apiClient.interceptors.response.use(
  (response: AxiosResponse) => {
    // ApiResponse::success() returns { Status: 200, Results: {...} }
    // We unwrap and just return the Results object for convenience.
    if (response.data && response.data.Results) {
      return response.data.Results;
    }
    return response.data;
  },
  (error: AxiosError) => {
    if (error.response && error.response.data) {
      const data = error.response.data as Record<string, unknown>;
      
      // If the backend sent a standardized ErrorEnvelope array in Results
      if (data.Results && Array.isArray(data.Results) && data.Results.length > 0 && data.Results[0].code) {
        const errorEnv = data.Results[0];
        throw new ApiError({
          ErrorCode: errorEnv.code,
          TraceId: errorEnv.trace_id || 'unknown',
          Message: errorEnv.message,
          Details: errorEnv.details
        }, error.response.status);
      }
      
      // If it's a fallback format from older skeleton or validation
      if (data.ErrorCode) {
        throw new ApiError(data as ApiErrorEnvelope, error.response.status);
      }
    }

    // Network errors or unhandled 500s
    throw new ApiError({
      ErrorCode: 'GL-NETWORK-ERROR',
      TraceId: 'local',
      Message: error.message || 'An unexpected network error occurred.',
    }, error.response?.status);
  }
);
