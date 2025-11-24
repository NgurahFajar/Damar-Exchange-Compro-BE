import React, { useState } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { useNavigate } from 'react-router-dom';
import { Lock, User, Loader2, ArrowRight, Moon, Sun, Eye, EyeOff } from 'lucide-react';

const Login = () => {
    const [credentials, setCredentials] = useState({
        user: '',
        password: ''
    });
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState('');
    const [isDark, setIsDark] = useState(true);
    const navigate = useNavigate();

    const [showPassword, setShowPassword] = useState(false);

    const handleSubmit = async (e) => {
        e.preventDefault();

        // Reset state
        setLoading(true);
        setError('');

        // Basic validation
        if (!credentials.user.trim() || !credentials.password.trim()) {
            setError('Please enter both your username and password.');
            setLoading(false);
            return;
        }

        try {
            // Send API request
            const response = await axios.post('/api/login', credentials, {
                timeout: 10000, // 10 seconds timeout
            });

            // Extract token
            const token = response.data?.token;
            if (!token) {
                throw new Error('Login failed. Please try again.');
            }

            // Store token and set authorization header
            localStorage.setItem('token', token);
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

            // Navigate to dashboard
            navigate('/dashboard');
        } catch (err) {
            if (err.response) {
                // API returned an error response (4xx, 5xx)
                setError('Unable to log in. Please check your username and password.');
            } else if (err.request) {
                // Request was made but no response received
                setError('Unable to connect. Please check your internet connection and try again.');
            } else {
                // Unknown error
                setError('Something went wrong. Please try again later.');
            }
        } finally {
            setLoading(false);
        }
    };

    const containerVariants = {
        hidden: { opacity: 0, y: 20 },
        visible: {
            opacity: 1,
            y: 0,
            transition: {
                duration: 0.6,
                ease: "easeOut",
                staggerChildren: 0.1
            }
        }
    };

    const itemVariants = {
        hidden: { opacity: 0, x: -20 },
        visible: { opacity: 1, x: 0 }
    };

    const backgroundClass = isDark
        ? "bg-gradient-to-br from-gray-900 via-purple-900 to-gray-900"
        : "bg-gradient-to-br from-blue-50 via-orange-50 to-blue-50";

    const cardClass = isDark
        ? "bg-gray-800/40 backdrop-blur-md"
        : "bg-white/30 backdrop-blur-md";

    const inputClass = isDark
        ? "border-gray-700/50 bg-gray-700/50"
        : "border-gray-200/50 bg-white/50";

    return (
        <div className={`min-h-screen flex items-center justify-center ${backgroundClass} transition-colors duration-500`}>
            {/* Animated background elements */}
            <div className="absolute inset-0 overflow-hidden">
                <div className="absolute inset-0 bg-[url('/grid.svg')] opacity-20"></div>
                {[...Array(3)].map((_, i) => (
                    <motion.div
                        key={i}
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
                            width: '400px',
                            height: '400px',
                            left: `${30 * i}%`,
                            top: `${20 * i}%`,
                            backgroundColor: isDark ? '#4c1d95' : '#E25822',
                        }}
                    />
                ))}
            </div>

            <motion.div
                className={`relative w-full max-w-md p-8 ${cardClass} rounded-2xl shadow-2xl border border-opacity-20 ${isDark ? 'border-white/20' : 'border-gray-200/30'}`}
                variants={containerVariants}
                initial="hidden"
                animate="visible"
            >
                {/* Theme toggle button */}
                <motion.button
                    whileHover={{ scale: 1.1 }}
                    whileTap={{ scale: 0.9 }}
                    className="absolute top-4 right-4 p-2 rounded-full bg-white/10 backdrop-blur-sm"
                    onClick={() => setIsDark(!isDark)}
                >
                    {isDark ? (
                        <Sun className="w-5 h-5 text-yellow-400" />
                    ) : (
                        <Moon className="w-5 h-5 text-gray-600" />
                    )}
                </motion.button>

                <motion.div className="text-center space-y-4" variants={itemVariants}>
                    <motion.div
                        className="mx-auto h-20 w-20 rounded-full bg-gradient-to-r from-purple-500 to-orange-500 flex items-center justify-center"
                        whileHover={{ scale: 1.05, rotate: 5 }}
                        whileTap={{ scale: 0.95 }}
                    >
                        <Lock className="h-10 w-10 text-white" />
                    </motion.div>

                    <motion.h2
                        className={`text-3xl font-bold ${isDark ? 'text-white' : 'text-gray-900'}`}
                        variants={itemVariants}
                    >
                        Damar Exchange
                    </motion.h2>

                    <motion.p
                        className={`text-sm ${isDark ? 'text-gray-300' : 'text-gray-700'}`}
                        variants={itemVariants}
                    >
                        Login to access Admin Dashboard
                    </motion.p>
                </motion.div>

                <AnimatePresence>
                    {error && (
                        <motion.div
                            initial={{ opacity: 0, y: -20 }}
                            animate={{ opacity: 1, y: 0 }}
                            exit={{ opacity: 0, y: -20 }}
                            className="mt-4 p-4 rounded-lg bg-red-500/10 border border-red-500/20"
                        >
                            <p className="text-sm text-red-400">{error}</p>
                        </motion.div>
                    )}
                </AnimatePresence>

                <form onSubmit={handleSubmit} className="mt-8 space-y-6">
                    <motion.div className="space-y-4" variants={itemVariants}>
                        <div className="relative">
                            <User
                                className={`absolute left-3 top-3 h-5 w-5 ${isDark ? 'text-gray-400' : 'text-gray-500'}`}/>
                            <motion.input
                                whileFocus={{scale: 1.02}}
                                type="text"
                                required
                                className={`pl-10 block w-full rounded-lg ${inputClass} px-3 py-3 ${isDark ? 'text-white' : 'text-gray-900'} placeholder-gray-400 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200`}
                                placeholder="Username"
                                value={credentials.user}
                                onChange={(e) => setCredentials({...credentials, user: e.target.value})}
                            />
                        </div>

                        <div className="relative">
                            <Lock
                                className={`absolute left-3 top-3 h-5 w-5 ${isDark ? 'text-gray-400' : 'text-gray-500'}`}/>
                            <motion.input
                                whileFocus={{scale: 1.02}}
                                type={showPassword ? 'text' : 'password'}
                                required
                                className={`pl-10 pr-10 block w-full rounded-lg ${inputClass} px-3 py-3 ${isDark ? 'text-white' : 'text-gray-900'} placeholder-gray-400 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200`}
                                placeholder="Password"
                                value={credentials.password}
                                onChange={(e) => setCredentials({...credentials, password: e.target.value})}
                            />
                            <button
                                type="button"
                                onClick={() => setShowPassword(!showPassword)}
                                className="absolute right-3 top-3 text-gray-500 hover:text-gray-700 transition"
                            >
                                {showPassword ? (
                                    <EyeOff className="h-5 w-5"/>
                                ) : (
                                    <Eye className="h-5 w-5"/>
                                )}
                            </button>
                        </div>

                    </motion.div>

                    <motion.div variants={itemVariants} className="flex items-center justify-between">
                        <div className="flex items-center">
                            <input
                                id="remember-me"
                                type="checkbox"
                                className="h-4 w-4 rounded border-gray-300 text-orange-600 focus:ring-orange-500"
                            />
                            <label htmlFor="remember-me"
                                   className={`ml-2 block text-sm ${isDark ? 'text-gray-300' : 'text-gray-700'}`}>
                                Remember me
                            </label>
                        </div>


                    </motion.div>

                    <motion.button
                        variants={itemVariants}
                        whileHover={{ scale: 1.02 }}
                        whileTap={{ scale: 0.98 }}
                        type="submit"
                        disabled={loading}
                        className={`group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white
              ${loading
                            ? 'bg-orange-500/80 cursor-not-allowed'
                            : 'bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700'
                        } transition-all duration-200`}
                    >
                        {loading ? (
                            <Loader2 className="h-5 w-5 animate-spin" />
                        ) : (
                            <span className="flex items-center">
                Log-in
                <ArrowRight className="ml-2 h-4 w-4 group-hover:translate-x-1 transition-transform" />
              </span>
                        )}
                    </motion.button>


                </form>
            </motion.div>
        </div>
    );
};

export default Login;
