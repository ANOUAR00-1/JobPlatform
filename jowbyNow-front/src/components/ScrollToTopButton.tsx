import React, { useState, useEffect } from 'react';
import { ArrowUp } from 'lucide-react';

const ScrollToTopButton: React.FC = () => {
  const [isVisible, setIsVisible] = useState(false);

  // Show button when page is scrolled down
  useEffect(() => {
    const toggleVisibility = () => {
      if (window.pageYOffset > 300) {
        setIsVisible(true);
      } else {
        setIsVisible(false);
      }
    };

    window.addEventListener('scroll', toggleVisibility);

    return () => {
      window.removeEventListener('scroll', toggleVisibility);
    };
  }, []);

  // Scroll to top smoothly
  const scrollToTop = () => {
    window.scrollTo({
      top: 0,
      behavior: 'smooth',
    });
  };

  return (
    <button
      onClick={scrollToTop}
      className={`fixed bottom-6 right-6 rtl:right-auto rtl:left-6 p-4 rounded-full bg-[#2aa354] text-white shadow-xl hover:shadow-2xl hover:bg-[#238a45] hover:scale-105 transition-all duration-300 z-[9998] ${
        isVisible ? 'opacity-100 scale-100' : 'opacity-0 scale-0 pointer-events-none'
      }`}
      aria-label="Scroll to top"
    >
      <ArrowUp size={24} strokeWidth={2.5} />
    </button>
  );
};

export default ScrollToTopButton;
