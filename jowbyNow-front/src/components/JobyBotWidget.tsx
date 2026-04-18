import React, { useState, useRef, useEffect } from 'react';
import { Bot, X, Send, User, Loader2 } from 'lucide-react';
import axios from 'axios';

interface Message {
  role: 'user' | 'bot';
  text: string;
}

const JobyBotWidget: React.FC = () => {
  const [isOpen, setIsOpen] = useState(false);
  const [messages, setMessages] = useState<Message[]>([
    { role: 'bot', text: "Hello! I'm JobyBot, your elite recruitment assistant. How can I help you find your next opportunity today?" }
  ]);
  const [inputValue, setInputValue] = useState('');
  const [isLoading, setIsLoading] = useState(false);
  const messagesEndRef = useRef<HTMLDivElement>(null);

  const scrollToBottom = () => {
    messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
  };

  useEffect(() => {
    if (isOpen) {
      scrollToBottom();
    }
  }, [messages, isOpen, isLoading]);

  const handleSend = async () => {
    if (!inputValue.trim()) return;

    const userMessage = inputValue.trim();
    setInputValue('');
    setMessages(prev => [...prev, { role: 'user', text: userMessage }]);
    setIsLoading(true);

    try {
      const response = await axios.post('http://localhost:8000/api/chat', { message: userMessage });
      if (response.data.success) {
        setMessages(prev => [...prev, { role: 'bot', text: response.data.reply }]);
      } else {
        setMessages(prev => [...prev, { role: 'bot', text: 'Sorry, I encountered an issue while processing your request.' }]);
      }
    } catch (error) {
      console.error("Chatbot Error:", error);
      setMessages(prev => [...prev, { role: 'bot', text: 'Network error. Please try again later. Make sure the backend is running.' }]);
    } finally {
      setIsLoading(false);
    }
  };

  const handleKeyDown = (e: React.KeyboardEvent<HTMLInputElement>) => {
    if (e.key === 'Enter') {
      handleSend();
    }
  };

  return (
    <>
      {/* Floating Action Button */}
      <button
        onClick={() => setIsOpen(true)}
        className={`fixed bottom-6 left-6 rtl:left-auto rtl:right-6 p-4 rounded-2xl bg-[#8cedaa] text-slate-950 shadow-xl hover:shadow-2xl hover:bg-[#7bc897] hover:scale-105 transition-all duration-300 z-[9999] ${isOpen ? 'opacity-0 pointer-events-none scale-0' : 'opacity-100 scale-100'}`}
        aria-label="Open AI Assistant"
      >
        <Bot size={28} />
      </button>

      {/* Chat Window */}
      <div 
        className={`fixed bottom-6 left-6 rtl:left-auto rtl:right-6 w-[400px] max-w-[calc(100vw-3rem)] h-[600px] max-h-[calc(100vh-6rem)] z-[9999] flex flex-col transform transition-all duration-300 ease-out origin-bottom-left rtl:origin-bottom-right
        ${isOpen ? 'scale-100 opacity-100 translate-y-0' : 'scale-90 opacity-0 translate-y-10 pointer-events-none'}
        bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden`}
      >
        {/* Header */}
        <div className="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50">
          <div className="flex items-center gap-3">
            <div className="w-11 h-11 rounded-xl bg-[#8cedaa] flex items-center justify-center shadow-sm">
              <Bot size={22} className="text-slate-950" />
            </div>
            <div>
              <h3 className="text-slate-950 font-display font-bold text-base">JobyBot</h3>
              <p className="text-[#2aa354] text-xs font-semibold flex items-center gap-1.5">
                <span className="w-2 h-2 rounded-full bg-[#2aa354] animate-pulse"></span> Online
              </p>
            </div>
          </div>
          <button 
            onClick={() => setIsOpen(false)}
            className="text-slate-600 hover:text-slate-950 transition-colors p-2 hover:bg-slate-100 rounded-lg"
          >
            <X size={20} />
          </button>
        </div>

        {/* Message History */}
        <div className="flex-1 overflow-y-auto p-6 flex flex-col gap-4 bg-white">
          {messages.map((msg, idx) => (
            <div key={idx} className={`flex gap-3 max-w-[85%] ${msg.role === 'user' ? 'ml-auto flex-row-reverse' : ''}`}>
              <div className={`w-8 h-8 rounded-xl shrink-0 flex items-center justify-center ${msg.role === 'user' ? 'bg-slate-100 border border-slate-200' : 'bg-[#8cedaa]/10 border border-[#8cedaa]/30'}`}>
                {msg.role === 'user' ? <User size={16} className="text-slate-600" /> : <Bot size={16} className="text-[#2aa354]" />}
              </div>
              <div className={`p-4 rounded-2xl text-sm leading-relaxed font-medium ${msg.role === 'user' ? 'bg-slate-100 border border-slate-200 text-slate-950 rounded-tr-md' : 'bg-slate-50 border border-slate-200 text-slate-950 rounded-tl-md'}`}>
                {msg.role === 'bot' ? (
                  <div className="whitespace-pre-wrap space-y-2" 
                       dangerouslySetInnerHTML={{ __html: msg.text.replace(/\*\*(.*?)\*\*/g, '<strong class="text-[#2aa354] font-bold">$1</strong>') }} />
                ) : (
                  msg.text
                )}
              </div>
            </div>
          ))}
          
          {isLoading && (
            <div className="flex gap-3 max-w-[85%]">
              <div className="w-8 h-8 rounded-xl shrink-0 flex items-center justify-center bg-[#8cedaa]/10 border border-[#8cedaa]/30">
                <Bot size={16} className="text-[#2aa354]" />
              </div>
              <div className="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-slate-950 rounded-tl-md flex items-center gap-3">
                <Loader2 size={16} className="animate-spin text-[#2aa354]" />
                <span className="text-xs font-semibold text-slate-600">Processing...</span>
              </div>
            </div>
          )}
          <div ref={messagesEndRef} className="h-1 w-1" />
        </div>

        {/* Input Area */}
        <div className="p-4 bg-slate-50 border-t border-slate-200">
          <div className="flex relative items-center">
            <input 
              type="text" 
              value={inputValue}
              onChange={(e) => setInputValue(e.target.value)}
              onKeyDown={handleKeyDown}
              placeholder="Ask JobyBot anything..."
              className="w-full bg-white border border-slate-200 rounded-xl py-3 pl-4 pr-12 text-sm text-slate-950 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#8cedaa]/20 focus:border-[#8cedaa] transition-all font-medium shadow-sm"
              autoComplete="off"
            />
            <button 
              onClick={handleSend}
              disabled={!inputValue.trim() || isLoading}
              className="absolute right-2 p-2 bg-[#8cedaa] text-slate-950 rounded-lg hover:bg-[#7bc897] transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-sm"
            >
              <Send size={16} />
            </button>
          </div>
        </div>
      </div>
    </>
  );
};

export default JobyBotWidget;
