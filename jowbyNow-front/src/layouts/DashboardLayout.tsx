import React from 'react';
import { Outlet, NavLink, useNavigate } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import { useAuth } from '../hooks/useAuth';
import { LogOut, Briefcase, User, Users, FileText, LayoutDashboard } from 'lucide-react';
import clsx from 'clsx';
import NotificationBell from '../components/NotificationBell';
import ThemeToggle from '../components/ThemeToggle';

const DashboardLayout: React.FC = () => {
  const { logout, role, user } = useAuth();
  const navigate = useNavigate();
  const { t } = useTranslation();

  const handleLogout = () => {
    logout();
    navigate('/login');
  };

  const navItems = role === 'entreprise' 
    ? [
        { name: t('dashboard_layout.enterprise.dashboard'), path: '/entreprise', icon: <LayoutDashboard size={18} /> },
        { name: t('dashboard_layout.enterprise.offers'), path: '/entreprise/offres', icon: <FileText size={18} /> },
        { name: t('dashboard_layout.enterprise.candidatures'), path: '/entreprise/candidatures', icon: <Users size={18} /> },
      ]
    : [
        { name: t('dashboard_layout.candidate.dashboard'), path: '/candidat', icon: <LayoutDashboard size={18} /> },
        { name: t('dashboard_layout.candidate.browse_jobs'), path: '/offres', icon: <Briefcase size={18} /> },
        { name: t('dashboard_layout.candidate.my_applications'), path: '/candidat/candidatures', icon: <FileText size={18} /> },
        { name: t('dashboard_layout.candidate.profile'), path: '/candidat/profile', icon: <User size={18} /> },
      ];

  const initials = user?.email?.slice(0, 2).toUpperCase() ?? 'JN';

  return (
    <div className="flex h-screen bg-white dark:bg-slate-950 overflow-hidden font-sans">

      {/* Sidebar */}
      <aside className="hidden lg:flex w-64 border-r border-slate-200 dark:border-slate-800 flex-col bg-slate-50 dark:bg-slate-900 relative z-10 shrink-0">
        
        {/* Logo / Brand */}
        <div className="h-16 px-6 flex items-center border-b border-slate-200 dark:border-slate-800 shrink-0 bg-white dark:bg-slate-950">
          <div className="flex items-center gap-3">
            <div className="w-9 h-9 rounded-xl bg-[#8cedaa] flex items-center justify-center text-slate-950 font-bold text-base shadow-sm">
              J
            </div>
            <span className="text-base font-bold text-slate-950 dark:text-white tracking-tight">
              JobyNow
            </span>
          </div>
        </div>

        {/* Nav Links */}
        <nav className="flex-1 py-4 px-4 space-y-1 overflow-y-auto">
          {navItems.map((item) => (
            <NavLink
              key={item.path}
              to={item.path}
              className={({ isActive }) => clsx(
                "flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all",
                isActive
                  ? "bg-white dark:bg-slate-800 text-slate-950 dark:text-white shadow-sm border border-slate-200 dark:border-slate-700"
                  : "text-slate-600 dark:text-slate-400 hover:text-slate-950 dark:hover:text-white hover:bg-white/50 dark:hover:bg-slate-800/50"
              )}
              end={item.path === '/entreprise' || item.path === '/candidat'}
            >
              <span className="shrink-0">{item.icon}</span>
              <span>{item.name}</span>
            </NavLink>
          ))}
        </nav>

        {/* User Panel & Logout */}
        <div className="p-4 border-t border-slate-200 dark:border-slate-800 space-y-2 bg-white dark:bg-slate-950">
          <div className="flex items-center gap-3 px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900">
            <div className="w-9 h-9 rounded-xl bg-[#8cedaa]/20 border border-[#8cedaa]/30 flex items-center justify-center text-[#2aa354] text-xs font-bold flex-shrink-0">
              {initials}
            </div>
            <div className="min-w-0">
              <p className="text-xs font-bold text-slate-950 dark:text-white truncate leading-tight">{user?.email}</p>
              <p className="text-xs text-slate-600 dark:text-slate-400 capitalize leading-tight mt-0.5 font-medium">{role}</p>
            </div>
          </div>
          <button
            onClick={handleLogout}
            className="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30 transition-all"
          >
            <LogOut size={18} />
            {t('dashboard_layout.logout')}
          </button>
        </div>
      </aside>

      {/* Mobile Bottom Nav */}
      <div className="lg:hidden fixed bottom-0 left-0 right-0 z-50 bg-white dark:bg-slate-950 border-t border-slate-200 dark:border-slate-800 flex items-center justify-around px-2 py-2 shadow-lg">
        {navItems.map((item) => (
          <NavLink
            key={item.path}
            to={item.path}
            className={({ isActive }) => clsx(
              "flex flex-col items-center gap-1 px-3 py-2 rounded-xl text-xs font-semibold transition-all",
              isActive
                ? "text-[#2aa354] bg-[#8cedaa]/10"
                : "text-slate-600 dark:text-slate-400"
            )}
            end={item.path === '/entreprise' || item.path === '/candidat'}
          >
            {item.icon}
            <span className="text-[10px]">{item.name}</span>
          </NavLink>
        ))}
        <button
          onClick={handleLogout}
          className="flex flex-col items-center gap-1 px-3 py-2 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-400"
        >
          <LogOut size={18} />
          <span className="text-[10px]">{t('dashboard_layout.logout')}</span>
        </button>
      </div>

      {/* Main Content Area */}
      <main className="flex-1 overflow-y-auto flex flex-col relative pb-16 lg:pb-0">
        {/* Top Header Bar */}
        <header className="h-16 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900 sticky top-0 z-40 flex items-center justify-end px-6 shrink-0 gap-3">
          <ThemeToggle />
          {role === 'entreprise' && <NotificationBell />}
        </header>

        {/* Page Content */}
        <div className="p-6 lg:p-8 max-w-7xl w-full mx-auto flex-1">
          <Outlet />
        </div>
      </main>
    </div>
  );
};

export default DashboardLayout;

