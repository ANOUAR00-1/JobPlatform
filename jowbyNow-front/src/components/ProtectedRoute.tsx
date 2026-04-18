import React from 'react';
import { Navigate, Outlet } from 'react-router-dom';
import { useAuth } from '../hooks/useAuth';

interface ProtectedRouteProps {
  allowedRoles?: ('candidat' | 'entreprise')[];
  redirectPath?: string;
}

const ProtectedRoute: React.FC<ProtectedRouteProps> = ({ 
  allowedRoles, 
  redirectPath = '/login' 
}) => {
  const { isAuthenticated, role } = useAuth();

  if (!isAuthenticated) {
    return <Navigate to={redirectPath} replace />;
  }

  if (allowedRoles && role && !allowedRoles.includes(role)) {
    // If the user is logged in but doesn't have the right role, send them to their own dashboard
    const defaultDashboard = role === 'entreprise' ? '/entreprise' : '/candidat';
    return <Navigate to={defaultDashboard} replace />;
  }

  return <Outlet />;
};

export default ProtectedRoute;
