import './bootstrap';

import '@fontsource/manrope/400.css';
import '@fontsource/manrope/500.css';
import '@fontsource/manrope/600.css';
import '@fontsource/manrope/700.css';
import '@fontsource/nunito/400.css';
import '@fontsource/nunito/500.css';
import '@fontsource/nunito/600.css';
import '@fontsource/nunito/700.css';
import '@fontsource/nunito/800.css';
import '@fontsource/nunito/900.css';
import '@flaticon/flaticon-uicons/css/all/all.css';
import DataTable from "datatables.net-dt";
import AOS from 'aos';

// Buttons extension + theme styling
import 'datatables.net-buttons';
import 'datatables.net-buttons-dt/css/buttons.dataTables.css';

// Core libraries always needed
window.DataTable = DataTable;

// Lazy load heavy libraries only when needed
window.loadPDFLibrary = async () => {
    if (!window.pdfjsLib) {
        const pdfjsLib = await import('pdfjs-dist');
        window.pdfjsLib = pdfjsLib;
    }
    return window.pdfjsLib;
};

window.loadChartLibrary = async () => {
    if (!window.Chart) {
        const Chart = (await import('chart.js/auto')).default;
        window.Chart = Chart;
        return Chart;
    }
    return window.Chart;
};

// Initialize AOS
AOS.init();
window.AOS = AOS;

// Wait for DOM to be ready before accessing window.Laravel
document.addEventListener('DOMContentLoaded', function() {
    // Echo listener moved to admin layout for better control
});
