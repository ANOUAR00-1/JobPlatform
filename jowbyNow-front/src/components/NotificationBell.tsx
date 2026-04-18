import React, { useState, useEffect, useRef } from 'react';
import { Bell } from 'lucide-react';
import { useTranslation } from 'react-i18next';
import api from '../services/api';
import { motion, AnimatePresence } from 'framer-motion';

interface AppNotification {
  id: string;
  data: {
    candidat_name: string;
    offre_title: string;
    candidature_id: number;
    message: string;
  };
  created_at: string;
}

const NotificationBell: React.FC = () => {
  const [notifications, setNotifications] = useState<AppNotification[]>([]);
  const [isOpen, setIsOpen] = useState(false);
  const dropdownRef = useRef<HTMLDivElement>(null);
  const { t } = useTranslation();

  useEffect(() => {
    const fetchNotifications = async () => {
      try {
        const res = await api.get('/entreprise/notifications');
        if (res.data.success) {
          setNotifications(res.data.data.notifications);
        }
      } catch (error) {
        console.error('Failed to fetch notifications', error);
      }
    };

    fetchNotifications();
    // In a real prod environment, use Pusher/Reverb or polling if websockets aren't set up.
    // We will poll every 30 seconds for the demo purpose.
    const interval = setInterval(fetchNotifications, 30000);
    return () => clearInterval(interval);
  }, []);

  const markAsRead = async (id: string) => {
    try {
      const res = await api.post(`/entreprise/notifications/${id}/read`);
      if (res.data.success) {
        setNotifications(prev => prev.filter(n => n.id !== id));
      }
    } catch (error) {
      console.error('Failed to mark notification as read', error);
    }
  };

  // Close dropdown when clicking outside
  useEffect(() => {
    const handleClickOutside = (event: MouseEvent) => {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target as Node)) {
        setIsOpen(false);
      }
    };
    if (isOpen) {
      document.addEventListener('mousedown', handleClickOutside);
    }
    return () => {
      document.removeEventListener('mousedown', handleClickOutside);
    };
  }, [isOpen]);

  const unreadCount = notifications.length;

  return (
    <div className="relative z-50" ref={dropdownRef}>
      <button
        onClick={() => setIsOpen(!isOpen)}
        className="relative p-2 rounded-xl hover:bg-slate-100 transition-colors text-slate-600 hover:text-slate-950"
      >
        <Bell size={20} />
        {unreadCount > 0 && (
          <span className="absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[9px] font-bold text-white shadow">
            {unreadCount > 9 ? '9+' : unreadCount}
          </span>
        )}
      </button>

      <AnimatePresence>
        {isOpen && (
          <motion.div
            initial={{ opacity: 0, y: 10, scale: 0.95 }}
            animate={{ opacity: 1, y: 0, scale: 1 }}
            exit={{ opacity: 0, y: 10, scale: 0.95 }}
            transition={{ duration: 0.2 }}
            className="absolute right-0 mt-2 w-80 bg-white border border-slate-200 shadow-xl rounded-xl overflow-hidden"
          >
            <div className="p-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
              <h3 className="font-semibold text-sm text-slate-950">
                {t('notifications.title', 'Notifications')}
              </h3>
              <span className="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-lg">
                {unreadCount} {t('notifications.new', 'Nouveau(x)')}
              </span>
            </div>

            <div className="max-h-[300px] overflow-y-auto">
              {notifications.length === 0 ? (
                <div className="p-6 text-center text-slate-500 text-sm">
                  {t('notifications.empty', 'Aucune notification')}
                </div>
              ) : (
                notifications.map((notification) => (
                  <div 
                    key={notification.id} 
                    className="p-4 border-b border-slate-100 hover:bg-slate-50 transition-colors flex gap-3 group relative cursor-pointer"
                    onClick={() => markAsRead(notification.id)}
                  >
                    <div className="w-2 h-2 rounded-full bg-emerald-500 mt-1.5 shrink-0" />
                    <div>
                      <p className="text-sm text-slate-600">
                        <span className="font-semibold text-slate-950">{notification.data.candidat_name}</span> {t('notifications.applied_for')} <span className="text-emerald-700 font-medium">{notification.data.offre_title}</span>
                      </p>
                      <p className="text-xs text-slate-500 mt-1.5">
                        {new Date(notification.created_at).toLocaleDateString()}
                      </p>
                    </div>
                  </div>
                ))
              )}
            </div>
          </motion.div>
        )}
      </AnimatePresence>
    </div>
  );
};

export default NotificationBell;
