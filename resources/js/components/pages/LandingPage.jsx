import React, { useState, useEffect } from 'react';
import { motion } from 'framer-motion';
import { Moon, Sun, ArrowUpDown, RefreshCcw } from 'lucide-react';
import axios from '../../bootstrap.js';
import Header from '../layout/Header.jsx';
import Footer from '../layout/Footer.jsx'
import Carousel from '../layout/Carousel.jsx';
import Location from '../layout/Location.jsx'
import CompanyProfile from '../layout/CompanyProfile.jsx'
import DynamicOrnaments from '../layout/DynamicOrnaments.jsx'
import { CheckIcon, ShieldCheckIcon, ClockIcon } from 'lucide-react';

// Animation variants
const fadeInUp = {
    hidden: { opacity: 0, y: 60 },
    visible: {
        opacity: 1,
        y: 0,
        transition: {
            type: "spring",
            damping: 30,
            stiffness: 100
        }
    }
};

const staggerContainer = {
    hidden: { opacity: 0 },
    visible: {
        opacity: 1,
        transition: {
            staggerChildren: 0.2,
            delayChildren: 0.3
        }
    }
};

const fadeInScale = {
    hidden: { opacity: 0, scale: 0.8 },
    visible: {
        opacity: 1,
        scale: 1,
        transition: {
            type: "spring",
            damping: 25,
            stiffness: 100
        }
    }
};

const fadeInLeft = {
    hidden: { opacity: 0, x: -60 },
    visible: {
        opacity: 1,
        x: 0,
        transition: {
            type: "spring",
            damping: 30,
            stiffness: 100
        }
    }
};

const fadeInRight = {
    hidden: { opacity: 0, x: 60 },
    visible: {
        opacity: 1,
        x: 0,
        transition: {
            type: "spring",
            damping: 30,
            stiffness: 100
        }
    }
};

const LandingPage = () => {
    const [isDark, setIsDark] = useState(true);
    const [currencies, setCurrencies] = useState([]);
    const [amount, setAmount] = useState('');
    const [fromCurrency, setFromCurrency] = useState('');
    const [toCurrency, setToCurrency] = useState('');
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [convertedAmount, setConvertedAmount] = useState('');
    const [lastUpdated, setLastUpdated] = useState(new Date());

    useEffect(() => {
        console.log('LandingPage.jsx is rendering...');
        fetchCurrencies();
        const interval = setInterval(fetchCurrencies, 60000);
        return () => clearInterval(interval);
    }, []);

    useEffect(() => {
        if (!loading && currencies.length > 0) {
            calculateConversion();
        }
    }, [amount, fromCurrency, toCurrency, currencies, loading]);

    const fetchCurrencies = async () => {
        try {
            const response = await axios.get('/api/currencies');
            if (response.data?.data) {
                setCurrencies(response.data.data);
                setLastUpdated(new Date()); // Update waktu setiap kali data di-fetch
            }
            setError(null);
        } catch (err) {
            setError('Failed to fetch currency data');
            console.error('Error fetching currencies:', err);
        } finally {
            setLoading(false);
        }
    };

    const calculateConversion = () => {
        // Basic validation
        if (!amount || !fromCurrency || currencies.length === 0) {
            setConvertedAmount('');
            return;
        }

        try {
            // Find the target currency data (using toCurrency, not fromCurrency)
            const targetCurrencyData = currencies.find((c) => c.currency_code === toCurrency);

            if (!targetCurrencyData) {
                setConvertedAmount('');
                return;
            }

            // Simply multiply amount by the target currency's buy rate
            const converted = parseFloat(amount) * targetCurrencyData.buy_rate;

            // Format the result to 2 decimal places
            setConvertedAmount(converted.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));

        } catch (error) {
            console.error("Error during conversion:", error);
            setConvertedAmount('');
        }
    };

    const backgroundClass = isDark
        ? "bg-gradient-to-br from-gray-900 via-purple-900 to-gray-900"
        : "bg-gradient-to-br from-blue-50 via-orange-50 to-blue-50";

    const cardClass = isDark
        ? "bg-gray-800/40 backdrop-blur-md"
        : "bg-white/30 backdrop-blur-md";

    const textClass = isDark ? "text-white" : "text-gray-900";

    if (loading) {
        return (
            <div className={`min-h-screen flex items-center justify-center ${backgroundClass}`}>
                <motion.div
                    className={`${cardClass} p-8 rounded-lg`}
                    initial={{ opacity: 0 }}
                    animate={{ opacity: 1 }}
                >
                    <div className="flex items-center space-x-4">
                        <RefreshCcw className="w-6 h-6 animate-spin text-white" />
                        <p className={textClass}>Loading accessing page...</p>
                    </div>
                </motion.div>
            </div>
        );
    }

    return (
        <div className={`min-h-screen ${backgroundClass} transition-colors duration-500`}>
            <Header isDark={isDark} setIsDark={setIsDark}/>
            {/* Background Elements */}
            <div className="absolute inset-0 overflow-hidden">
                <div className="absolute inset-0 bg-[url('/grid.svg')] opacity-20"></div>
                {[...Array(3)].map((_, i) => (
                    <motion.div
                        key={`blob-${i}`}
                        className="absolute rounded-full mix-blend-multiply filter blur-xl opacity-70"
                        animate={{
                            x: [0, 100, 0],
                            y: [0, -100, 0],
                            scale: [1, 1.2, 1],
                        }}
                        transition={{
                            duration: 10,
                            repeat: Infinity,
                            delay: i * 2,
                        }}
                        style={{
                            width: '500px',
                            height: '500px',
                            left: `${30 * i}%`,
                            top: `${20 * i}%`,
                            backgroundColor: isDark ? '#4c1d95' : '#E25822',
                        }}
                    />
                ))}
            </div>

            <div className="pt-16"> {/* Padding to account for fixed header */}
                <Carousel isDark={isDark}/>
            </div>

            {/* Header */}
            <Header isDark={isDark} setIsDark={setIsDark}/>

            {/* Main Content */}
            <div className="relative">
                {/* Hero Section */}
                <section id="home" className="min-h-screen pt-20 px-4 sm:px-6 lg:px-8">
                    <div className="container mx-auto max-w-7xl">
                        {/* Welcome & Converter Row */}
                        <motion.div
                            className="flex flex-col lg:flex-row gap-4 sm:gap-6 lg:gap-8 mb-4 sm:mb-6 lg:mb-8"
                            variants={staggerContainer}
                            initial="hidden"
                            whileInView="visible"
                            viewport={{once: true, margin: "-100px"}}
                        >
                            <div className="flex flex-col lg:flex-row gap-4 sm:gap-6 lg:gap-8 mb-4 sm:mb-6 lg:mb-8">
                                {/* Welcome Message */}
                                <motion.div
                                    className="w-full lg:w-1/2"
                                    initial={{opacity: 0, y: 20}}
                                    animate={{opacity: 1, y: 0}}
                                    transition={{delay: 0.2}}
                                >
                                    <div className={`${cardClass} rounded-2xl p-4 sm:p-6 lg:p-8 h-full`}>
                                        <motion.div
                                            variants={staggerContainer}
                                            initial="hidden"
                                            animate="visible"
                                        >
                                            <h1 className={`text-3xl sm:text-4xl md:text-5xl font-bold mb-4 sm:mb-6 ${textClass}`}>
                                                Selamat Datang di
                                                <span className="text-orange-500 block sm:inline"> Damar Exchange</span>
                                            </h1>
                                            <p className={`text-lg sm:text-xl md:text-2xl mb-4 sm:mb-6 ${isDark ? 'text-gray-300' : 'text-gray-700'}`}>
                                                Solusi Terpercaya untuk Kebutuhan Valuta Asing Anda
                                            </p>
                                            <div className={`space-y-4 ${isDark ? 'text-gray-300' : 'text-gray-600'}`}>
                                                <div className="flex items-center gap-3">
                                                    <div
                                                        className="w-8 h-8 rounded-full bg-orange-500 flex items-center justify-center">
                                                        <CheckIcon className="w-5 h-5 text-white"/>
                                                    </div>
                                                    <p>Kurs Kompetitif</p>
                                                </div>
                                                <div className="flex items-center gap-3">
                                                    <div
                                                        className="w-8 h-8 rounded-full bg-orange-500 flex items-center justify-center">
                                                        <ShieldCheckIcon className="w-5 h-5 text-white"/>
                                                    </div>
                                                    <p>Terpercaya & Berizin Resmi</p>
                                                </div>
                                                <div className="flex items-center gap-3">
                                                    <div
                                                        className="w-8 h-8 rounded-full bg-orange-500 flex items-center justify-center">
                                                        <ClockIcon className="w-5 h-5 text-white"/>
                                                    </div>
                                                    <p>Layanan Cepat & Professional</p>
                                                </div>
                                            </div>
                                        </motion.div>
                                    </div>
                                </motion.div>

                                {/* Currency Converter Card */}
                                <motion.div
                                    className="w-full lg:w-1/2"
                                    variants={fadeInRight}
                                    initial={{opacity: 0, y: 20}}
                                    animate={{opacity: 1, y: 0}}
                                    transition={{delay: 0.4}}
                                >
                                    <div className={`${cardClass} rounded-2xl p-4 sm:p-6 lg:p-8`}>
                                        <h2 className={`text-xl sm:text-2xl font-bold mb-4 sm:mb-6 ${textClass}`}>Kalkulator
                                            Kurs</h2>
                                        <div className="space-y-4">
                                            <div className="flex flex-col sm:flex-row gap-2 sm:gap-4">
                                                <input
                                                    type="number"
                                                    value={amount}
                                                    onChange={(e) => setAmount(e.target.value)}
                                                    className="w-full p-2 sm:p-3 rounded-lg bg-white/10 border border-gray-700/50 text-white placeholder-gray-400"
                                                    placeholder="Amount"
                                                />
                                                <select
                                                    value={fromCurrency}
                                                    onChange={(e) => setFromCurrency(e.target.value)}
                                                    className={`w-full sm:w-32 p-2 sm:p-3 rounded-lg ${
                                                        isDark
                                                            ? 'bg-gray-700/50 border-gray-600 text-white hover:bg-gray-600/50'
                                                            : 'bg-white/50 border-gray-300 text-gray-800 hover:bg-white/70'
                                                    } border transition-colors duration-200`}
                                                >
                                                    <option value="" disabled>Select</option>
                                                    {currencies.map((curr) => (
                                                        <option key={curr.currency_code} value={curr.currency_code}>
                                                            {curr.currency_code}
                                                        </option>
                                                    ))}
                                                </select>
                                            </div>
                                            <div className="flex gap-4">
                                                <input
                                                    type="text"
                                                    value={convertedAmount}
                                                    readOnly
                                                    className="w-full p-3 rounded-lg bg-white/10 border border-gray-700/50 text-white"
                                                    placeholder="Converted Amount"
                                                />
                                                <select
                                                    value={toCurrency}
                                                    onChange={(e) => setToCurrency(e.target.value)}
                                                    className={`w-32 p-3 rounded-lg ${
                                                        isDark
                                                            ? 'bg-gray-700/50 border-gray-600 text-white hover:bg-gray-600/50'
                                                            : 'bg-white/50 border-gray-300 text-gray-800 hover:bg-white/70'
                                                    } border transition-colors duration-200`}
                                                >
                                                    <option value="" disabled>Select</option>
                                                    {currencies.map((curr) => (
                                                        <option key={curr.currency_code} value={curr.currency_code}>
                                                            {curr.currency_code}
                                                        </option>
                                                    ))}
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </motion.div>
                            </div>
                        </motion.div>

                        {/* Exchange Rates Row */}
                        <motion.div
                            className="w-full mt-4 sm:mt-6 lg:mt-8"
                            variants={fadeInUp}
                            initial="hidden"
                            whileInView="visible"
                            viewport={{once: true, margin: "-100px"}}
                        >
                            <div className={`${cardClass} rounded-2xl p-4 sm:p-6 lg:p-8`}>
                                <div
                                    className="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 sm:mb-6">
                                    <h2 className={`text-xl sm:text-2xl font-bold ${textClass}`}>Kurs Mata Uang Hari
                                        Ini</h2>
                                    <motion.button
                                        whileHover={{scale: 1.1}}
                                        whileTap={{scale: 0.9}}
                                        className="mt-2 sm:mt-0 p-2 rounded-full bg-white/10"
                                        onClick={fetchCurrencies}
                                    >
                                        <RefreshCcw className={`w-5 h-5 ${textClass}`}/>
                                    </motion.button>
                                </div>
                                <div className="overflow-x-auto -mx-4 sm:mx-0">
                                    <table className="w-full min-w-[640px]">
                                        <thead>
                                        <tr className={`text-xs sm:text-sm ${isDark ? 'text-gray-400' : 'text-gray-600'}`}>
                                            <th className="text-left py-3">Currency</th>
                                            <th className="text-right py-3">We Buy</th>
                                            <th className="text-right py-3">We Sell</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        {currencies.map((currency) => (
                                            <motion.tr
                                                key={currency.currency_code}
                                                variants={fadeInUp}
                                                initial={{opacity: 0, y: 20}}
                                                animate={{opacity: 1, y: 0}}
                                                className={`border-t ${isDark ? 'border-gray-700' : 'border-gray-200'}`}
                                            >
                                                <td className={`py-4 ${textClass} font-medium`}>
                                                    {currency.currency_code}
                                                </td>
                                                <td className={`text-right ${textClass}`}>
                                                    {currency.buy_rate?.toLocaleString()}
                                                </td>
                                                <td className={`text-right ${textClass}`}>
                                                    {currency.sell_rate?.toLocaleString()}
                                                </td>
                                            </motion.tr>
                                        ))}
                                        </tbody>
                                    </table>
                                </div>
                                <div className="text-center mt-4">
                                    <p className={`text-xs sm:text-sm ${isDark ? 'text-gray-400' : 'text-gray-600'}`}>
                                        Last updated: {lastUpdated.toLocaleTimeString('id-ID', {
                                        hour: '2-digit',
                                        minute: '2-digit',
                                        second: '2-digit',
                                        hour12: false
                                    })}
                                    </p>
                                </div>
                            </div>
                        </motion.div>
                    </div>
                </section>

                {/*
                ORNAMEN ANIMASI
                */}
                <DynamicOrnaments isDark={isDark}/>


                {/* Company Profile Section */}
                <div id="company-profile">
                    <CompanyProfile isDark={isDark}/>
                </div>

                {/*
                ORNAMEN ANIMASI
                */}
                <DynamicOrnaments isDark={isDark}/>

                {/* Location Section */}
                <div id="location">
                    <Location isDark={isDark}/>
                </div>

                {/* Footer Section */}
                <div id="footer">
                    <Footer isDark={isDark}/>
                </div>
            </div>
        </div>
    );
};

export default LandingPage;
