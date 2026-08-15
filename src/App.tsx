import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import { ThemeProvider } from './components/theme-provider';
import { Layout } from './components/Layout';
import Dashboard from './pages/Dashboard';
import GitProfiles from './pages/GitProfiles';
import Repos from './pages/Repos';
import Pipelines from './pages/Pipelines';
import PipelineDetail from './pages/PipelineDetail';
import { Toaster } from './components/ui/sonner';

// Initialize React Query
const queryClient = new QueryClient();

function App() {
  return (
    <QueryClientProvider client={queryClient}>
      <ThemeProvider defaultTheme="dark" storageKey="vite-ui-theme">
        <BrowserRouter>
          <Routes>
            <Route element={<Layout />}>
              <Route path="/" element={<Navigate to="/dashboard" replace />} />
              <Route path="/dashboard" element={<Dashboard />} />
              <Route path="/profiles" element={<GitProfiles />} />
              <Route path="/repos" element={<Repos />} />
              <Route path="/pipelines" element={<Pipelines />} />
              <Route path="/pipelines/:id" element={<PipelineDetail />} />
              {/* Add more routes here as we build them */}
            </Route>
          </Routes>
        </BrowserRouter>
        <Toaster />
      </ThemeProvider>
    </QueryClientProvider>
  );
}

export default App;
