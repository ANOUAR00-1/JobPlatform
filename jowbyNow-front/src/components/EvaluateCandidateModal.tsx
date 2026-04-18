import React, { useState } from 'react';
import { useTranslation } from 'react-i18next';
import { Star, X, Loader2, MessageSquare } from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';

interface EvaluateCandidateModalProps {
  isOpen: boolean;
  onClose: () => void;
  onSubmit: (note: number, commentaire: string) => Promise<void>;
  candidateName: string;
  initialNote?: number;
  initialComment?: string;
}

const EvaluateCandidateModal: React.FC<EvaluateCandidateModalProps> = ({
  isOpen,
  onClose,
  onSubmit,
  candidateName,
  initialNote = 0,
  initialComment = ''
}) => {
  const { t } = useTranslation();
  const [note, setNote] = useState<number>(initialNote);
  const [hoverNote, setHoverNote] = useState<number>(0);
  const [commentaire, setCommentaire] = useState<string>(initialComment);
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    if (note < 1 || note > 5) return;
    
    setIsSubmitting(true);
    try {
      await onSubmit(note, commentaire);
      onClose(); // Parent handles toast and closing
    } catch {
      // Parent catches and handles toast, we just reset state
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
                <h3 className="text-lg font-semibold text-slate-950">
                  {t('evaluate_modal.title', 'Évaluer le candidat')}
                </h3>
                <button
                  onClick={onClose}
                  className="text-slate-500 hover:text-slate-950 transition-colors rounded-lg p-1 hover:bg-slate-100"
                >
                  <X size={20} />
                </button>
              </div>

              {/* Body */}
              <form onSubmit={handleSubmit} className="p-6">
                <p className="text-slate-600 text-sm mb-6">
                  {t('evaluate_modal.description')} <span className="font-semibold text-slate-950">{candidateName}</span>.
                </p>

                {/* Star Rating */}
                <div className="mb-8">
                  <label className="block text-sm font-semibold text-slate-950 mb-3">
                    {t('evaluate_modal.note', 'Note (sur 5)')}
                  </label>
                  <div className="flex gap-2">
                    {[1, 2, 3, 4, 5].map((star) => (
                      <button
                        key={star}
                        type="button"
                        onClick={() => setNote(star)}
                        onMouseEnter={() => setHoverNote(star)}
                        onMouseLeave={() => setHoverNote(0)}
                        className="p-1 focus:outline-none transition-transform hover:scale-110"
                      >
                        <Star
                          size={32}
                          className={star <= (hoverNote || note) ? 'fill-yellow-500 text-yellow-500' : 'text-slate-300'}
                        />
                      </button>
                    ))}
                  </div>
                </div>

                {/* Comment Area */}
                <div className="mb-8">
                  <label className="flex items-center gap-2 text-sm font-semibold text-slate-950 mb-3">
                    <MessageSquare size={16} />
                    {t('evaluate_modal.comment', 'Commentaire privé')}
                  </label>
                  <textarea
                    value={commentaire}
                    onChange={(e) => setCommentaire(e.target.value)}
                    placeholder={t('evaluate_modal.comment_placeholder', 'Notes pour vous-même concernant ce candidat...')}
                    rows={4}
                    className="w-full bg-slate-50 border border-slate-200 text-slate-950 p-3 rounded-xl outline-none placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-sm transition-all resize-none"
                  />
                </div>

                {/* Footer Actions */}
                <div className="flex justify-end gap-3 pt-4 border-t border-slate-200">
                  <button
                    type="button"
                    onClick={onClose}
                    className="px-5 py-2.5 border border-slate-200 text-slate-600 hover:text-slate-950 hover:bg-slate-50 transition-colors text-sm font-medium rounded-xl"
                  >
                    {t('common.cancel', 'Annuler')}
                  </button>
                  <button
                    type="submit"
                    disabled={note === 0 || isSubmitting}
                    className="px-5 py-2.5 bg-[#8cedaa] text-slate-950 text-sm font-semibold rounded-xl hover:bg-[#7bc897] transition-colors flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    {isSubmitting ? (
                      <>
                        <Loader2 className="w-4 h-4 animate-spin" />
                        {t('common.saving', 'Enregistrement...')}
                      </>
                    ) : (
                      t('common.save', 'Enregistrer')
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

export default EvaluateCandidateModal;
