import { forwardRef, useState } from 'react';
import type { InputHTMLAttributes } from 'react';
import clsx from 'clsx';

export interface InputProps extends InputHTMLAttributes<HTMLInputElement> {
  label: string;
  error?: string;
  icon?: React.ReactNode;
}

export const Input = forwardRef<HTMLInputElement, InputProps>(
  ({ className, label, error, icon, ...props }, ref) => {
    const [focused, setFocused] = useState(false);

    return (
      <div className="w-full relative group flex flex-col gap-1.5">
        <label className={clsx(
          "text-sm font-display font-medium uppercase tracking-widest",
          error ? "text-red-500" : (focused ? "text-accent-primary" : "text-slate-400")
        )}>{label}</label>

        <div className="relative">
          {icon && (
            <div className="absolute left-4 rtl:left-auto rtl:right-4 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none group-focus-within:text-accent-primary transition-colors">
              {icon}
            </div>
          )}
          <input
            {...props}
            ref={ref}
            onFocus={(e) => {
              setFocused(true);
              props.onFocus?.(e);
            }}
            onBlur={(e) => {
              setFocused(false);
              props.onBlur?.(e);
            }}
            className={clsx(
              "w-full bg-brand-900 border-2 text-white transition-all duration-200 outline-none rounded-none",
              "min-h-[44px]", // Target Size Optimization
              icon ? "pl-11 pr-4 rtl:pl-4 rtl:pr-11" : "px-4",
              error 
                ? "border-red-500 focus:border-red-500 focus:ring-2 focus:ring-red-500/20" 
                : "border-brand-border focus:border-accent-primary focus:ring-2 focus:ring-accent-primary/20 hover:border-slate-600",
              className
            )}
          />
        </div>
        
        {/* Error Messaging placement exactly below input */}
        {error && (
          <p className="text-xs font-display text-red-500 uppercase tracking-wider font-medium animate-fade-in">
            * {error}
          </p>
        )}
      </div>
    );
  }
);

Input.displayName = 'Input';
