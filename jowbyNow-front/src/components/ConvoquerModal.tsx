import React, { useState } from 'react';
import { useTranslation } from 'react-i18next';
import { Calendar, Clock, MapPin, X, Loader2, Send } from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';

interface ConvoquerModalProps {
  isOpen: boolean;
  onClose: () => void;
  onSubmit: (date: string, time: string, location: string) => Promise<void>;
  candidateName: string;
  position: string;
}

const ConvoquerModal: React.FC<ConvoquerModalProps> = ({
  isOpen,
  onClose,
  onSubmit,
  candidateName,
  position
}) => {
  const { t } = useTranslation();
  const [date, setDate] = useState('');
  const [time, setTime] = useState('');
  const [location, setLocation] = useState('');
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    if (!date || !time || !location) return;

    setIsSubmitting(true);
    try {
      await onSubmit(date, time, location);
      onClose(); // Parent handles success
    } catch {
      // Error handled by parent
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <AnimatePresence>
      {isOpen && (
        <React.Fragment>
          {/* Backdrop */}
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            className="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
            onClick={onClose}
          >
            {/* Modal */}
            <motion.div
              initial={{ scale: 0.95, opacity: 0, y: 20 }}
              animate={{ scale: 1, opacity: 1, y: 0 }}
              exit={{ scale: 0.95, opacity: 0, y: 20 }}
              onClick={(e) => e.stopPropagation()}
              className="w-full max-w-md bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden"
            >
              {/* Header */}
              <div className="flex justify-between items-center p-6 border-b border-slate-200 bg-slate-50">
                <h3 className="text-lg font-semibold text-slate-950 flex items-center gap-2">
                  <Send size={18} className="text-emerald-600" />
                  {t('convoquer_modal.title', 'Convoquer le Candidat')}
                </h3>
                <button
                  onClick={onClose}
                  className="text-slate-500 hover:text-slate-950 transition-colors rounded-lg p-1 hover:bg-slate-100"
                >
                  <X size={20} />
                </button>
              </div>

              {/* Body */}
              <form onSubmit={handleSubmit} className="p-6 space-y-6">
                <div>
                  <p className="text-slate-600 text-sm">
                    {t('convoquer_modal.description1', 'Vous êtes sur le point de convoquer ')}
                    <span className="font-semibold text-slate-950">{candidateName}</span>
                    {t('convoquer_modal.description2', ' pour le poste ')}
                    <span className="font-semibold text-slate-950">{position}</span>.
                  </p>
                </div>

                <div className="space-y-4">
                  {/* Date Input */}
                  <div>
                    <label className="flex items-center gap-2 text-sm font-semibold text-slate-950 mb-2">
                      <Calendar size={16} />
                      {t('convoquer_modal.date', "Date de l'entretien")}
                    </label>
                    <input
                      type="date"
                      required
                      value={date}
                      onChange={(e) => setDate(e.target.value)}
                      className="w-full bg-slate-50 border border-slate-200 text-slate-950 p-3 rounded-xl outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-sm transition-all"
                    />
                  </div>

                  {/* Time Input */}
                  <div>
                    <label className="flex items-center gap-2 text-sm font-semibold text-slate-950 mb-2">
                      <Clock size={16} />
                      {t('convoquer_modal.time', "Heure de l'entretien")}
                    </label>
                    <input
                      type="time"
                      required
                      value={time}
                      onChange={(e) => setTime(e.target.value)}
                      className="w-full bg-slate-50 border border-slate-200 text-slate-950 p-3 rounded-xl outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-sm transition-all"
                    />
                  </div>

                  {/* Location Input */}
                  <div>
                    <label className="flex items-center gap-2 text-sm font-semibold text-slate-950 mb-2">
                      <MapPin size={16} />
                      {t('convoquer_modal.location', "Lieu ou Lien (Meet, Zoom...)")}
                    </label>
                    <input
                      type="text"
                      required
                      value={location}
                      onChange={(e) => setLocation(e.target.value)}
                      placeholder="Ex: Bureau Paris ou Lien Google Meet"
                      className="w-full bg-slate-50 border border-slate-200 text-slate-950 p-3 rounded-xl outline-none placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-sm transition-all"
                    />
                  </div>
                </div>

                {/* Footer Actions */}
                <div className="flex justify-end gap-3 pt-6 border-t border-slate-200">
                  <button
                    type="button"
                    onClick={onClose}
                    className="px-5 py-2.5 border border-slate-200 text-slate-600 hover:text-slate-950 hover:bg-slate-50 transition-colors text-sm font-medium rounded-xl"
                  >
                    {t('common.cancel', 'Annuler')}
                  </button>
                  <button
                    type="submit"
                    disabled={isSubmitting || !date || !time || !location}
                    className="px-5 py-2.5 bg-[#8cedaa] text-slate-950 text-sm font-semibold rounded-xl hover:bg-[#7bc897] transition-colors flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    {isSubmitting ? (
                      <>
                        <Loader2 className="w-4 h-4 animate-spin" />
                        {t('common.sending', 'Envoi...')}
                      </>
                    ) : (
                      <>
                        <Send className="w-4 h-4" />
                        {t('convoquer_modal.send', 'Envoyer Convocation')}
                      </>
                    )}
                  </button>
                </div>
              </form>
            </motion.div>
          </motion.div>
        </React.Fragment>
      )}
    </AnimatePresence>
  );
};

export default ConvoquerModal;
