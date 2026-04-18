/* eslint-disable react-refresh/only-export-components */
import React, { createContext, useState, useEffect } from 'react';
import type { ReactNode } from 'react';

interface User {
  id: number;
  email: string;
  nom?: string;
  prenom?: string;
  raison_social?: string;
  telephone?: string;
  ville_id?: string | number;
  type?: 'candidat' | 'entreprise';
  role?: 'candidat' | 'entreprise';
}

export interface AuthContextType {
  user: User | null;
  token: string | null;
  role: 'candidat' | 'entreprise' | null;
  login: (userData: User, authToken: string) => void;
  logout: () => void;
  isAuthenticated: boolean;
}

export const AuthContext = createContext<AuthContextType | undefined>(undefined);

export const AuthProvider: React.FC<{ children: ReactNode }> = ({ children }) => {
  // Initialize state from localStorage
  const getInitialToken = () => localStorage.getItem('auth_token');
  const getInitialUser = () => {
    const storedUserStr = localStorage.getItem('auth_user');
    if (storedUserStr) {
      try {
        return JSON.parse(storedUserStr) as User;
      } catch (error) {
        console.error('Failed to parse stored user data', error);
        return null;
      }
    }
    return null;
  };

  const [user, setUser] = useState<User | null>(getInitialUser);
  const [token, setToken] = useState<string | null>(getInitialToken);
  const [role, setRole] = useState<'candidat' | 'entreprise' | null>(() => {
    const initialUser = getInitialUser();
    if (initialUser) {
      if (initialUser.raison_social || initialUser.type === 'entreprise') {
        return 'entreprise';
      }
      return 'candidat';
    }
    return null;
  });

  const logout = () => {
    localStorage.removeItem('auth_token');
    localStorage.removeItem('auth_user');
    setToken(null);
    setUser(null);
    setRole(null);
  };

  // Listen for unauthorized events from Axios
  useEffect(() => {
    const handleUnauthorized = () => {
      logout();
    };
    window.addEventListener('auth_unauthorized', handleUnauthorized);
    return () => {
      window.removeEventListener('auth_unauthorized', handleUnauthorized);
    };
  }, []);

  const login = (userData: User, authToken: string) => {
    localStorage.setItem('auth_token', authToken);
    localStorage.setItem('auth_user', JSON.stringify(userData));
    setToken(authToken);
    setUser(userData);
    
    if (userData.raison_social || userData.type === 'entreprise') {
      setRole('entreprise');
    } else {
      setRole('candidat');
    }
  };

  const isAuthenticated = !!token;

  return (
    <AuthContext.Provider value={{ user, token, role, login, logout, isAuthenticated }}>
      {children}
    </AuthContext.Provider>
  );
};
