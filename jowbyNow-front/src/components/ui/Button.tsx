import { forwardRef } from 'react';
import type { ButtonHTMLAttributes } from 'react';
import clsx from 'clsx';
import { Loader2 } from 'lucide-react';

export interface ButtonProps extends ButtonHTMLAttributes<HTMLButtonElement> {
  variant?: 'primary' | 'secondary' | 'ghost' | 'danger';
  size?: 'sm' | 'md' | 'lg';
  isLoading?: boolean;
}

export const Button = forwardRef<HTMLButtonElement, ButtonProps>(
  ({ className, variant = 'primary', size = 'md', isLoading, children, ...props }, ref) => {
    
    // Neo-Utility Base: Ensure minimum touch heights and stark edges
    const baseStyles = "relative inline-flex items-center justify-center font-display font-medium uppercase tracking-widest transition-all duration-200 outline-none active:translate-y-[2px] active:translate-x-[2px] active:shadow-none disabled:opacity-50 disabled:pointer-events-none disabled:translate-y-0 disabled:translate-x-0";
    
    const variants = {
      primary: "bg-accent-primary text-brand-900 border-2 border-accent-primary hover:bg-accent-primary-hover shadow-[4px_4px_0_0_#FF2E93] hover:shadow-[2px_2px_0_0_#FF2E93]",
      secondary: "bg-brand-900 border-2 border-brand-border text-white hover:bg-brand-800 hover:border-white shadow-[4px_4px_0_0_#222222]",
      ghost: "text-slate-400 hover:text-white border-2 border-transparent hover:border-brand-border bg-transparent",
      danger: "bg-brand-900 border-2 border-red-500 text-red-500 hover:bg-red-500 hover:text-white shadow-[4px_4px_0_0_#ff0000]"
    };

    const sizes = {
      sm: "text-xs px-4 min-h-[36px]",
      md: "text-sm px-6 min-h-[44px]",
      lg: "text-base px-8 min-h-[56px]"
    };

    return (
      <button
        ref={ref}
        className={clsx(baseStyles, variants[variant], sizes[size], className)}
        disabled={isLoading || props.disabled}
        {...props}
      >
        <span className="flex items-center justify-center gap-2">
          {isLoading && <Loader2 className="w-4 h-4 animate-spin" />}
          {children}
        </span>
      </button>
    );
  }
);

Button.displayName = 'Button';
