import React from 'react';
import { motion } from 'framer-motion';

const CompanyProfile = ({ isDark }) => {
    const textClass = isDark ? 'text-white' : 'text-gray-900';
    const cardClass = isDark
        ? 'bg-gray-800/40 backdrop-blur-md'
        : 'bg-white/30 backdrop-blur-md';

    return (
        <section id="profile" className="min-h-screen py-20">
            <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                {/* Main Content */}
                <motion.div
                    initial={{ opacity: 0, y: 20 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.6 }}
                    viewport={{ once: true }}
                    className="space-y-12"
                >
                    {/* Video Profile Section */}
                    <motion.div
                        initial={{ opacity: 0, y: 20 }}
                        whileInView={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.6 }}
                        viewport={{ once: true }}
                        className={`${cardClass} rounded-2xl p-8`}
                    >
                        <h2 className={`text-3xl font-bold ${textClass} mb-6 text-center`}>
                            Video Profile
                        </h2>
                        <div className="relative w-full overflow-hidden rounded-xl">
                            <div className="relative pb-[56.25%] h-0">
                                <iframe
                                    className="absolute top-0 left-0 w-full h-full rounded-xl"
                                    src="https://www.youtube.com/embed/dQw4w9WgXcQ"
                                    title="Damar Exchange Company Profile"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowFullScreen
                                />
                            </div>
                        </div>
                    </motion.div>

                    {/* About Section */}
                    <div className={`${cardClass} rounded-2xl p-8 space-y-6`}>
                        <h2 className={`text-3xl font-bold ${textClass} mb-4`}>
                            <span className={isDark ? 'text-white' : 'text-gray-900'}>
                                   Tentang Damar {' '}
                            </span>
                            <span className="text-[#E25822]">
                                    Exchange
                            </span>
                        </h2>
                        <div className={`${textClass} space-y-4 text-lg leading-relaxed`}>
                            <p>
                                Damar Exchange adalah perusahan yang bergerak dalam industri valuta asing non-perbankan
                                yang didirikan pada 13 April 2005 dan telah mendapatkan izin No. 5/114/KEP.Dir.PM/2003
                                dari Bank Indonesia.
                            </p>
                            <p>
                                Selama hampir dua dekade, berbagai upaya peningkatan kinerja telah ditempuh oleh
                                Damar Exchange guna memenuhi permintaan masyarakat terkait valuta asing.
                                Kompleksitas industri valuta asing menuntut kami untuk terus belajar dan menjadi
                                lebih peka terhadap beragamnya kebutuhan masyarakat.
                            </p>
                        </div>
                    </div>

                    {/* SMILE Values Section */}
                    <motion.div
                        initial={{ opacity: 0, y: 20 }}
                        whileInView={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.6, delay: 0.2 }}
                        viewport={{ once: true }}
                        className={`${cardClass} rounded-2xl p-8`}
                    >
                        <h2 className={`text-3xl font-bold ${textClass} mb-6`}>
                            Budaya Kerja "SMILE"
                        </h2>
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            {[
                                {
                                    title: 'Satisfaction',
                                    desc: 'Kepuasan pelanggan adalah tujuan utama kami.'
                                },
                                {
                                    title: 'Meaningful',
                                    desc: 'Melayani pelanggan dengan tulus dan sepenuh hati.'
                                },
                                {
                                    title: 'Impressive',
                                    desc: 'Selalu memberikan pelayanan yang prima dan berkesan kepada para pelanggan.'
                                },
                                {
                                    title: 'Leading',
                                    desc: 'Menjadi yang terdepan dalam hal profesionalisme pada segi pelayanan.'
                                },
                                {
                                    title: 'Excellence',
                                    desc: 'Menciptakan suasana dan hubungan kerja yang harmonis serta kerjasama tim yang baik.'
                                }
                            ].map((value, index) => (
                                <motion.div
                                    key={value.title}
                                    initial={{ opacity: 0, y: 20 }}
                                    whileInView={{ opacity: 1, y: 0 }}
                                    transition={{ duration: 0.6, delay: index * 0.1 }}
                                    viewport={{ once: true }}
                                    className={`p-6 rounded-xl ${
                                        isDark ? 'bg-gray-700/50' : 'bg-white/50'
                                    }`}
                                >
                                    <h3 className={`text-xl font-semibold ${textClass} mb-2`}>
                                        {value.title}
                                    </h3>
                                    <p className={`${textClass} opacity-90`}>{value.desc}</p>
                                </motion.div>
                            ))}
                        </div>
                    </motion.div>

                    {/* Vision Section */}
                    <motion.div
                        initial={{ opacity: 0, y: 20 }}
                        whileInView={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.6, delay: 0.4 }}
                        viewport={{ once: true }}
                        className={`${cardClass} rounded-2xl p-8`}
                    >
                        <h2 className={`text-3xl font-bold ${textClass} mb-4`}>
                            Visi & Komitmen
                        </h2>
                        <p className={`${textClass} text-lg leading-relaxed`}>
                            Kami memilih untuk mengambil spesialiasasi dalam industri valuta asing
                            dengan menawarkan pelayanan jasa money changer yang memberikan rasa
                            kepercayaan, keamanan dan kepuasan bagi pelanggan. Tujuan kami adalah
                            untuk diakui sebagai perusahaan valuta asing terbaik dimanapun kami berada.
                        </p>
                    </motion.div>
                </motion.div>
            </div>
        </section>
    );
};

export default CompanyProfile;
