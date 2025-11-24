
{/* Old CurrencyManipulation */}
import React, { useState, useEffect } from "react";
import axios from "../../bootstrap.js";
import { motion, AnimatePresence } from "framer-motion";
import { Sun, Moon, Plus, Trash, Edit, List, LogOut } from "lucide-react";
import Swal from "sweetalert2";
import withReactContent from "sweetalert2-react-content";
import { useNavigate } from "react-router-dom";


const MySwal = withReactContent(Swal);

const AdminDashboard = () => {
    const [currencies, setCurrencies] = useState([]);
    const [selectedCurrency, setSelectedCurrency] = useState(null);
    const [newCurrency, setNewCurrency]  = useState({
        currency_code: "",
        currency_name: "",
        buy_rate: "",
        sell_rate: ""
    });

    const [isDark, setIsDark] = useState(true);
    const [activeTab, setActiveTab] = useState("view");
    const navigate = useNavigate();

    useEffect(() => {
        fetchCurrencies();
    }, []);

    // CRUD Operations
    // In AdminDashboard.jsx
    const fetchCurrencies = async () => {
        try {
            const response = await axios.get("/api/currencies");
            setCurrencies(response.data.data);
        } catch (error) {
            MySwal.fire("Error", error.response?.data?.message || "Error fetching currencies", "error");
            console.error("Error fetching currencies:", error);
        }
    };

    const handleAddCurrency = async () => {
        try {
            const response = await axios.post("/api/currencies", {
                currency_code: newCurrency.currency_code.toUpperCase(),
                currency_name: newCurrency.currency_name,
                buy_rate: parseFloat(newCurrency.buy_rate),
                sell_rate: parseFloat(newCurrency.sell_rate)
            });

            setCurrencies([...currencies, response.data.data]);
            setNewCurrency({
                currency_code: "",
                currency_name: "",
                buy_rate: "",
                sell_rate: ""
            });
            setActiveTab("view");
            MySwal.fire("Success", "Currency added successfully", "success");
        } catch (error) {
            const message = error.response?.data?.message || "Error adding currency";
            MySwal.fire("Error", message, "error");
        }
    };

    const handleUpdateCurrency = async () => {
        if (!selectedCurrency) return;

        try {
            const response = await axios.put(
                `/api/currencies/${selectedCurrency.currency_code}`,
                {
                    currency_name: selectedCurrency.currency_name,
                    buy_rate: parseFloat(selectedCurrency.buy_rate),
                    sell_rate: parseFloat(selectedCurrency.sell_rate)
                }
            );

            setCurrencies(prev => prev.map(c =>
                c.currency_code === selectedCurrency.currency_code
                    ? response.data.data
                    : c
            ));
            setSelectedCurrency(null);
            setActiveTab("view");
            MySwal.fire("Success", "Currency updated successfully", "success");
        } catch (error) {
            MySwal.fire("Error", error.response?.data?.message || "Error updating currency", "error");
        }
    };

    const onCurrencySelect = (code) => {
        const currency = currencies.find(c => c.currency_code === code);
        setSelectedCurrency(currency || null);
    };

    const handleDeleteCurrency = async (currencyCode) => {
        const confirmation = await MySwal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this action!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "Cancel",
        });

        if (!confirmation.isConfirmed) return;

        try {
            await axios.delete(`/api/currencies/${currencyCode}`);
            setCurrencies((prev) =>
                prev.filter((c) => c.currency_code !== currencyCode)
            );
            MySwal.fire("Deleted!", "The currency has been deleted.", "success");
        } catch (error) {
            const message = error.response?.data?.message || "Error deleting currency";
            MySwal.fire("Error", message, "error");
        }
    };

    // Theme and Style Classes
    const themeClass = {
        background: isDark
            ? "bg-gradient-to-br from-gray-900 via-purple-900 to-gray-900"
            : "bg-gradient-to-br from-blue-50 via-orange-50 to-blue-50",
        text: isDark ? "text-gray-100" : "text-gray-800",
        subtext: isDark ? "text-gray-300" : "text-gray-600",
        content: isDark
            ? "bg-gray-800/40 backdrop-blur-md"
            : "bg-white/60 backdrop-blur-md",
        input: isDark
            ? "bg-gray-700/50 text-white border-gray-600 placeholder-gray-400"
            : "bg-white/50 text-gray-900 border-gray-200 placeholder-gray-500",
        card: isDark
            ? "bg-gray-700/50 text-white"
            : "bg-white/50 text-gray-900",
        button: {
            primary: isDark
                ? "bg-purple-600 hover:bg-purple-500 text-white"
                : "bg-orange-500 hover:bg-orange-400 text-white",
            secondary: isDark
                ? "bg-gray-700 hover:bg-gray-600 text-white"
                : "bg-white/70 hover:bg-white/90 text-gray-800",
            danger: isDark
                ? "bg-red-500 hover:bg-red-600 text-white"
                : "bg-red-400 hover:bg-red-500 text-white"
        }
    };

    //Logout


// Animation variants
    const containerVariants = {
        hidden: { opacity: 0 },
        visible: {
            opacity: 1,
            transition: {
                duration: 0.5,
                staggerChildren: 0.1
            }
        }
    };

    const itemVariants = {
        hidden: { opacity: 0, x: -20 },
        visible: { opacity: 1, x: 0 }
    };

    const contentVariants = {
        hidden: { opacity: 0, y: 20 },
        visible: {
            opacity: 1,
            y: 0,
            transition: { duration: 0.3 }
        },
        exit: {
            opacity: 0,
            y: -20,
            transition: { duration: 0.2 }
        }
    };

    return (
        <div className={`relative min-h-screen flex ${themeClass.background} transition-colors duration-500 overflow-hidden`}>
            {/* Animated background elements */}
            <div className="absolute inset-0">
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

            {/* Sidebar */}
            <motion.div
                className={`relative w-64 ${themeClass.content} border-r border-white/10 flex flex-col items-center p-6 space-y-4 z-10 ${themeClass.text}`}
                variants={containerVariants}
                initial="hidden"
                animate="visible"
            >
                <motion.h2
                    className="text-2xl font-bold mb-4"
                    variants={itemVariants}
                >
                    <span className={isDark ? 'text-white' : 'text-gray-900'}>
                                    Damar{' '}
                                </span>
                    <span className="text-[#E25822]">
                                    Exchange
                                </span>
                </motion.h2>

                {[
                    {tab: "view", icon: List, label: "View Data"},
                    {tab: "add", icon: Plus, label: "Add Data"},
                    {tab: "edit", icon: Edit, label: "Edit Data"},
                    {tab: "delete", icon: Trash, label: "Delete Data" }
                ].map(({ tab, icon: Icon, label }) => (
                    <motion.button
                        key={tab}
                        onClick={() => setActiveTab(tab)}
                        className={`w-full p-3 rounded-lg transition-colors duration-200 ${
                            activeTab === tab ? themeClass.button.primary : themeClass.button.secondary
                        }`}
                        variants={itemVariants}
                        whileHover={{ scale: 1.05 }}
                        whileTap={{ scale: 0.95 }}
                    >
                        <Icon className="inline mr-2" /> {label}
                    </motion.button>
                ))}
                <div className="mt-auto w-full flex items-center justify-between px-4 pb-4">
                    {/* Logout Button */}
                    <motion.button
                        whileHover={{ scale: 1.1 }}
                        className="p-3 rounded-lg bg-red-500 hover:bg-red-600 text-white flex items-center"
                        onClick={() => {
                            localStorage.removeItem("userToken");
                            navigate("/login");
                        }}
                    >
                        <LogOut className="inline w-5 h-5 mr-2" /> Logout
                    </motion.button>

                    {/* Theme Toggle Button */}
                    <motion.button
                        whileHover={{ scale: 1.1 }}
                        className="p-2 rounded-full bg-white/10 flex items-center justify-center"
                        onClick={() => setIsDark(!isDark)}
                    >
                        {isDark ? (
                            <Sun className="text-yellow-400 w-6 h-6" />
                        ) : (
                            <Moon className="text-gray-600 w-6 h-6" />
                        )}
                    </motion.button>
                </div>
            </motion.div>
            {/* Main Content */}
            <motion.div
                className="relative flex-1 p-8 pt-20 z-10"
                variants={containerVariants}
                initial="hidden"
                animate="visible"
            >
                <motion.h1
                    className={`text-3xl font-bold mb-6 ${themeClass.text}`}
                    variants={itemVariants}
                >
                    Admin Dashboard
                </motion.h1>

                <AnimatePresence mode="wait">
                    <motion.div
                        key={activeTab}
                        variants={contentVariants}
                        initial="hidden"
                        animate="visible"
                        exit="exit"
                        className={`p-6 rounded-xl ${themeClass.content} border border-white/10`}
                    >
                        {activeTab === "view" && (
                            <div className="space-y-4">
                                <h2 className={`text-xl mb-4 ${themeClass.text}`}>View Currencies</h2>
                                <motion.div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    {currencies.map((currency, index) => (
                                        <motion.div
                                            key={currency.currency_code}
                                            initial={{ opacity: 0, y: 20 }}
                                            animate={{ opacity: 1, y: 0 }}
                                            transition={{ delay: index * 0.1 }}
                                            className={`p-4 rounded-lg ${themeClass.card}`}
                                        >
                                            <h3 className="font-bold">
                                                {currency.currency_name} ({currency.currency_code})
                                            </h3>
                                            <div className={`mt-2 space-y-1 ${themeClass.subtext}`}>
                                                <p className="flex justify-between">
                                                    <span>Buy Rate:</span>
                                                    <span>{currency.buy_rate.toLocaleString()} IDR</span>
                                                </p>
                                                <p className="flex justify-between">
                                                    <span>Sell Rate:</span>
                                                    <span>{currency.sell_rate.toLocaleString()} IDR</span>
                                                </p>
                                            </div>
                                        </motion.div>
                                    ))}
                                </motion.div>
                            </div>
                        )}

                        {activeTab === "add" && (
                            <div>
                                <h2 className={`text-xl mb-4 ${themeClass.text}`}>Add New Currency</h2>
                                <motion.div className="space-y-4" variants={containerVariants}>
                                    <motion.input
                                        type="text"
                                        placeholder="Currency Code (e.g., USD)"
                                        value={newCurrency.currency_code.toUpperCase()}
                                        onChange={(e) => setNewCurrency({
                                            ...newCurrency,
                                            currency_code: e.target.value
                                        })}
                                        className={`p-2 rounded-lg block w-full border ${themeClass.input}`}
                                        variants={itemVariants}
                                    />
                                    <motion.input
                                        type="text"
                                        placeholder="Currency Name"
                                        value={newCurrency.currency_name}
                                        onChange={(e) => setNewCurrency({
                                            ...newCurrency,
                                            currency_name: e.target.value
                                        })}
                                        className={`p-2 rounded-lg block w-full border ${themeClass.input}`}
                                        variants={itemVariants}
                                    />
                                    <div className="grid grid-cols-2 gap-4">
                                        <motion.input
                                            type="number"
                                            step="0.01"
                                            placeholder="Buy Rate (IDR)"
                                            value={newCurrency.buy_rate}
                                            onChange={(e) => setNewCurrency({
                                                ...newCurrency,
                                                buy_rate: e.target.value
                                            })}
                                            className={`p-2 rounded-lg block w-full border ${themeClass.input}`}
                                            variants={itemVariants}
                                        />
                                        <motion.input
                                            type="number"
                                            step="0.01"
                                            placeholder="Sell Rate (IDR)"
                                            value={newCurrency.sell_rate}
                                            onChange={(e) => setNewCurrency({
                                                ...newCurrency,
                                                sell_rate: e.target.value
                                            })}
                                            className={`p-2 rounded-lg block w-full border ${themeClass.input}`}
                                            variants={itemVariants}
                                        />
                                    </div>
                                    <motion.button
                                        onClick={handleAddCurrency}
                                        className={`w-full p-2 rounded-lg ${themeClass.button.primary}`}
                                        whileHover={{ scale: 1.02 }}
                                        whileTap={{ scale: 0.98 }}
                                        variants={itemVariants}
                                    >
                                        Save
                                    </motion.button>
                                </motion.div>
                            </div>
                        )}

                        {activeTab === "edit" && (
                            <div>
                                <h2 className={`text-xl mb-4 ${themeClass.text}`}>Edit Currency</h2>
                                <motion.div className="space-y-4" variants={containerVariants}>
                                    {/* Currency Selection */}
                                    <div className="grid gap-4">
                                        <motion.select
                                            value={selectedCurrency?.currency_code || ""}
                                            onChange={(e) => onCurrencySelect(e.target.value)}
                                            className={`p-3 rounded-lg w-full border ${themeClass.input}`}
                                            variants={itemVariants}
                                        >
                                            <option value="">Select Currency to Edit</option>
                                            {currencies.map((currency) => (
                                                <option key={currency.currency_code} value={currency.currency_code}>
                                                    {currency.currency_code} - {currency.currency_name}
                                                </option>
                                            ))}
                                        </motion.select>
                                    </div>

                                    {/* Edit Form */}
                                    {selectedCurrency && (
                                        <motion.div
                                            className="space-y-4 mt-4"
                                            initial={{ opacity: 0, y: 20 }}
                                            animate={{ opacity: 1, y: 0 }}
                                        >
                                            <motion.div className="grid gap-4">
                                                <div>
                                                    <label className={`block text-sm font-medium mb-1 ${themeClass.text}`}>
                                                        Currency Name
                                                    </label>
                                                    <input
                                                        type="text"
                                                        value={selectedCurrency.currency_name}
                                                        onChange={(e) => setSelectedCurrency({
                                                            ...selectedCurrency,
                                                            currency_name: e.target.value
                                                        })}
                                                        className={`p-3 rounded-lg w-full border ${themeClass.input}`}
                                                    />
                                                </div>

                                                <div className="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <label className={`block text-sm font-medium mb-1 ${themeClass.text}`}>
                                                            Buy Rate (IDR)
                                                        </label>
                                                        <input
                                                            type="number"
                                                            step="0.01"
                                                            value={selectedCurrency.buy_rate}
                                                            onChange={(e) => setSelectedCurrency({
                                                                ...selectedCurrency,
                                                                buy_rate: e.target.value
                                                            })}
                                                            className={`p-3 rounded-lg w-full border ${themeClass.input}`}
                                                        />
                                                    </div>

                                                    <div>
                                                        <label className={`block text-sm font-medium mb-1 ${themeClass.text}`}>
                                                            Sell Rate (IDR)
                                                        </label>
                                                        <input
                                                            type="number"
                                                            step="0.01"
                                                            value={selectedCurrency.sell_rate}
                                                            onChange={(e) => setSelectedCurrency({
                                                                ...selectedCurrency,
                                                                sell_rate: e.target.value
                                                            })}
                                                            className={`p-3 rounded-lg w-full border ${themeClass.input}`}
                                                        />
                                                    </div>
                                                </div>
                                            </motion.div>

                                            <motion.button
                                                onClick={handleUpdateCurrency}
                                                className={`w-full p-3 rounded-lg ${themeClass.button.primary}`}
                                                whileHover={{ scale: 1.02 }}
                                                whileTap={{ scale: 0.98 }}
                                            >
                                                Update Currency
                                            </motion.button>
                                        </motion.div>
                                    )}
                                </motion.div>
                            </div>
                        )}

                        {activeTab === "delete" && (
                            <div>
                                <h2 className={`text-xl mb-4 ${themeClass.text}`}>
                                    Delete Currency
                                </h2>
                                <motion.div
                                    className="space-y-2"
                                    variants={containerVariants}
                                    initial="hidden"
                                    animate="visible"
                                >
                                    {currencies.map((currency, index) => (
                                        <motion.div
                                            key={currency.currency_code}
                                            variants={itemVariants}
                                            custom={index}
                                            className={`flex justify-between items-center p-4 rounded-lg ${themeClass.card}`}
                                        >
                                            <span className={themeClass.text}>
                                                {currency.currency_name} ({currency.currency_code})
                                            </span>
                                            <motion.button
                                                onClick={() => handleDeleteCurrency(currency.currency_code)}
                                                className={`p-2 rounded-lg ${themeClass.button.danger}`}
                                                whileHover={{ scale: 1.05 }}
                                                whileTap={{ scale: 0.95 }}
                                            >
                                                <Trash className="w-4 h-4" />
                                            </motion.button>
                                        </motion.div>
                                    ))}
                                </motion.div>
                            </div>
                        )}
                    </motion.div>
                </AnimatePresence>
            </motion.div>
        </div>
    );
};

export default AdminDashboard;
