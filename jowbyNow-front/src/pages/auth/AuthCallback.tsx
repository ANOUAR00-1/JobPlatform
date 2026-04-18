import React, { useEffect } from 'react';
import { useNavigate, useSearchParams } from 'react-router-dom';
import { Loader2 } from 'lucide-react';
import { useAuth } from '../../hooks/useAuth';
import api from '../../services/api';

const AuthCallback: React.FC = () => {
  const [searchParams] = useSearchParams();
  const navigate = useNavigate();
  const { login } = useAuth();

  useEffect(() => {
    const handleCallback = async () => {
      const token = searchParams.get('token');
      const error = searchParams.get('error');

      if (error) {
        // OAuth failed
        navigate('/login?error=oauth_failed');
        return;
      }

      if (!token) {
        // No token provided
        navigate('/login');
        return;
      }

      try {
        // Save token with the correct key that api.ts expects
        localStorage.setItem('auth_token', token);

        // Fetch user data
        const response = await api.get('/user');
        const { user, profile } = response.data;

        // Merge user and profile data
        const userData = {
          ...user,
          ...profile,
        };

        // Update auth state using the login method
        login(userData, token);

        // Redirect to dashboard based on role
        if (user.role === 'entreprise') {
          navigate('/entreprise');
        } else {
          navigate('/candidat');
        }
      } catch (error) {
        console.error('Auth callback error:', error);
        localStorage.removeItem('auth_token');
        navigate('/login?error=auth_failed');
      }
    };

    handleCallback();
  }, [searchParams, navigate, login]);

  return (
    <div className="min-h-screen flex items-center justify-center bg-slate-50">
      <div className="text-center">
        <Loader2 className="w-12 h-12 animate-spin text-[#2aa354] mx-auto mb-4" />
        <h2 className="text-xl font-semibold text-slate-950 mb-2">Authenticating...</h2>
        <p className="text-slate-600">Please wait while we log you in.</p>
      </div>
    </div>
  );
};

export default AuthCallback;
