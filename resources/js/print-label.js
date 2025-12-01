/**
 * Print Sample Label Function
 * Opens a new window with formatted sample label for printing
 */
function printSampleLabel(labelData) {
    // Create a new window for printing
    const printWindow = window.open('', '_blank', 'width=800,height=600');

    // Generate HTML content for the label
    const content = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Sample Label - ${labelData.sample_code}</title>
            <style>
                @media print {
                    body { margin: 0; }
                    .no-print { display: none; }
                }
                body {
                    font-family: Arial, sans-serif;
                    padding: 20px;
                }
                .label-container {
                    width: 100%;
                    max-width: 600px;
                    margin: 0 auto;
                    border: 2px solid #000;
                    padding: 20px;
                }
                .label-header {
                    text-align: center;
                    border-bottom: 2px solid #000;
                    padding-bottom: 15px;
                    margin-bottom: 15px;
                }
                .label-title {
                    font-size: 24px;
                    font-weight: bold;
                    margin-bottom: 5px;
                }
                .sample-code {
                    font-size: 32px;
                    font-weight: bold;
                    margin: 10px 0;
                    letter-spacing: 2px;
                }
                .label-row {
                    display: flex;
                    justify-content: space-between;
                    padding: 8px 0;
                    border-bottom: 1px solid #ddd;
                }
                .label-row:last-child {
                    border-bottom: none;
                }
                .label-key {
                    font-weight: bold;
                    width: 40%;
                }
                .label-value {
                    width: 60%;
                }
                .print-button {
                    margin: 20px 0;
                    text-align: center;
                }
                .print-button button {
                    padding: 10px 30px;
                    font-size: 16px;
                    background-color: #3b82f6;
                    color: white;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                }
                .print-button button:hover {
                    background-color: #2563eb;
                }
            </style>
        </head>
        <body>
            <div class="print-button no-print">
                <button onclick="window.print(); window.close();">Print Label</button>
            </div>

            <div class="label-container">
                <div class="label-header">
                    <div class="label-title">LABORATORY SAMPLE LABEL</div>
                </div>

                <div class="label-body">
                    <div class="label-row">
                        <div class="label-key">Category:</div>
                        <div class="label-value">${labelData.category}</div>
                    </div>
                    <div class="label-row">
                        <div class="label-key">Material:</div>
                        <div class="label-value">${labelData.material}</div>
                    </div>
                    <div class="label-row">
                        <div class="label-key">Reference:</div>
                        <div class="label-value">${labelData.reference}</div>
                    </div>
                    <div class="label-row">
                        <div class="label-key">Supplier:</div>
                        <div class="label-value">${labelData.supplier}</div>
                    </div>
                    <div class="label-row">
                        <div class="label-key">Batch/Lot:</div>
                        <div class="label-value">${labelData.batch_lot}</div>
                    </div>
                    <div class="label-row">
                        <div class="label-key">Vehicle/Container:</div>
                        <div class="label-value">${labelData.vehicle_container}</div>
                    </div>
                    <div class="label-row">
                        <div class="label-key">Submission Date:</div>
                        <div class="label-value">${labelData.submission_date}</div>
                    </div>
                    <div class="label-row">
                        <div class="label-key">Status:</div>
                        <div class="label-value">${labelData.status}</div>
                    </div>
                    <div class="label-row">
                        <div class="label-key">Submitted By:</div>
                        <div class="label-value">${labelData.submitted_by}</div>
                    </div>
                </div>
            </div>
        </body>
        </html>
    `;

    printWindow.document.write(content);
    printWindow.document.close();
}

// Make function available globally
window.printSampleLabel = printSampleLabel;
