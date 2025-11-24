// components/DynamicOrnaments.jsx
import React from 'react';
import { motion } from 'framer-motion';

const DynamicOrnaments = ({ isDark }) => {
    const currencySymbols = ['$', '€', '¥', '£', 'Rp'];

    return (
        <div className="relative w-full overflow-hidden py-12 sm:py-16 md:py-20">
            <motion.div
                className="absolute left-0 right-0 pointer-events-none"
                initial={{ opacity: 0 }}
                animate={{ opacity: 1 }}
                transition={{ duration: 1.5, ease: "easeOut" }}
            >
                {/* Currency Symbols */}
                {currencySymbols.map((symbol, index) => (
                    <motion.div
                        key={symbol}
                        className="absolute"
                        initial={{ opacity: 0, scale: 0 }}
                        animate={{
                            opacity: [0.1, 0.2, 0.1],
                            scale: [1, 1.2, 1],
                            y: [0, -20, 0],
                            x: [0, 10, 0]
                        }}
                        transition={{
                            duration: 8,
                            repeat: Infinity,
                            repeatType: "reverse",
                            ease: "easeInOut",
                            delay: index * 0.5,
                            times: [0, 0.5, 1]
                        }}
                        style={{
                            left: `${15 + index * 20}%`,
                            top: `${20 + (index % 3) * 15}%`
                        }}
                    >
                        <span className={`text-5xl sm:text-6xl md:text-7xl font-bold ${
                            isDark ? 'text-gray-200/20' : 'text-gray-700/20'
                        }`}>
                            {symbol}
                        </span>
                    </motion.div>
                ))}

                {/* Floating Circles */}
                {[...Array(8)].map((_, i) => (
                    <motion.div
                        key={`circle-${i}`}
                        className={`absolute rounded-full ${
                            isDark ? 'bg-orange-500/10' : 'bg-purple-500/10'
                        }`}
                        style={{
                            width: `${20 + Math.random() * 30}px`,
                            height: `${20 + Math.random() * 30}px`,
                            left: `${10 + i * 12}%`,
                            top: `${30 + (i % 3) * 20}%`
                        }}
                        animate={{
                            y: [0, -30, 0],
                            x: [0, 20, 0],
                            scale: [1, 1.2, 1],
                            opacity: [0.3, 0.6, 0.3],
                        }}
                        transition={{
                            duration: 5 + i * 0.5,
                            repeat: Infinity,
                            repeatType: "reverse",
                            ease: "easeInOut",
                            delay: i * 0.3,
                        }}
                    />
                ))}

                {/* Gradient Orbs */}
                {[...Array(3)].map((_, i) => (
                    <motion.div
                        key={`orb-${i}`}
                        className="absolute rounded-full blur-2xl"
                        style={{
                            width: '150px',
                            height: '150px',
                            background: isDark
                                ? `radial-gradient(circle, rgba(249,115,22,0.1) 0%, rgba(249,115,22,0) 70%)`
                                : `radial-gradient(circle, rgba(147,51,234,0.1) 0%, rgba(147,51,234,0) 70%)`,
                            left: `${20 + i * 30}%`,
                            top: `${10 + (i % 2) * 30}%`
                        }}
                        animate={{
                            scale: [1, 1.2, 1],
                            opacity: [0.3, 0.6, 0.3],
                            x: [0, 20, 0],
                            y: [0, -20, 0]
                        }}
                        transition={{
                            duration: 8,
                            repeat: Infinity,
                            repeatType: "reverse",
                            ease: "easeInOut",
                            delay: i * 0.7,
                        }}
                    />
                ))}

                {/* Dots Grid */}
                <div className="absolute inset-0 grid grid-cols-8 gap-8 opacity-20">
                    {[...Array(24)].map((_, i) => (
                        <motion.div
                            key={`dot-${i}`}
                            className={`w-2 h-2 rounded-full ${
                                isDark ? 'bg-orange-500' : 'bg-purple-500'
                            }`}
                            animate={{
                                scale: [1, 1.5, 1],
                                opacity: [0.3, 0.7, 0.3],
                                y: [0, -10, 0]
                            }}
                            transition={{
                                duration: 3,
                                repeat: Infinity,
                                repeatType: "reverse",
                                ease: "easeInOut",
                                delay: (i % 8) * 0.1,
                            }}
                        />
                    ))}
                </div>
            </motion.div>
        </div>
    );
};

export default DynamicOrnaments;
