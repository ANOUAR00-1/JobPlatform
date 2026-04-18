import React, { createContext, useState, useEffect } from 'react';
import type { ReactNode } from 'react';

type Theme = 'light' | 'dark';

interface ThemeContextType {
  theme: Theme;
  toggleTheme: () => void;
  setTheme: (theme: Theme) => void;
}

export const ThemeContext = createContext<ThemeContextType | undefined>(undefined);

export const ThemeProvider: React.FC<{ children: ReactNode }> = ({ children }) => {
  // Initialize theme from localStorage or system preference
  const getInitialTheme = (): Theme => {
    const storedTheme = localStorage.getItem('theme') as Theme | null;
    if (storedTheme) {
      return storedTheme;
    }
    // Check system preference
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
      return 'dark';
    }
    return 'light';
  };

  const [theme, setThemeState] = useState<Theme>(getInitialTheme);

  // Apply theme to document
  useEffect(() => {
    const root = document.documentElement;
    const body = document.body;
    
    console.log('🎨 Theme changed to:', theme);
    console.log('📍 Adding dark class to:', root, body);
    
    if (theme === 'dark') {
      root.classList.add('dark');
      body.classList.add('dark');
      console.log('✅ Dark mode enabled');
      console.log('🔍 HTML classes:', root.className);
      console.log('🔍 Body classes:', body.className);
    } else {
      root.classList.remove('dark');
      body.classList.remove('dark');
      console.log('☀️ Light mode enabled');
    }
    localStorage.setItem('theme', theme);
  }, [theme]);

  const toggleTheme = () => {
    setThemeState((prev) => (prev === 'light' ? 'dark' : 'light'));
  };

  const setTheme = (newTheme: Theme) => {
    setThemeState(newTheme);
  };

  return (
    <ThemeContext.Provider value={{ theme, toggleTheme, setTheme }}>
      {children}
    </ThemeContext.Provider>
  );
};
