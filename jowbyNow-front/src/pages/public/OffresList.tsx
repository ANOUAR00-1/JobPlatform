import React, { useEffect, useState } from 'react';
import { MapPin, Clock, Search, Loader2, ArrowUpRight, Building, Briefcase } from 'lucide-react';
import { useNavigate } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import api from '../../services/api';
import { useToast } from '../../hooks/useToast';

interface JobOffer {
  id: number;
  titre: string;
  description: string;
  company_name?: string;
  ville?: { nom: string };
  type_contrat: string;
  salaire: string;
  created_at: string;
}

const OffresList: React.FC = () => {
  const navigate = useNavigate();
  const { t } = useTranslation();
  const { addToast } = useToast();
  
  const [offres, setOffres] = useState<JobOffer[]>([]);
  const [loading, setLoading] = useState(true);
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [total, setTotal] = useState(0);
  
  // Filter states
  const [selectedContractTypes, setSelectedContractTypes] = useState<string[]>([]);
  const [selectedLocations, setSelectedLocations] = useState<string[]>([]);
  const [searchQuery, setSearchQuery] = useState('');

  useEffect(() => {
    const fetchJobs = async () => {
      try {
        setLoading(true);
        
        // Build query params
        const params = new URLSearchParams();
        params.append('page', currentPage.toString());
        params.append('per_page', '10');
        
        if (selectedContractTypes.length > 0) {
          params.append('contract_types', selectedContractTypes.join(','));
        }
        
        if (selectedLocations.length > 0) {
          params.append('locations', selectedLocations.join(','));
        }
        
        if (searchQuery.trim()) {
          params.append('search', searchQuery.trim());
        }
        
        const res = await api.get(`/jobs?${params.toString()}`);
        setOffres(res.data.data || []);
        setTotalPages(res.data.last_page || 1);
        setTotal(res.data.total || 0);
      } catch (error) {
        console.error(error);
        addToast('Erreur lors du chargement des offres...', 'error');
      } finally {
        setLoading(false);
      }
    };

    fetchJobs();
  }, [addToast, currentPage, selectedContractTypes, selectedLocations, searchQuery]);

  // Handle contract type filter toggle
  const toggleContractType = (type: string) => {
    setSelectedContractTypes(prev => {
      if (prev.includes(type)) {
        return prev.filter(t => t !== type);
      } else {
        return [...prev, type];
      }
    });
    setCurrentPage(1); // Reset to first page when filter changes
  };

  // Handle location filter toggle
  const toggleLocation = (location: string) => {
    setSelectedLocations(prev => {
      if (prev.includes(location)) {
        return prev.filter(l => l !== location);
      } else {
        return [...prev, location];
      }
    });
    setCurrentPage(1); // Reset to first page when filter changes
  };

  // Handle search
  const handleSearch = () => {
    setCurrentPage(1); // Reset to first page when searching
  };

  return (
    <div className="w-full min-h-screen bg-slate-50 dark:bg-slate-950 pb-20">
      {/* Search Header */}
      <div className="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 pt-16 pb-12 px-6">
        <div className="max-w-6xl mx-auto text-center">
          <h1 className="text-4xl md:text-5xl font-bold text-slate-950 dark:text-white tracking-tight mb-4">
            {t('public_jobs.title', 'Discover Open Roles')}
          </h1>
          <p className="text-lg text-slate-500 dark:text-slate-400 max-w-2xl mx-auto mb-10">
            {t('public_jobs.subtitle', 'Browse positions matching your skills. Transparent salaries, no hidden terms.')}
          </p>
          
          <div className="max-w-3xl mx-auto flex flex-col md:flex-row gap-4">
            <div className="flex-1 relative shadow-sm rounded-xl">
              <Search className="absolute left-4 rtl:left-auto rtl:right-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 dark:text-slate-500 pointer-events-none" />
              <input 
                type="text" 
                placeholder={t('public_jobs.search_placeholder', 'Search by title, keyword...')}
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                onKeyDown={(e) => e.key === 'Enter' && handleSearch()}
                className="w-full h-14 pl-12 pr-4 rtl:pl-4 rtl:pr-12 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-950 dark:text-white outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all font-medium"
              />
            </div>
            <button 
              onClick={handleSearch}
              className="h-14 px-8 text-base shadow-sm rounded-xl shrink-0 bg-[#8cedaa] text-slate-950 font-semibold hover:bg-[#7bc897] transition-colors"
            >
              {t('public_jobs.search_btn', 'Search')}
            </button>
          </div>
        </div>
      </div>

      <div className="max-w-6xl mx-auto px-6 mt-10 grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        {/* Sidebar Filters */}
        <aside className="lg:col-span-1 space-y-8 lg:sticky lg:top-24 lg:self-start">
          <div>
            <h3 className="text-sm font-semibold text-slate-950 dark:text-white mb-4">
              {t('public_jobs.filters.contract', 'Contract Type')}
            </h3>
            <div className="space-y-3">
              {['CDI', 'CDD', 'Freelance', 'Stage'].map(type => (
                <label key={type} className="flex items-center gap-3 cursor-pointer group">
                  <input 
                    type="checkbox" 
                    checked={selectedContractTypes.includes(type)}
                    onChange={() => toggleContractType(type)}
                    className="w-4 h-4 rounded border-slate-300 dark:border-slate-600 text-emerald-600 focus:ring-emerald-600 cursor-pointer" 
                  />
                  <span className="text-sm text-slate-600 dark:text-slate-400 group-hover:text-slate-950 dark:group-hover:text-white transition-colors">{type}</span>
                </label>
              ))}
            </div>
          </div>
          
          <div className="pt-8 border-t border-slate-200 dark:border-slate-800">
            <h3 className="text-sm font-semibold text-slate-950 dark:text-white mb-4">
              {t('public_jobs.filters.location', 'Location')}
            </h3>
            <div className="space-y-3">
              {['Casablanca', 'Rabat', 'Tanger', 'Marrakech'].map(loc => (
                <label key={loc} className="flex items-center gap-3 cursor-pointer group">
                  <input 
                    type="checkbox" 
                    checked={selectedLocations.includes(loc)}
                    onChange={() => toggleLocation(loc)}
                    className="w-4 h-4 rounded border-slate-300 dark:border-slate-600 text-emerald-600 focus:ring-emerald-600 cursor-pointer" 
                  />
                  <span className="text-sm text-slate-600 dark:text-slate-400 group-hover:text-slate-950 dark:group-hover:text-white transition-colors">{loc}</span>
                </label>
              ))}
            </div>
          </div>
        </aside>

        {/* Results List */}
        <main className="lg:col-span-3 space-y-4">
          {loading ? (
            <div className="py-24 flex items-center justify-center">
              <Loader2 className="w-8 h-8 animate-spin text-emerald-600" />
            </div>
          ) : offres.length === 0 ? (
            <div className="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-16 text-center shadow-sm flex flex-col items-center">
              <Search className="w-12 h-12 text-slate-300 dark:text-slate-600 mb-4" />
              <h3 className="text-lg font-semibold text-slate-950 dark:text-white mb-2">No positions found</h3>
              <p className="text-slate-500 dark:text-slate-400 text-sm">{t('public_jobs.no_positions', 'Adjust your filters or query.')}</p>
            </div>
          ) : (
            offres.map((offre) => (
              <div 
                key={offre.id} 
                className="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 hover:shadow-md hover:border-emerald-300 dark:hover:border-emerald-700 transition-all cursor-pointer group"
                onClick={() => navigate(`/offres/${offre.id}`)}
              >
                <div className="flex flex-col md:flex-row md:items-start justify-between gap-6">
                  
                  <div className="flex-1">
                    <h3 className="text-xl font-semibold text-slate-950 dark:text-white mb-3 group-hover:text-emerald-700 dark:group-hover:text-emerald-400 transition-colors">
                      {offre.titre}
                    </h3>
                    
                    <div className="flex flex-wrap items-center gap-4 text-sm text-slate-600 dark:text-slate-400 mb-4">
                      <span className="flex items-center gap-1.5 font-medium text-slate-700 dark:text-slate-300">
                        <Building className="w-4 h-4" /> {offre.company_name || t('public_jobs.confidential', 'Confidential')}
                      </span>
                      <span className="flex items-center gap-1.5">
                        <MapPin className="w-4 h-4" /> {offre.ville?.nom || 'N/A'}
                      </span>
                      <span className="flex items-center gap-1.5">
                        <Briefcase className="w-4 h-4" /> {offre.type_contrat}
                      </span>
                    </div>

                    <div className="flex items-center gap-2">
                      <span className="inline-flex px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-semibold">
                        {offre.type_contrat}
                      </span>
                      {offre.salaire && (
                        <span className="inline-flex px-2.5 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 text-xs font-semibold">
                          {offre.salaire}
                        </span>
                      )}
                    </div>
                  </div>
                  
                  <div className="flex items-center justify-between md:flex-col md:items-end md:justify-between h-full min-h-[90px]">
                    <span className="flex items-center gap-1.5 text-xs text-slate-400 dark:text-slate-500">
                      <Clock className="w-3.5 h-3.5" /> {new Date(offre.created_at).toLocaleDateString()}
                    </span>
                    <button className="opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap mt-4 md:mt-0 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                      View Details <ArrowUpRight className="w-4 h-4 ml-2 rtl:hidden inline" /><ArrowUpRight className="w-4 h-4 mr-2 hidden rtl:inline rtl:-scale-x-100" />
                    </button>
                  </div>

                </div>
              </div>
            ))
          )}
          
          {/* Pagination */}
          {!loading && offres.length > 0 && totalPages > 1 && (
            <div className="flex items-center justify-between pt-8 border-t border-slate-200 dark:border-slate-800">
              <div className="text-sm text-slate-600 dark:text-slate-400">
                Showing page {currentPage} of {totalPages} ({total} total jobs)
              </div>
              <div className="flex items-center gap-2">
                <button
                  onClick={() => setCurrentPage(prev => Math.max(1, prev - 1))}
                  disabled={currentPage === 1}
                  className="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  Previous
                </button>
                
                {/* Page Numbers */}
                <div className="flex items-center gap-1">
                  {Array.from({ length: Math.min(5, totalPages) }, (_, i) => {
                    let pageNum: number;
                    if (totalPages <= 5) {
                      pageNum = i + 1;
                    } else if (currentPage <= 3) {
                      pageNum = i + 1;
                    } else if (currentPage >= totalPages - 2) {
                      pageNum = totalPages - 4 + i;
                    } else {
                      pageNum = currentPage - 2 + i;
                    }
                    
                    return (
                      <button
                        key={pageNum}
                        onClick={() => setCurrentPage(pageNum)}
                        className={`w-10 h-10 rounded-xl text-sm font-medium transition-colors ${
                          currentPage === pageNum
                            ? 'bg-[#8cedaa] text-slate-950'
                            : 'border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800'
                        }`}
                      >
                        {pageNum}
                      </button>
                    );
                  })}
                </div>
                
                <button
                  onClick={() => setCurrentPage(prev => Math.min(totalPages, prev + 1))}
                  disabled={currentPage === totalPages}
                  className="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  Next
                </button>
              </div>
            </div>
          )}
        </main>
      </div>
    </div>
  );
};

export default OffresList;
