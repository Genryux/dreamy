import './bootstrap';

import '@flaticon/flaticon-uicons/css/all/all.css';
import DataTable from "datatables.net-dt";
import * as pdfjsLib from 'pdfjs-dist';
import Chart from 'chart.js/auto';

// Buttons extension + theme styling
import 'datatables.net-buttons';
import 'datatables.net-buttons-dt/css/buttons.dataTables.css';

window.DataTable = DataTable;
window.pdfjsLib = pdfjsLib;
window.Chart = Chart;
