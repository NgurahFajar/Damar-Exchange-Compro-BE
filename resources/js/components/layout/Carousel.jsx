import React, { useState, useEffect } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { ChevronLeft, ChevronRight } from 'lucide-react';
import carousel1 from '../../../../public/images/carousel/carousel 1.png';
import carousel2 from '../../../../public/images/carousel/carousel 2.png';
import carousel3 from '../../../../public/images/carousel/carousel 3.png';

const Carousel = ({ isDark }) => {
    const [currentIndex, setCurrentIndex] = useState(0);
    const [direction, setDirection] = useState(0);
    const images = [carousel1, carousel2, carousel3];

    useEffect(() => {
        const timer = setInterval(() => {
            nextSlide();
        }, 5000);

        return () => clearInterval(timer);
    }, [currentIndex]);

    const nextSlide = () => {
        setDirection(1);
        setCurrentIndex((prevIndex) =>
            prevIndex === images.length - 1 ? 0 : prevIndex + 1
        );
    };

    const prevSlide = () => {
        setDirection(-1);
        setCurrentIndex((prevIndex) =>
            prevIndex === 0 ? images.length - 1 : prevIndex - 1
        );
    };

    const slideVariants = {
        enter: (direction) => ({
            x: direction > 0 ? 300 : -300,
            opacity: 0
        }),
        center: {
            x: 0,
            opacity: 1
        },
        exit: (direction) => ({
            x: direction > 0 ? -300 : 300,
            opacity: 0
        })
    };

    return (
        <div className="relative w-full mt-16">
            <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="relative h-[250px] sm:h-[400px] md:h-[500px] rounded-xl overflow-hidden">
                    {/* Main carousel image */}
                    <div className="relative h-full flex items-center justify-center bg-gradient-to-r from-purple-900/10 via-transparent to-purple-900/10">
                        <AnimatePresence initial={false} custom={direction} mode="wait">
                            <motion.img
                                key={currentIndex}
                                src={images[currentIndex]}
                                custom={direction}
                                variants={slideVariants}
                                initial="enter"
                                animate="center"
                                exit="exit"
                                transition={{
                                    duration: 0.8,
                                    ease: [0.4, 0.0, 0.2, 1]
                                }}
                                className="max-w-full max-h-full object-contain"
                                alt={`Slide ${currentIndex + 1}`}
                            />
                        </AnimatePresence>

                        {/* Navigation Buttons */}
                        <div className="absolute inset-0 flex items-center justify-between px-4">
                            <motion.button
                                whileHover={{ scale: 1.05 }}
                                whileTap={{ scale: 0.95 }}
                                onClick={prevSlide}
                                className={`p-3 rounded-full ${
                                    isDark ? 'bg-gray-800/70' : 'bg-white/70'
                                } backdrop-blur-sm hover:bg-opacity-90 transition-all`}
                            >
                                <ChevronLeft className={`w-12 h-12 ${
                                    isDark ? 'text-white' : 'text-gray-800'
                                } stroke-[3]`} />
                            </motion.button>
                            <motion.button
                                whileHover={{ scale: 1.05 }}
                                whileTap={{ scale: 0.95 }}
                                onClick={nextSlide}
                                className={`p-3 rounded-full ${
                                    isDark ? 'bg-gray-800/70' : 'bg-white/70'
                                } backdrop-blur-sm hover:bg-opacity-90 transition-all`}
                            >
                                <ChevronRight className={`w-12 h-12 ${
                                    isDark ? 'text-white' : 'text-gray-800'
                                } stroke-[3]`} />
                            </motion.button>
                        </div>

                        {/* Indicators */}
                        <div className="absolute bottom-4 left-0 right-0 flex justify-center space-x-3">
                            {images.map((_, index) => (
                                <button
                                    key={index}
                                    onClick={() => setCurrentIndex(index)}
                                    className={`h-2.5 rounded-full transition-all ${
                                        currentIndex === index
                                            ? 'bg-white w-8'
                                            : 'bg-white/50 w-2.5 hover:bg-white/75'
                                    }`}
                                />
                            ))}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Carousel;
