import React from 'react';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { AlertCircle, Terminal } from 'lucide-react';

interface ErrorBannerProps {
  error: Error | unknown;
  className?: string;
}

export const ErrorBanner: React.FC<ErrorBannerProps> = ({ error, className }) => {
  if (!error) return null;

  // Handle Axios errors containing the Universal Error Envelope
  if (error && typeof error === 'object' && 'Status' in error) {
    const env = (error as { Status: { Code: string; Message: string; TraceId?: string } }).Status;
    return (
      <Alert variant="destructive" className={className}>
        <AlertCircle className="h-4 w-4" />
        <AlertTitle className="font-mono">{env.Code}</AlertTitle>
        <AlertDescription className="mt-2 flex flex-col gap-2">
          <p>{env.Message}</p>
          {env.TraceId && (
            <p className="text-xs opacity-75 font-mono">Trace ID: {env.TraceId}</p>
          )}
        </AlertDescription>
      </Alert>
    );
  }

  // Handle standard JS Errors
  if (error instanceof Error) {
    return (
      <Alert variant="destructive" className={className}>
        <AlertCircle className="h-4 w-4" />
        <AlertTitle>Unexpected Client Error</AlertTitle>
        <AlertDescription className="mt-2">
          {error.message}
        </AlertDescription>
      </Alert>
    );
  }

  // Fallback for unknown throws
  return (
    <Alert variant="destructive" className={className}>
      <Terminal className="h-4 w-4" />
      <AlertTitle>Unknown Error</AlertTitle>
      <AlertDescription>
        An unknown error occurred rendering this component.
      </AlertDescription>
    </Alert>
  );
};
