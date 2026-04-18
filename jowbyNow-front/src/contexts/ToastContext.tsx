/* eslint-disable react-refresh/only-export-components */
import React, { createContext, useState, useCallback } from 'react';
import type { ReactNode } from 'react';
import { CheckCircle, XCircle, X, Info } from 'lucide-react';

type ToastType = 'success' | 'error' | 'info';

interface Toast {
  id: string;
  message: string;
  type: ToastType;
}

interface ToastContextData {
  addToast: (message: string, type: ToastType) => void;
}

export const ToastContext = createContext<ToastContextData>({ addToast: () => {} });

export const ToastProvider: React.FC<{ children: ReactNode }> = ({ children }) => {
  const [toasts, setToasts] = useState<Toast[]>([]);

  const addToast = useCallback((message: string, type: ToastType) => {
    const id = Math.random().toString(36).substring(2, 11);
    setToasts((prev) => [...prev, { id, message, type }]);
    
    setTimeout(() => {
      setToasts((prev) => prev.filter((t) => t.id !== id));
    }, 4000);
  }, []);

  const removeToast = (id: string) => {
    setToasts((prev) => prev.filter((t) => t.id !== id));
  };

  const getToastStyle = (type: ToastType) => {
    switch (type) {
      case 'success': return 'border-l-emerald-500 bg-white';
      case 'error': return 'border-l-red-500 bg-white';
      case 'info': return 'border-l-blue-500 bg-white';
    }
  };

  return (
    <ToastContext.Provider value={{ addToast }}>
      {children}
      
      {/* Toast Container — Monotree */}
      <div className="fixed top-6 right-6 z-100 flex flex-col gap-3 pointer-events-none">
        {toasts.map((toast) => (
          <div 
            key={toast.id}
            className={`
              pointer-events-auto animate-slide-up-stagger flex items-center justify-between gap-4 p-4 border border-slate-200 border-l-4 shadow-lg rounded-xl
              min-w-[300px] max-w-sm transition-all
              ${getToastStyle(toast.type)}
            `}
          >
            <div className="flex items-start gap-3">
              {toast.type === 'success' && <CheckCircle className="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" />}
              {toast.type === 'error' && <XCircle className="w-5 h-5 text-red-500 shrink-0 mt-0.5" />}
              {toast.type === 'info' && <Info className="w-5 h-5 text-blue-500 shrink-0 mt-0.5" />}
              <p className="text-sm font-medium text-slate-950">{toast.message}</p>
            </div>
            <button onClick={() => removeToast(toast.id)} className="text-slate-400 hover:text-slate-950 transition-colors rounded-lg p-1 hover:bg-slate-100">
              <X className="w-4 h-4" />
            </button>
          </div>
        ))}
      </div>
    </ToastContext.Provider>
  );
};
