import React from 'react';
import { motion } from 'framer-motion';
import { Facebook, Instagram, Youtube, Mail, Phone } from 'lucide-react';

const Footer = ({ isDark }) => {
    const textClass = isDark ? 'text-white' : 'text-gray-900';
    const mutedTextClass = isDark ? 'text-gray-400' : 'text-gray-600';
    const inputClass = isDark
        ? 'bg-gray-800/50 border-gray-700 focus:border-orange-500'
        : 'bg-white/50 border-gray-300 focus:border-orange-500';

    const socialLinks = [
        { icon: <Facebook className="w-5 h-5" />, href: "#" },
        { icon: <Instagram className="w-5 h-5" />, href: "#" },
        { icon: <Youtube className="w-5 h-5" />, href: "#" },
        { icon: <Mail className="w-5 h-5" />, href: "mailto:contact@damarexchange.com" },
        { icon: <Phone className="w-5 h-5" />, href: "tel:+6281338407237" },
    ];

    return (
        <footer className={`border-t ${isDark ? 'border-gray-800' : 'border-gray-200'} py-8 sm:py-12`}>
            <div className="container mx-auto px-4 sm:px-6 lg:px-8">
                <div className="grid grid-cols-1 gap-8 lg:grid-cols-3 lg:gap-12">
                    {/* Contact Us Section */}
                    <div className="space-y-4">
                        <h3 className={`text-lg font-semibold ${textClass}`}>CONTACT US</h3>
                        <p className={`${mutedTextClass} text-sm sm:text-base`}>
                            Address : Jl. Raya Pererenan Tanah Lot No.99, Pererenan,
                            Kec. Mengwi, Kabupaten Badung, Bali 80351
                        </p>
                        <div className="flex flex-wrap gap-3">
                            {socialLinks.map((link, index) => (
                                <motion.a
                                    key={index}
                                    href={link.href}
                                    whileHover={{ scale: 1.1 }}
                                    whileTap={{ scale: 0.9 }}
                                    className={`p-2 rounded-full ${
                                        isDark
                                            ? 'bg-gray-800 hover:bg-gray-700'
                                            : 'bg-gray-100 hover:bg-gray-200'
                                    } ${textClass} transition-colors`}
                                >
                                    {link.icon}
                                </motion.a>
                            ))}
                        </div>
                    </div>

                    {/* Links Section */}
                    <div className="space-y-4">
                        <h3 className={`text-lg font-semibold ${textClass}`}>LINK</h3>
                        <div className="flex flex-wrap items-center gap-6 sm:gap-8">
                            <img
                                src="/images/footer/BI_Logo.png"
                                alt="Bank Indonesia"
                                className="h-8 sm:h-10 lg:h-12 w-auto object-contain"
                            />
                            <img
                                src="/images/footer/OJK_Logo.png"
                                alt="OJK"
                                className="h-8 sm:h-10 lg:h-12 w-auto object-contain"
                            />
                            <img
                                src="/images/footer/PVA.png"
                                alt="ISO Certification"
                                className="h-8 sm:h-10 lg:h-12 w-auto object-contain"
                            />
                        </div>
                    </div>

                    {/* Send Message Section */}
                    <div className="space-y-4">
                        <h3 className={`text-lg font-semibold ${textClass}`}>SEND A MESSAGE</h3>
                        <form className="space-y-3">
                            <input
                                type="text"
                                placeholder="Name"
                                className={`w-full p-2 sm:p-3 text-sm sm:text-base rounded-lg border focus:outline-none focus:ring-1 focus:ring-orange-500 ${inputClass}`}
                            />
                            <input
                                type="email"
                                placeholder="Email"
                                className={`w-full p-2 sm:p-3 text-sm sm:text-base rounded-lg border focus:outline-none focus:ring-1 focus:ring-orange-500 ${inputClass}`}
                            />
                            <textarea
                                placeholder="Message"
                                rows="4"
                                className={`w-full p-2 sm:p-3 text-sm sm:text-base rounded-lg border focus:outline-none focus:ring-1 focus:ring-orange-500 ${inputClass}`}
                            />
                            <motion.button
                                whileHover={{ scale: 1.02 }}
                                whileTap={{ scale: 0.98 }}
                                className="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 sm:py-3 px-4 rounded-lg transition-colors text-sm sm:text-base font-medium"
                            >
                                SEND MESSAGE
                            </motion.button>
                        </form>
                    </div>
                </div>

                {/* Copyright */}
                <div className="mt-8 sm:mt-12 pt-4 border-t border-gray-800 text-center">
                    <p className={`${mutedTextClass} text-sm sm:text-base`}>
                        Copyright © {new Date().getFullYear()} Damar Exchange. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    );
};

export default Footer;
