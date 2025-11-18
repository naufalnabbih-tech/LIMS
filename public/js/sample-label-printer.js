/**
 * Sample Label Printer
 *
 * Global utility function for printing sample labels
 * Used by LIMS Sample Submission module
 */

window.printSampleLabel = function(data) {
    console.log('Printing sample label:', data);

    if (!data || !data.sample_id) {
        console.error('Invalid label data:', data);
        alert('Error: Invalid label data received');
        return;
    }

    const printWindow = window.open('', '_blank', 'width=400,height=600');

    if (!printWindow) {
        alert('Unable to open print window. Please check if pop-ups are blocked.');
        return;
    }

    const labelHtml = `
<!DOCTYPE html>
<html>
<head>
    <title>Sample Label #${data.sample_id}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        .label-container {
            border: 2px solid #000;
            padding: 15px;
            width: 300px;
            margin: 0 auto;
        }
        .label-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .label-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            border-bottom: 1px dotted #ccc;
            padding-bottom: 5px;
        }
        .label-field {
            font-weight: bold;
        }
        .label-value {
            text-align: right;
            max-width: 150px;
            word-break: break-word;
        }
        .sample-id {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            background: #f0f0f0;
            padding: 10px;
            margin: 10px 0;
        }
        .print-button {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin: 10px 0;
            display: block;
            width: 100%;
        }
        .print-button:hover {
            background: #2563eb;
        }
        @media print {
            body { margin: 0; }
            .label-container { border: 2px solid #000; }
            .print-button { display: none; }
        }
    </style>
</head>
<body>
    <div class="label-container">
        <button class="print-button" onclick="window.print()">🖨️ Print This Label</button>
        <div class="label-title">LABORATORY SAMPLE LABEL</div>
        <div class="sample-id">ID: #${data.sample_id}</div>
        <div class="label-row">
            <span class="label-field">Material:</span>
            <span class="label-value">${data.material_name}</span>
        </div>
        <div class="label-row">
            <span class="label-field">Category:</span>
            <span class="label-value">${data.category_name}</span>
        </div>
        <div class="label-row">
            <span class="label-field">Supplier:</span>
            <span class="label-value">${data.supplier}</span>
        </div>
        <div class="label-row">
            <span class="label-field">Batch/Lot:</span>
            <span class="label-value">${data.batch_lot}</span>
        </div>
        <div class="label-row">
            <span class="label-field">Vehicle/Container:</span>
            <span class="label-value">${data.vehicle_container}</span>
        </div>
        <div class="label-row">
            <span class="label-field">Reference:</span>
            <span class="label-value">${data.reference}</span>
        </div>
        <div class="label-row">
            <span class="label-field">Submitted by:</span>
            <span class="label-value">${data.submitted_by}</span>
        </div>
    </div>
</body>
</html>`;

    printWindow.document.open();
    printWindow.document.write(labelHtml);
    printWindow.document.close();
    printWindow.focus();
};
