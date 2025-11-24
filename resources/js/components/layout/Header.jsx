// components/admin/Header.jsx
import React from 'react';
import { motion } from 'framer-motion';
import { Moon, Sun } from 'lucide-react';
import logo from '../../../../public/logo_example.png';

const Header = ({ isDark, setIsDark }) => {
    const scrollToSection = (sectionId) => {
        const element = document.getElementById(sectionId);
        if (element) {
            element.scrollIntoView({
                behavior: 'smooth',
                block: 'start',
                inline: 'nearest'
            });

            // Adjust for header height
            const headerHeight = 80; // This should match your header's height
            const elementPosition = element.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.pageYOffset - headerHeight;

            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth'
            });
        }
    };

    const navigationItems = [
        { id: 'home', label: 'Beranda' },
        { id: 'company-profile', label: 'Company Profile' },
        { id: 'location', label: 'Cabang' },
        { id: 'footer', label: 'Kontak' }
    ];

    return (
        <motion.header
            initial={{ opacity: 0, y: -20 }}
            animate={{ opacity: 1, y: 0 }}
            className={`fixed top-0 left-0 right-0 z-50 bg-transparent backdrop-blur-lg border-b ${
                isDark ? 'border-gray-700/30' : 'border-gray-200/30'
            }`}
        >
            <div className="container mx-auto px-4">
                <div className="flex items-center justify-between h-20"> {/* Increased height */}
                    {/* Logo with Text */}
                    <motion.div
                        whileHover={{ scale: 1.05 }}
                        whileTap={{ scale: 0.95 }}
                        className="flex items-center space-x-3"
                    >
                        <img
                            src={logo}
                            alt="Damar Exchange Logo"
                            className="h-12 w-auto" // Increased logo size
                        />
                        <div className="flex flex-col">
                            <span className="text-xl font-bold">
                                <span className={isDark ? 'text-white' : 'text-gray-900'}>
                                    Damar{' '}
                                </span>
                                <span className="text-[#E25822]">
                                    Exchange
                                </span>
                            </span>
                            <span className={`text-xs ${
                                isDark ? 'text-gray-400' : 'text-gray-600'
                            }`}>
                                Your Trusted Exchange Partner
                            </span>
                        </div>
                    </motion.div>

                    {/* Navigation */}
                    <nav className="hidden md:flex items-center space-x-8">
                        {navigationItems.map((item) => (
                            <motion.button
                                key={item.id}
                                whileHover={{ scale: 1.05 }}
                                whileTap={{ scale: 0.95 }}
                                onClick={() => scrollToSection(item.id)}
                                className={`text-sm font-medium ${
                                    isDark ? 'text-gray-300 hover:text-white' : 'text-gray-600 hover:text-gray-900'
                                } transition-colors`}
                            >
                                {item.label}
                            </motion.button>
                        ))}
                    </nav>

                    {/* Theme Toggle */}
                    <motion.button
                        whileHover={{ scale: 1.1 }}
                        whileTap={{ scale: 0.9 }}
                        onClick={() => setIsDark(!isDark)}
                        className={`p-2 rounded-full ${
                            isDark ? 'bg-gray-800/50 text-yellow-400' : 'bg-white/50 text-gray-600'
                        }`}
                    >
                        {isDark ? <Sun className="w-5 h-5" /> : <Moon className="w-5 h-5" />}
                    </motion.button>
                </div>
            </div>
        </motion.header>
    );
};

export default Header;
