<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rekam Medis - {{ $medicalRecord->pet->name ?? 'Hewan Peliharaan' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #2563eb;
            font-size: 24pt;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .header p {
            color: #666;
            font-size: 10pt;
        }
        
        .record-info {
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            font-weight: bold;
            width: 30%;
            padding: 5px 10px 5px 0;
            color: #1f2937;
        }
        
        .info-value {
            display: table-cell;
            padding: 5px 0;
            color: #4b5563;
        }
        
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        
        .section-title {
            background-color: #2563eb;
            color: white;
            padding: 10px 15px;
            font-size: 13pt;
            font-weight: bold;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        
        .section-content {
            padding: 0 10px;
            text-align: justify;
        }
        
        .diagnoses-list, .medications-list {
            margin-top: 10px;
        }
        
        .diagnosis-item, .medication-item {
            background-color: #f9fafb;
            border-left: 4px solid #2563eb;
            padding: 12px 15px;
            margin-bottom: 10px;
            border-radius: 0 5px 5px 0;
        }
        
        .item-header {
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 5px;
            font-size: 12pt;
        }
        
        .item-detail {
            color: #6b7280;
            margin-left: 0;
            font-size: 10pt;
            line-height: 1.5;
        }
        
        .medication-dosage {
            display: inline-block;
            background-color: #dbeafe;
            color: #1e40af;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 9pt;
            margin-right: 5px;
            font-weight: 600;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
        }
        
        .signature-section {
            margin-top: 50px;
            text-align: right;
            padding-right: 50px;
        }
        
        .signature-line {
            display: inline-block;
            text-align: center;
        }
        
        .signature-space {
            height: 60px;
            margin-bottom: 5px;
        }
        
        .signature-name {
            border-top: 1px solid #333;
            padding-top: 5px;
            min-width: 200px;
            font-weight: bold;
        }
        
        .print-date {
            color: #6b7280;
            font-size: 9pt;
            margin-top: 20px;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 9pt;
            font-weight: 600;
        }
        
        .badge-success {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .badge-info {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .no-data {
            color: #9ca3af;
            font-style: italic;
            padding: 10px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>🏥 VETCARE</h1>
        <p>Sistem Manajemen Klinik Hewan</p>
        <p style="margin-top: 5px; font-weight: bold;">REKAM MEDIS HEWAN PELIHARAAN</p>
    </div>

    <!-- Medical Record Information -->
    <div class="record-info">
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">No. Rekam Medis:</div>
                <div class="info-value">#MR-{{ str_pad($medicalRecord->id, 6, '0', STR_PAD_LEFT) }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tanggal Pemeriksaan:</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($medicalRecord->record_date)->format('d F Y, H:i') }} WIB</div>
            </div>
        </div>
    </div>

    <!-- Pet Information -->
    <div class="section">
        <div class="section-title">📋 Informasi Hewan Peliharaan</div>
        <div class="section-content">
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Nama Hewan:</div>
                    <div class="info-value">{{ $medicalRecord->pet->name ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Jenis:</div>
                    <div class="info-value">{{ ucfirst($medicalRecord->pet->species ?? '-') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Ras:</div>
                    <div class="info-value">{{ $medicalRecord->pet->breed ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Usia:</div>
                    <div class="info-value">{{ $medicalRecord->pet->age ?? '-' }} tahun</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Berat Badan:</div>
                    <div class="info-value">{{ $medicalRecord->pet->weight ?? '-' }} kg</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Pemilik:</div>
                    <div class="info-value">{{ $medicalRecord->pet->customer->name ?? ($medicalRecord->pet->user->name ?? '-') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Doctor Information -->
    <div class="section">
        <div class="section-title">👨‍⚕️ Dokter Pemeriksa</div>
        <div class="section-content">
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Nama Dokter:</div>
                    <div class="info-value">{{ $medicalRecord->doctor->name ?? '-' }}</div>
                </div>
                @if(isset($medicalRecord->doctor->specialization))
                <div class="info-row">
                    <div class="info-label">Spesialisasi:</div>
                    <div class="info-value">{{ $medicalRecord->doctor->specialization }}</div>
                </div>
                @endif
                @if(isset($medicalRecord->doctor->license_number))
                <div class="info-row">
                    <div class="info-label">No. Lisensi:</div>
                    <div class="info-value">{{ $medicalRecord->doctor->license_number }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Symptoms -->
    @if($medicalRecord->symptoms)
    <div class="section">
        <div class="section-title">🩺 Gejala / Keluhan</div>
        <div class="section-content">
            <p>{{ $medicalRecord->symptoms }}</p>
        </div>
    </div>
    @endif

    <!-- Diagnosis -->
    <div class="section">
        <div class="section-title">🔬 Diagnosis</div>
        <div class="section-content">
            @if($medicalRecord->diagnosis)
                <p style="font-weight: bold; color: #1f2937; margin-bottom: 10px;">{{ $medicalRecord->diagnosis }}</p>
            @endif
            
            @if($medicalRecord->diagnoses && $medicalRecord->diagnoses->count() > 0)
                <div class="diagnoses-list">
                    @foreach($medicalRecord->diagnoses as $diagnosis)
                        <div class="diagnosis-item">
                            <div class="item-header">{{ $diagnosis->diagnosis_name }}</div>
                            @if($diagnosis->description)
                                <div class="item-detail">{{ $diagnosis->description }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                @if(!$medicalRecord->diagnosis)
                    <p class="no-data">Tidak ada diagnosis tercatat</p>
                @endif
            @endif
        </div>
    </div>

    <!-- Treatment -->
    @if($medicalRecord->treatment)
    <div class="section">
        <div class="section-title">💊 Perawatan & Tindakan</div>
        <div class="section-content">
            <p>{{ $medicalRecord->treatment }}</p>
        </div>
    </div>
    @endif

    <!-- Medications -->
    @if($medicalRecord->medications && $medicalRecord->medications->count() > 0)
    <div class="section">
        <div class="section-title">💊 Resep Obat</div>
        <div class="section-content">
            <div class="medications-list">
                @foreach($medicalRecord->medications as $medication)
                    <div class="medication-item">
                        <div class="item-header">{{ $medication->medicine_name }}</div>
                        <div class="item-detail">
                            @if($medication->dosage)
                                <span class="medication-dosage">{{ $medication->dosage }}</span>
                            @endif
                            @if($medication->frequency)
                                <span class="medication-dosage">{{ $medication->frequency }}</span>
                            @endif
                            @if($medication->duration)
                                <span class="medication-dosage">Durasi: {{ $medication->duration }}</span>
                            @endif
                            @if($medication->notes)
                                <br><span>{{ $medication->notes }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Additional Notes -->
    @if($medicalRecord->notes)
    <div class="section">
        <div class="section-title">📝 Catatan Tambahan</div>
        <div class="section-content">
            <p>{{ $medicalRecord->notes }}</p>
        </div>
    </div>
    @endif

    <!-- Signature -->
    <div class="signature-section">
        <div class="signature-line">
            <p style="margin-bottom: 5px;">Dokter Pemeriksa,</p>
            <div class="signature-space"></div>
            <div class="signature-name">{{ $medicalRecord->doctor->name ?? '-' }}</div>
            @if(isset($medicalRecord->doctor->license_number))
                <p style="font-size: 9pt; color: #6b7280; margin-top: 3px;">{{ $medicalRecord->doctor->license_number }}</p>
            @endif
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="print-date">
            Dokumen dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y, H:i:s') }} WIB
        </div>
        <p style="font-size: 9pt; color: #9ca3af; margin-top: 10px;">
            Dokumen ini adalah bukti resmi rekam medis dari VetCare Clinic
        </p>
    </div>
</body>
</html>
