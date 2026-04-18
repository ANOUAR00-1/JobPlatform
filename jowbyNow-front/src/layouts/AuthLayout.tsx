import React from 'react';
import { Outlet, Link } from 'react-router-dom';

const AuthLayout: React.FC = () => {
  return (
    <div className="min-h-screen grid place-items-center relative p-6 bg-slate-50">
      <div className="w-full max-w-md bg-white border border-slate-200 rounded-2xl p-10 shadow-xl">
        <Link to="/" className="flex items-center gap-3 justify-center mb-10">
          <span className="text-2xl font-display font-bold tracking-tight">
            <span className="text-[#2aa354]">J</span><span className="text-slate-950">oby</span><span className="text-[#2aa354]">N</span><span className="text-slate-600">ow</span>
          </span>
        </Link>
        <Outlet />
      </div>
    </div>
  );
};

export default AuthLayout;
