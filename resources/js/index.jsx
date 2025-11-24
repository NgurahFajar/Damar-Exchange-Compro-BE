import React from 'react';
import { createRoot } from 'react-dom/client';
import { BrowserRouter } from 'react-router-dom';
import App from './App';

import './bootstrap';
import '../css/app.css';

console.log('Starting React application...'); // Debug: Check if the file is executed

const container = document.getElementById('app');
if (!container) {
    console.error('Container #app not found!'); // Debug: Log error if container is missing
} else {
    console.log('Container found, mounting React...'); // Debug: Confirm container exists

    try {
        const root = createRoot(container);
        root.render(
            <React.StrictMode>
                <BrowserRouter>
                    <App />
                </BrowserRouter>
            </React.StrictMode>
        );
        console.log('React mounted successfully'); // Debug: Confirm React is mounted
    } catch (err) {
        console.error('Error mounting React:', err); // Debug: Catch any render errors
    }
}
