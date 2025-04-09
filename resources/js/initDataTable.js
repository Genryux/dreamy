import DataTable from "datatables.net-dt";

/**
 * Initializes a DataTable.
 *
 * @param {String} selector - The DOM selector for the table container.
 * @param {Object} options - The Tabulator options, including data, columns, etc.
 */
export function initDataTable(selector, options) {
    const table = document.querySelector(selector);
    if (table) {
        new DataTable(table, options);
    } else {
        console.warn(`Element not found: ${selector}`);
    }
}