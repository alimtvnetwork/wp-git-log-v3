import { create } from 'zustand';

interface AuthState {
  token: string | null;
  setToken: (token: string | null) => void;
  isAuthenticated: boolean;
}

export const useAuthStore = create<AuthState>((set) => ({
  token: localStorage.getItem('gl_token') || null,
  setToken: (token) => {
    if (token) {
      localStorage.setItem('gl_token', token);
    } else {
      localStorage.removeItem('gl_token');
    }
    set({ token, isAuthenticated: !!token });
  },
  isAuthenticated: !!localStorage.getItem('gl_token'),
}));
