import React from 'react';
import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import { AuthProvider } from './contexts/AuthContext';
import { ToastProvider } from './contexts/ToastContext';
import { ThemeProvider } from './contexts/ThemeContext';

// Layouts
import PublicLayout from './layouts/PublicLayout';
import AuthLayout from './layouts/AuthLayout';
import DashboardLayout from './layouts/DashboardLayout';
import ProtectedRoute from './components/ProtectedRoute';
import JobyBotWidget from './components/JobyBotWidget';
import ScrollToTop from './components/ScrollToTop';
import ScrollToTopButton from './components/ScrollToTopButton';

// Public Pages
import LandingPage from './pages/public/LandingPage';
import OffresList from './pages/public/OffresList';
import OffreDetails from './pages/public/OffreDetails';
import PrivacyPolicy from './pages/public/PrivacyPolicy';
import TermsOfService from './pages/public/TermsOfService';
import CookiePolicy from './pages/public/CookiePolicy';

// Auth Pages
import Login from './pages/auth/Login';
import Register from './pages/auth/Register';
import VerifyEmail from './pages/auth/VerifyEmail';
import ForgotPassword from './pages/auth/ForgotPassword';
import ResetPassword from './pages/auth/ResetPassword';
import AuthCallback from './pages/auth/AuthCallback';

// Candidat Pages
import CandidatDashboard from './pages/candidat/Dashboard';
import Profile from './pages/candidat/Profile';
import MyApplications from './pages/candidat/MyApplications';

// Entreprise Pages
import EntrepriseDashboard from './pages/entreprise/Dashboard';
import CreateOffre from './pages/entreprise/CreateOffre';
import ManageCandidatures from './pages/entreprise/ManageCandidatures';
import EntrepriseOffresList from './pages/entreprise/OffresList';

const App: React.FC = () => {
  return (
    <BrowserRouter>
      <ThemeProvider>
        <ToastProvider>
          <AuthProvider>
            <ScrollToTop />
            <Routes>
            {/* Public Sector routes under PublicLayout */}
            <Route element={<PublicLayout />}>
              <Route path="/" element={<LandingPage />} />
              <Route path="/offres" element={<OffresList />} />
              <Route path="/offres/:id" element={<OffreDetails />} />
              <Route path="/privacy-policy" element={<PrivacyPolicy />} />
              <Route path="/terms-of-service" element={<TermsOfService />} />
              <Route path="/cookie-policy" element={<CookiePolicy />} />
            </Route>
            
            {/* Auth Routes */}
            <Route element={<AuthLayout />}>
              <Route path="/login" element={<Login />} />
              <Route path="/register" element={<Register />} />
              <Route path="/verify-email" element={<VerifyEmail />} />
              <Route path="/forgot-password" element={<ForgotPassword />} />
              <Route path="/reset-password" element={<ResetPassword />} />
            </Route>

            {/* OAuth Callback - No layout */}
            <Route path="/auth/callback" element={<AuthCallback />} />

            {/* Candidat Routes */}
            <Route element={<ProtectedRoute allowedRoles={['candidat']} />}>
              <Route element={<DashboardLayout />}>
                <Route path="/candidat" element={<CandidatDashboard />} />
                <Route path="/candidat/profile" element={<Profile />} />
                <Route path="/candidat/candidatures" element={<MyApplications />} />
              </Route>
            </Route>

            {/* Entreprise Routes */}
            <Route element={<ProtectedRoute allowedRoles={['entreprise']} />}>
              <Route element={<DashboardLayout />}>
                <Route path="/entreprise" element={<EntrepriseDashboard />} />
                <Route path="/entreprise/offres" element={<EntrepriseOffresList />} />
                <Route path="/entreprise/offres/new" element={<CreateOffre />} />
                <Route path="/entreprise/candidatures" element={<ManageCandidatures />} />
              </Route>
            </Route>

            {/* 404 Fallback - redirect to root instead of login */}
            <Route path="*" element={<Navigate to="/" replace />} />
          </Routes>
          <JobyBotWidget />
          <ScrollToTopButton />
          </AuthProvider>
        </ToastProvider>
      </ThemeProvider>
    </BrowserRouter>
  );
};

export default App;
