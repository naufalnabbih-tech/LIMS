<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Analysis Template - PT TIMAH INDUSTRI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @php
        use Illuminate\Support\Facades\Storage;
    @endphp
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

<body class="bg-gray-100 flex justify-center text-gray-900">

        <div
            class="page-container bg-white w-[210mm] min-h-[297mm] p-[10mm] shadow-xl relative text-sm leading-tight flex flex-col print:shadow-none print:min-h-0 print:justify-between">

            <!-- Header -->
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

            <!-- Title -->
            <div class="text-center mb-4">
                <h1 class="text-xl font-bold underline decoration-2 underline-offset-4">CERTIFICATE OF ANALYSIS</h1>
                <p class="mt-1 font-bold text-sm">No. {{ $documentNumber ?? '[COA NUMBER]' }}</p>
            </div>

            <!-- Document Information -->
            <div class="grid grid-cols-[160px_10px_1fr] gap-y-1 mb-4 text-sm">
                <div class="font-semibold">Brand</div>
                <div>:</div>
                <div class="font-bold">{{ $material ?? '[BRAND/PRODUCT NAME]' }}</div>

                <div class="font-semibold">Lot No</div>
                <div>:</div>
                <div>{{ $batchLot ?? '[LOT NUMBER]' }}</div>

                <div class="font-semibold">Date of Inspection</div>
                <div>:</div>
                <div>{{ $inspectionDate ?? '[INSPECTION DATE]' }}</div>

                <div class="font-semibold">Date of Release</div>
                <div>:</div>
                <div>{{ $releaseDate ?? '[RELEASE DATE]' }}</div>

                <div class="font-semibold">Net Weight</div>
                <div>:</div>
                <div>{{ $netWeight ?? '[WEIGHT]' }}</div>

                <div class="font-semibold">PO No</div>
                <div>:</div>
                <div>{{ $poNo ?? '[PO NUMBER]' }}</div>

                @if (!empty($customFields) && is_array($customFields))
                    @foreach ($customFields as $customField)
                        <div class="font-semibold">{{ $customField['label'] }}</div>
                        <div>:</div>
                        <div>{{ $customField['value'] ?: '-' }}</div>
                    @endforeach
                @endif

                {{-- <div class="font-semibold">PO Date</div>
                <div>:</div>
                <div>[PO DATE]</div>

                <div class="font-semibold">Vessel Name</div>
                <div>:</div>
                <div>[VESSEL NAME]</div> --}}
            </div>

            <!-- Certificate Statement -->
            <p class="mb-2 italic text-gray-700 text-xs">
                The undersigned hereby certifies the following data to be true specification of the obtained results of
                tests and assays.
            </p>

            <!-- Test Results Table -->
            <table class="w-full border-collapse border border-black mb-2 text-[11px]">
                <thead>
                    <tr class="bg-gray-100 text-center font-bold">
                        <th class="border border-black px-2 py-1 w-1/3">TESTS</th>
                        <th class="border border-black px-2 py-1 w-1/3">SPECIFICATION</th>
                        <th class="border border-black px-2 py-1 w-1/3">RESULTS</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @if (!empty($tests) && is_array($tests))
                        @foreach ($tests as $test)
                            <tr>
                                <td class="border border-black px-2 py-1 text-left">{{ $test['name'] ?? '-' }}</td>
                                <td class="border border-black px-2 py-1">{{ $test['spec'] ?? '-' }}</td>
                                <td class="border border-black px-2 py-1">{{ $test['result'] ?? '-' }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="border border-black px-2 py-1 text-center text-gray-500">No test data available</td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <!-- Signature Section -->
            <div class="flex justify-end mt-4 px-10 flex-grow">
                <div class="text-center">
                    <!-- QR Code Signature -->
                    @if ($approverQRSignature)
                        <div class="mb-3 flex justify-center">
                            <img src="{{ Storage::url($approverQRSignature) }}"
                                alt="QR Signature"
                                class="h-32 w-32 border border-gray-400 rounded p-1">
                        </div>
                    @else
                        <div class="h-36"></div>
                    @endif

                    @if ($approver)
                        <p class="font-bold border-b border-black inline-block mb-1">{{ $approver }}</p>
                        <p class="text-sm">{{ $approverRole ?: 'Authorized Signatory' }}</p>
                    @else
                        <p class="font-bold border-b border-black inline-block mb-1">Authorized Signatory</p>
                    @endif
                </div>
            </div>

            <!-- Footer -->
            <div class="w-full text-xs text-black leading-snug mt-4">
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

    </body>

    </html>
