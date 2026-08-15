import axios, { AxiosError, AxiosResponse } from 'axios';
import { useAuthStore } from '../store/authStore';

// Determine base URL dynamically (assumes Laravel API is hosted appropriately)
const baseURL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api';

export const apiClient = axios.create({
  baseURL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Request Interceptor: Attach Sanctum Bearer Token
apiClient.interceptors.request.use((config) => {
  const token = useAuthStore.getState().token;
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Response Interceptor: Unwrap ErrorEnvelope and handle 401s
apiClient.interceptors.response.use(
  (response: AxiosResponse) => {
    // If the API returns a structured ErrorEnvelope inside 200, we can handle it here,
    // but in Laravel we mostly return 4xx/5xx for failures.
    return response.data;
  },
  (error: AxiosError) => {
    if (error.response?.status === 401) {
      // Clear token on 401 (Unauthorized)
      useAuthStore.getState().setToken(null);
    }
    
    // The error response data is usually the ErrorEnvelope
    const errorData = error.response?.data;
    
    return Promise.reject(errorData || error);
  }
);
