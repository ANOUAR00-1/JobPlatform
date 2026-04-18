import React from 'react';
import { useTranslation } from 'react-i18next';
import { FileText, Scale, AlertCircle, UserX, Shield, Gavel } from 'lucide-react';

const TermsOfService: React.FC = () => {
  const { t } = useTranslation();

  return (
    <div className="w-full min-h-screen bg-slate-50 font-sans">
      {/* Header */}
      <div className="bg-white border-b border-slate-200 py-16 px-6">
        <div className="max-w-4xl mx-auto text-center">
          <div className="inline-flex items-center justify-center w-16 h-16 bg-emerald-100 rounded-2xl mb-6">
            <FileText className="w-8 h-8 text-emerald-600" />
          </div>
          <h1 className="text-4xl md:text-5xl font-bold text-slate-950 tracking-tight mb-4">
            Terms of Service
          </h1>
          <p className="text-lg text-slate-500">
            Last updated: April 18, 2026
          </p>
        </div>
      </div>

      {/* Content */}
      <div className="max-w-4xl mx-auto px-6 py-16">
        <div className="bg-white rounded-2xl border border-slate-200 p-8 md:p-12 space-y-12">
          
          {/* Introduction */}
          <section>
            <p className="text-slate-600 leading-relaxed">
              Welcome to JobyNow. These Terms of Service ("Terms") govern your access to and use of our recruitment platform. By accessing or using JobyNow, you agree to be bound by these Terms. If you do not agree to these Terms, please do not use our services.
            </p>
          </section>

          {/* Acceptance of Terms */}
          <section>
            <div className="flex items-center gap-3 mb-6">
              <div className="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center">
                <Scale className="w-5 h-5 text-slate-600" />
              </div>
              <h2 className="text-2xl font-bold text-slate-950">Acceptance of Terms</h2>
            </div>
            
            <p className="text-slate-600 leading-relaxed">
              By creating an account or using our platform, you acknowledge that you have read, understood, and agree to be bound by these Terms and our Privacy Policy. These Terms apply to all users of the platform, including candidates, employers, and visitors.
            </p>
          </section>

          {/* User Accounts */}
          <section>
            <div className="flex items-center gap-3 mb-6">
              <div className="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center">
                <Shield className="w-5 h-5 text-slate-600" />
              </div>
              <h2 className="text-2xl font-bold text-slate-950">User Accounts</h2>
            </div>
            
            <div className="space-y-6">
              <div>
                <h3 className="text-lg font-semibold text-slate-950 mb-3">Account Registration</h3>
                <p className="text-slate-600 leading-relaxed mb-3">
                  To access certain features of our platform, you must register for an account. You agree to:
                </p>
                <ul className="space-y-2 text-slate-600">
                  <li className="flex items-start gap-3">
                    <span className="w-1.5 h-1.5 rounded-full bg-emerald-500 mt-2 shrink-0"></span>
                    <span>Provide accurate, current, and complete information during registration</span>
                  </li>
                  <li className="flex items-start gap-3">
                    <span className="w-1.5 h-1.5 rounded-full bg-emerald-500 mt-2 shrink-0"></span>
                    <span>Maintain and promptly update your account information</span>
                  </li>
                  <li className="flex items-start gap-3">
                    <span className="w-1.5 h-1.5 rounded-full bg-emerald-500 mt-2 shrink-0"></span>
                    <span>Maintain the security of your password and account</span>
                  </li>
                  <li className="flex items-start gap-3">
                    <span className="w-1.5 h-1.5 rounded-full bg-emerald-500 mt-2 shrink-0"></span>
                    <span>Accept responsibility for all activities that occur under your account</span>
                  </li>
                </ul>
              </div>

              <div>
                <h3 className="text-lg font-semibold text-slate-950 mb-3">Account Types</h3>
                <p className="text-slate-600 leading-relaxed">
                  JobyNow offers two types of accounts: Candidate accounts for job seekers and Enterprise accounts for employers. Each account type has specific features and responsibilities as outlined in these Terms.
                </p>
              </div>
            </div>
          </section>

          {/* User Conduct */}
          <section>
            <div className="flex items-center gap-3 mb-6">
              <div className="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center">
                <AlertCircle className="w-5 h-5 text-slate-600" />
              </div>
              <h2 className="text-2xl font-bold text-slate-950">User Conduct</h2>
            </div>
            
            <p className="text-slate-600 leading-relaxed mb-4">
              You agree not to use the platform to:
            </p>
            <ul className="space-y-2 text-slate-600">
              <li className="flex items-start gap-3">
                <span className="w-1.5 h-1.5 rounded-full bg-emerald-500 mt-2 shrink-0"></span>
                <span>Violate any applicable laws or regulations</span>
              </li>
              <li className="flex items-start gap-3">
                <span className="w-1.5 h-1.5 rounded-full bg-emerald-500 mt-2 shrink-0"></span>
                <span>Post false, misleading, or fraudulent information</span>
              </li>
              <li className="flex items-start gap-3">
                <span className="w-1.5 h-1.5 rounded-full bg-emerald-500 mt-2 shrink-0"></span>
                <span>Harass, abuse, or harm other users</span>
              </li>
              <li className="flex items-start gap-3">
                <span className="w-1.5 h-1.5 rounded-full bg-emerald-500 mt-2 shrink-0"></span>
                <span>Impersonate any person or entity</span>
              </li>
              <li className="flex items-start gap-3">
                <span className="w-1.5 h-1.5 rounded-full bg-emerald-500 mt-2 shrink-0"></span>
                <span>Interfere with or disrupt the platform's operation</span>
              </li>
              <li className="flex items-start gap-3">
                <span className="w-1.5 h-1.5 rounded-full bg-emerald-500 mt-2 shrink-0"></span>
                <span>Attempt to gain unauthorized access to any part of the platform</span>
              </li>
              <li className="flex items-start gap-3">
                <span className="w-1.5 h-1.5 rounded-full bg-emerald-500 mt-2 shrink-0"></span>
                <span>Use automated systems to access the platform without permission</span>
              </li>
            </ul>
          </section>

          {/* Content and Intellectual Property */}
          <section>
            <div className="flex items-center gap-3 mb-6">
              <div className="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center">
                <Gavel className="w-5 h-5 text-slate-600" />
              </div>
              <h2 className="text-2xl font-bold text-slate-950">Content and Intellectual Property</h2>
            </div>
            
            <div className="space-y-6">
              <div>
                <h3 className="text-lg font-semibold text-slate-950 mb-3">Your Content</h3>
                <p className="text-slate-600 leading-relaxed">
                  You retain ownership of any content you submit to the platform, including resumes, job postings, and messages. By submitting content, you grant JobyNow a worldwide, non-exclusive, royalty-free license to use, reproduce, and display your content for the purpose of operating and improving our services.
                </p>
              </div>

              <div>
                <h3 className="text-lg font-semibold text-slate-950 mb-3">Our Content</h3>
                <p className="text-slate-600 leading-relaxed">
                  The platform and its original content, features, and functionality are owned by JobyNow and are protected by international copyright, trademark, and other intellectual property laws. You may not copy, modify, distribute, or create derivative works based on our content without explicit permission.
                </p>
              </div>
            </div>
          </section>

          {/* Job Postings and Applications */}
          <section>
            <div className="flex items-center gap-3 mb-6">
              <div className="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center">
                <FileText className="w-5 h-5 text-slate-600" />
              </div>
              <h2 className="text-2xl font-bold text-slate-950">Job Postings and Applications</h2>
            </div>
            
            <div className="space-y-6">
              <div>
                <h3 className="text-lg font-semibold text-slate-950 mb-3">For Employers</h3>
                <p className="text-slate-600 leading-relaxed mb-3">
                  Employers agree to:
                </p>
                <ul className="space-y-2 text-slate-600">
                  <li className="flex items-start gap-3">
                    <span className="w-1.5 h-1.5 rounded-full bg-emerald-500 mt-2 shrink-0"></span>
                    <span>Post only legitimate job opportunities</span>
                  </li>
                  <li className="flex items-start gap-3">
                    <span className="w-1.5 h-1.5 rounded-full bg-emerald-500 mt-2 shrink-0"></span>
                    <span>Provide accurate job descriptions and requirements</span>
                  </li>
                  <li className="flex items-start gap-3">
                    <span className="w-1.5 h-1.5 rounded-full bg-emerald-500 mt-2 shrink-0"></span>
                    <span>Comply with all applicable employment laws</span>
                  </li>
                  <li className="flex items-start gap-3">
                    <span className="w-1.5 h-1.5 rounded-full bg-emerald-500 mt-2 shrink-0"></span>
                    <span>Not discriminate based on protected characteristics</span>
                  </li>
                </ul>
              </div>

              <div>
                <h3 className="text-lg font-semibold text-slate-950 mb-3">For Candidates</h3>
                <p className="text-slate-600 leading-relaxed mb-3">
                  Candidates agree to:
                </p>
                <ul className="space-y-2 text-slate-600">
                  <li className="flex items-start gap-3">
                    <span className="w-1.5 h-1.5 rounded-full bg-emerald-500 mt-2 shrink-0"></span>
                    <span>Provide truthful and accurate information in applications</span>
                  </li>
                  <li className="flex items-start gap-3">
                    <span className="w-1.5 h-1.5 rounded-full bg-emerald-500 mt-2 shrink-0"></span>
                    <span>Not misrepresent qualifications or experience</span>
                  </li>
                  <li className="flex items-start gap-3">
                    <span className="w-1.5 h-1.5 rounded-full bg-emerald-500 mt-2 shrink-0"></span>
                    <span>Respect the confidentiality of employer information</span>
                  </li>
                </ul>
              </div>
            </div>
          </section>

          {/* Termination */}
          <section>
            <div className="flex items-center gap-3 mb-6">
              <div className="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center">
                <UserX className="w-5 h-5 text-slate-600" />
              </div>
              <h2 className="text-2xl font-bold text-slate-950">Termination</h2>
            </div>
            
            <p className="text-slate-600 leading-relaxed">
              We reserve the right to suspend or terminate your account at any time, with or without notice, for any reason, including violation of these Terms. You may also terminate your account at any time by contacting us. Upon termination, your right to use the platform will immediately cease.
            </p>
          </section>

          {/* Disclaimer of Warranties */}
          <section>
            <div className="flex items-center gap-3 mb-6">
              <div className="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center">
                <AlertCircle className="w-5 h-5 text-slate-600" />
              </div>
              <h2 className="text-2xl font-bold text-slate-950">Disclaimer of Warranties</h2>
            </div>
            
            <p className="text-slate-600 leading-relaxed">
              The platform is provided "as is" and "as available" without warranties of any kind, either express or implied. We do not guarantee that the platform will be uninterrupted, secure, or error-free. We are not responsible for the accuracy or reliability of any content posted by users.
            </p>
          </section>

          {/* Limitation of Liability */}
          <section>
            <div className="flex items-center gap-3 mb-6">
              <div className="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center">
                <Scale className="w-5 h-5 text-slate-600" />
              </div>
              <h2 className="text-2xl font-bold text-slate-950">Limitation of Liability</h2>
            </div>
            
            <p className="text-slate-600 leading-relaxed">
              To the maximum extent permitted by law, JobyNow shall not be liable for any indirect, incidental, special, consequential, or punitive damages, or any loss of profits or revenues, whether incurred directly or indirectly, or any loss of data, use, goodwill, or other intangible losses resulting from your use of the platform.
            </p>
          </section>

          {/* Governing Law */}
          <section>
            <div className="flex items-center gap-3 mb-6">
              <div className="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center">
                <Gavel className="w-5 h-5 text-slate-600" />
              </div>
              <h2 className="text-2xl font-bold text-slate-950">Governing Law</h2>
            </div>
            
            <p className="text-slate-600 leading-relaxed">
              These Terms shall be governed by and construed in accordance with the laws of Morocco, without regard to its conflict of law provisions. Any disputes arising from these Terms or your use of the platform shall be subject to the exclusive jurisdiction of the courts of Casablanca, Morocco.
            </p>
          </section>

          {/* Changes to Terms */}
          <section>
            <div className="flex items-center gap-3 mb-6">
              <div className="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center">
                <FileText className="w-5 h-5 text-slate-600" />
              </div>
              <h2 className="text-2xl font-bold text-slate-950">Changes to Terms</h2>
            </div>
            
            <p className="text-slate-600 leading-relaxed">
              We reserve the right to modify these Terms at any time. We will notify users of any material changes by posting the new Terms on this page and updating the "Last updated" date. Your continued use of the platform after such changes constitutes your acceptance of the new Terms.
            </p>
          </section>

          {/* Contact Information */}
          <section>
            <div className="flex items-center gap-3 mb-6">
              <div className="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center">
                <Shield className="w-5 h-5 text-slate-600" />
              </div>
              <h2 className="text-2xl font-bold text-slate-950">Contact Us</h2>
            </div>
            
            <p className="text-slate-600 leading-relaxed mb-4">
              If you have any questions about these Terms, please contact us at:
            </p>
            <div className="bg-slate-50 rounded-xl p-6 border border-slate-200">
              <p className="text-slate-950 font-semibold mb-2">JobyNow Legal Team</p>
              <p className="text-slate-600">Email: legal@jobynow.com</p>
              <p className="text-slate-600">Address: Casablanca, Morocco</p>
            </div>
          </section>

          {/* Acknowledgment */}
          <section className="pt-8 border-t border-slate-200">
            <p className="text-sm text-slate-500 leading-relaxed">
              By using JobyNow, you acknowledge that you have read and understood these Terms of Service and agree to be bound by them.
            </p>
          </section>
        </div>
      </div>
    </div>
  );
};

export default TermsOfService;
