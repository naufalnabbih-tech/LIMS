<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Analysis - PT TIMAH INDUSTRI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Mengatur ukuran kertas secara eksplisit */
        @page {
            size: A4;
            margin: 0;
            /* Penting: Hapus margin default browser */
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .no-print {
                display: none;
            }

            .page-container {
                width: 210mm;
                /* SOLUSI UTAMA: */
                /* Jangan gunakan 297mm pas, kurangi sedikit (misal 296mm)
                   atau gunakan 100vh untuk menghindari overflow 1px */
                height: 296mm !important;
                margin: 0;
                box-shadow: none;
                /* Mencegah konten pecah ke halaman lain */
                page-break-inside: avoid;
                overflow: hidden;
                /* Sembunyikan overflow jika ada selisih pixel */
            }
        }

        body {
            font-family: 'Times New Roman', Times, serif;
        }
    </style>
</head>

<body class="bg-gray-100  flex justify-center text-gray-900">

    <div
        class="page-container bg-white w-[210mm] min-h-[297mm] p-[10mm] shadow-xl relative text-sm leading-tight flex flex-col print:shadow-none print:min-h-0">

        <div class="flex justify-between items-start border-b-2 border-black pb-2 mb-2">
            <div class="w-1/2">
                <img src="{{ asset('images/logo.png') }}" alt="PT TIMAH INDUSTRI Logo"
                    class="h-12 object-contain">
            </div>
            <div class="w-1/2 text-right text-xs space-y-1">
                <p>Form No: F-LAB-026</p>
                <p>Rev No: 0</p>
            </div>
        </div>

        <div class="text-center mb-4">
            <h1 class="text-xl font-bold underline decoration-2 underline-offset-4">CERTIFICATE OF ANALYSIS</h1>
            <p class="mt-1 font-bold text-sm">No. 388/TI/COA-2103/MT/2025-S0</p>
        </div>

        <div class="grid grid-cols-[160px_10px_1fr] gap-y-1 mb-4 text-sm">
            <div class="font-semibold">Brand</div>
            <div>:</div>
            <div class="font-bold">BANKASTAB IR-181</div>

            <div class="font-semibold">Lot No</div>
            <div>:</div>
            <div>TG2025J06</div>

            <div class="font-semibold">Date of Inspection</div>
            <div>:</div>
            <div>10 October 2025</div>

            <div class="font-semibold">Date of Release</div>
            <div>:</div>
            <div>22 October 2025</div>

            <div class="font-semibold">Net Weight</div>
            <div>:</div>
            <div>19200 Kg</div>

            <div class="font-semibold">PO No</div>
            <div>:</div>
            <div>IR-P-20251016</div>

            <div class="font-semibold">PO Date</div>
            <div>:</div>
            <div>16 October 2025</div>

            <div class="font-semibold">Vessel Name</div>
            <div>:</div>
            <div>GREEN EARTH 021N</div>
        </div>

        <p class="mb-2 italic text-gray-700 text-xs">
            The undersigned hereby certifies the following data to be true specification of the obtained results of
            tests and assays.
        </p>

        <table class="w-full border-collapse border border-black mb-2 text-[11px]">
            <thead>
                <tr class="bg-gray-100 text-center font-bold">
                    <th class="border border-black px-2 py-1 w-1/3">TESTS</th>
                    <th class="border border-black px-2 py-1 w-1/3">SPECIFICATION</th>
                    <th class="border border-black px-2 py-1 w-1/3">RESULTS</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <tr>
                    <td class="border border-black px-2 py-1 text-left">Appearance</td>
                    <td class="border border-black px-2 py-1">CLEAR</td>
                    <td class="border border-black px-2 py-1">CLEAR</td>
                </tr>
                <tr>
                    <td class="border border-black px-2 py-1 text-left">% Tin</td>
                    <td class="border border-black px-2 py-1">19.0 - 19.4</td>
                    <td class="border border-black px-2 py-1">19.2</td>
                </tr>
                <tr>
                    <td class="border border-black px-2 py-1 text-left">Refractive Index (25&deg;C)</td>
                    <td class="border border-black px-2 py-1">1.5070 - 1.5110</td>
                    <td class="border border-black px-2 py-1">1.5083</td>
                </tr>
                <tr>
                    <td class="border border-black px-2 py-1 text-left">Specific Gravity (25&deg;C)</td>
                    <td class="border border-black px-2 py-1">1.165 - 1.185</td>
                    <td class="border border-black px-2 py-1">1.170</td>
                </tr>
                <tr>
                    <td class="border border-black px-2 py-1 text-left">Acid Value</td>
                    <td class="border border-black px-2 py-1">3.0 Max</td>
                    <td class="border border-black px-2 py-1">0.20</td>
                </tr>
                <tr>
                    <td class="border border-black px-2 py-1 text-left">% Sulfur</td>
                    <td class="border border-black px-2 py-1">11.5 - 12.5</td>
                    <td class="border border-black px-2 py-1">12.0</td>
                </tr>
                <tr>
                    <td class="border border-black px-2 py-1 text-left">Gardner Color</td>
                    <td class="border border-black px-2 py-1">&lt; 1</td>
                    <td class="border border-black px-2 py-1">&lt; 1</td>
                </tr>
                <tr>
                    <td class="border border-black px-2 py-1 text-left">Viscosity (cps at 20&deg;C)</td>
                    <td class="border border-black px-2 py-1">&le; 100</td>
                    <td class="border border-black px-2 py-1">51.80</td>
                </tr>
                <tr>
                    <td class="border border-black px-2 py-1 text-left">% Monomethyltin</td>
                    <td class="border border-black px-2 py-1">19.0 - 29.0</td>
                    <td class="border border-black px-2 py-1">22.0</td>
                </tr>
                <tr>
                    <td class="border border-black px-2 py-1 text-left">% Transmittance</td>
                    <td class="border border-black px-2 py-1">&ge; 95</td>
                    <td class="border border-black px-2 py-1">99</td>
                </tr>
                <tr>
                    <td class="border border-black px-2 py-1 text-left">% Trimethyltin</td>
                    <td class="border border-black px-2 py-1">&le; 0.20</td>
                    <td class="border border-black px-2 py-1">n.d</td>
                </tr>
            </tbody>
        </table>

        <div class="flex justify-end mt-4 px-10">
            <div class="text-center">
                <div class="h-36"></div>
                <p class="font-bold border-b border-black inline-block mb-1">SELFIRA ARUM ANDADARI</p>
                <p class="text-sm">Laboratorium Spv</p>
            </div>
        </div>

        <div class="mt-[17%] w-full text-xs text-black leading-snug">
            <div class="border-t border-gray-400 mb-2"></div>

            <div class="flex justify-between items-end">
                <div class="text-left">
                    <p class="font-bold text-sm uppercase">PT TIMAH INDUSTRI</p>
                    <p>Factory & Office</p>
                    <p>Jl. Eropa I Kav. A3 Kawasan Industri Krakatau I</p>
                    <p>Kel. Kotasari, Kec. Grogol, Kota</p>
                    <p>Cilegon, Provinsi Banten 42436</p>
                </div>

                <div class="text-sm text-right whitespace-nowrap pb-[2px]">
                    <span class="font-bold text-red-600">T</span> +62 254 315 000
                    <span class="mx-2 text-gray-400">|</span>
                    <span class="font-bold text-red-600">F</span> +62 254 311 550
                    <span class="mx-2 text-gray-400">|</span>
                    <span class="font-bold text-red-600">W</span> www.timahindustri.com
                </div>
            </div>
        </div>

    </div>

    <script>
        // window.print();
    </script>
</body>

</html>
