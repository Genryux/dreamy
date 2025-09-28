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
import * as pdfjsLib from 'pdfjs-dist';
import Chart from 'chart.js/auto';
import AOS from 'aos';

// Buttons extension + theme styling
import 'datatables.net-buttons';
import 'datatables.net-buttons-dt/css/buttons.dataTables.css';

// Initialize AOS
AOS.init();

window.DataTable = DataTable;
window.pdfjsLib = pdfjsLib;
window.Chart = Chart;
window.AOS = AOS;

// Wait for DOM to be ready before accessing window.Laravel
document.addEventListener('DOMContentLoaded', function() {
    if (
        window.Laravel &&
        window.Laravel.user &&
        window.Laravel.user.roles &&
        ['registrar', 'super_admin'].some(role => window.Laravel.user.roles.includes(role))
    ) {
        window.Echo.channel('admins')
            .listen('.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated', (e) => {
                console.log("Admin real-time notification:", e);
                alert(`${e.title}: ${e.message}`);
            });
    }
});
