import React from 'react';
import { motion } from 'framer-motion';
import { MapPin, Phone, Clock } from 'lucide-react';

const Location = ({ isDark }) => {
    const cardClass = isDark
        ? "bg-gray-800/40 backdrop-blur-md"
        : "bg-white/30 backdrop-blur-md";

    const textClass = isDark ? "text-white" : "text-gray-900";

    const infoCardClass = isDark
        ? "bg-gray-700/50 hover:bg-gray-700/70"
        : "bg-white/50 hover:bg-white/70";

    return (
        <section id="branches" className="py-16 sm:py-20 px-4 sm:px-6 lg:px-8">
            <div className="container mx-auto max-w-7xl">
                <motion.div
                    className="w-full"
                    initial={{ opacity: 0, y: 20 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ delay: 0.2 }}
                >
                    <div className={`${cardClass} rounded-2xl p-4 sm:p-6 lg:p-8`}>
                        {/* Section Header */}
                        <div className="text-center mb-8 sm:mb-12">
                            <h2 className={`text-2xl sm:text-3xl lg:text-4xl font-bold ${textClass} mb-4`}>
                                <span className={isDark ? 'text-white' : 'text-gray-900'}>
                                   Lokasi Damar {' '}
                                </span>
                                <span className="text-[#E25822]">
                                    Exchange
                                </span>
                            </h2>
                            <p className={`${isDark ? 'text-gray-300' : 'text-gray-600'} text-lg max-w-2xl mx-auto`}>
                                Kunjungi kantor kami untuk layanan penukaran mata uang asing terpercaya
                            </p>
                        </div>

                        <div className="flex flex-col lg:flex-row gap-8 items-start">
                            {/* Location Info Cards */}
                            <div className="w-full lg:w-1/2 space-y-6">
                                {/* Address Card */}
                                <motion.div
                                    className={`${infoCardClass} p-6 rounded-xl transition-all duration-300`}
                                    whileHover={{ scale: 1.02 }}
                                >
                                    <div className="flex items-start gap-4">
                                        <div className="p-3 rounded-full bg-orange-500/20">
                                            <MapPin className="w-6 h-6 text-orange-500" />
                                        </div>
                                        <div>
                                            <h3 className={`text-xl font-semibold mb-2 ${textClass}`}>
                                                Damar Artha Abadi Money Changer
                                            </h3>
                                            <p className={`${isDark ? 'text-gray-300' : 'text-gray-600'} leading-relaxed`}>
                                                Jl. Raya Pererenan Tanah Lot No.99,
                                                <br />
                                                Pererenan, Kec. Mengwi,
                                                <br />
                                                Kabupaten Badung, Bali 80351
                                            </p>
                                        </div>
                                    </div>
                                </motion.div>

                                {/* Operating Hours Card */}
                                <motion.div
                                    className={`${infoCardClass} p-6 rounded-xl transition-all duration-300`}
                                    whileHover={{ scale: 1.02 }}
                                >
                                    <div className="flex items-start gap-4">
                                        <div className="p-3 rounded-full bg-orange-500/20">
                                            <Clock className="w-6 h-6 text-orange-500" />
                                        </div>
                                        <div>
                                            <h3 className={`text-xl font-semibold mb-2 ${textClass}`}>
                                                Jam Operasional
                                            </h3>
                                            <div className={`grid grid-cols-2 gap-2 ${isDark ? 'text-gray-300' : 'text-gray-600'}`}>
                                                <p>Senin - Minggu:</p>
                                                <p>09:00 - 22:00 WITA</p>
                                            </div>
                                        </div>
                                    </div>
                                </motion.div>

                                {/* Contact Card */}
                                <motion.div
                                    className={`${infoCardClass} p-6 rounded-xl transition-all duration-300`}
                                    whileHover={{ scale: 1.02 }}
                                >
                                    <div className="flex items-start gap-4">
                                        <div className="p-3 rounded-full bg-orange-500/20">
                                            <Phone className="w-6 h-6 text-orange-500" />
                                        </div>
                                        <div>
                                            <h3 className={`text-xl font-semibold mb-2 ${textClass}`}>
                                                Kontak Kami
                                            </h3>
                                            <p className={`${isDark ? 'text-gray-300' : 'text-gray-600'}`}>
                                                0813-3840-7237
                                            </p>
                                        </div>
                                    </div>
                                </motion.div>
                            </div>

                            {/* Map Container */}
                            <motion.div
                                className="w-full lg:w-1/2"
                                initial={{ opacity: 0, x: 20 }}
                                animate={{ opacity: 1, x: 0 }}
                                transition={{ delay: 0.4 }}
                            >
                                <div className="h-[500px] rounded-xl overflow-hidden shadow-lg">
                                    <iframe
                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3944.611838965092!2d115.13679249999998!3d-8.6332107!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd239a33371e2e3%3A0x39ece2567f92eac6!2sDamar%20Artha%20Abadi%20Money%20Changer!5e0!3m2!1sid!2sid!4v1735600434839!5m2!1sid!2sid"
                                        width="100%"
                                        height="100%"
                                        style={{ border: 0 }}
                                        allowFullScreen=""
                                        loading="lazy"
                                        referrerPolicy="no-referrer-when-downgrade"
                                        className="rounded-xl"
                                    />
                                </div>
                            </motion.div>
                        </div>
                    </div>
                </motion.div>
            </div>
        </section>
    );
};

export default Location;
