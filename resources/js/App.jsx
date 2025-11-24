import React from 'react';
import { Routes, Route } from 'react-router-dom';
import Login from './components/auth/Login';
import AdminDashboard from "./components/admin/AdminDashboard.jsx";
import LandingPage from './components/pages/LandingPage.jsx';

const App = () => {
    return (
        <Routes>
            <Route path="/login" element={<Login />} />
            <Route path="/dashboard" element={<AdminDashboard />} />
            <Route path="/" element={<LandingPage />} />
        </Routes>
    );
};

export default App;
