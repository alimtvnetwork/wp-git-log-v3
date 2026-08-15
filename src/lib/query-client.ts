import { QueryClient } from '@tanstack/react-query';

export const queryClient = new QueryClient({
  defaultOptions: {
    queries: {
      staleTime: 1000 * 60 * 5, // 5 minutes
      retry: (failureCount, error: Error) => {
        // Do not retry if we get an explicit ErrorEnvelope back (e.g. 401, 403, 404)
        if (error.name === 'ApiError') {
          return false;
        }
        return failureCount < 3;
      },
      refetchOnWindowFocus: false,
    },
  },
});
