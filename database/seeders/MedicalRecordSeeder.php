<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MedicalRecord;
use App\Models\Pet;
use App\Models\Doctor;
use App\Models\Appointment;
use Carbon\Carbon;

class MedicalRecordSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $pets = Pet::take(12)->get();
        $doctors = Doctor::all();

        // Realistic medical record templates
        $medicalRecordTemplates = [
            [
                'symptoms' => 'Muntah-muntah, lesu, tidak mau makan sejak 2 hari yang lalu',
                'diagnosis' => 'Gastroenteritis akut',
                'treatment' => 'Pemberian cairan infus, antiemetik (metoclopramide), dan antasida. Diet lunak selama 3 hari',
                'notes' => 'Pasien menunjukkan tanda-tanda dehidrasi ringan. Direkomendasikan rawat jalan dengan monitoring ketat. Follow-up dalam 3 hari untuk evaluasi kondisi',
            ],
            [
                'symptoms' => 'Gatal-gatal di kulit, kemerahan, dan rambut rontok di area punggung',
                'diagnosis' => 'Dermatitis alergi (flea allergy dermatitis)',
                'treatment' => 'Pemberian antihistamin, salep kortikosteroid topikal, dan obat anti-kutu. Shampoo khusus kulit sensitif',
                'notes' => 'Ditemukan bekas gigitan kutu. Pemilik disarankan untuk membersihkan lingkungan rumah dan memberikan obat anti-kutu rutin setiap bulan',
            ],
            [
                'symptoms' => 'Batuk kering, bersin-bersin, keluar cairan dari hidung',
                'diagnosis' => 'Infeksi saluran pernapasan atas (ISPA)',
                'treatment' => 'Antibiotik (amoxicillin), vitamin C, dan ekspektoran. Istirahat total di tempat hangat',
                'notes' => 'Kemungkinan penularan dari hewan lain. Isolasi sementara dari hewan peliharaan lain selama masa pengobatan 7 hari',
            ],
            [
                'symptoms' => 'Pincang pada kaki belakang kiri, terlihat kesakitan saat berjalan',
                'diagnosis' => 'Sprain (keseleo) pada sendi lutut',
                'treatment' => 'Pemberian anti-inflamasi (carprofen), kompres dingin, dan istirahat. Hindari aktivitas berat selama 2 minggu',
                'notes' => 'Kemungkinan cedera saat bermain. Jika tidak ada perbaikan dalam 1 minggu, akan dilakukan pemeriksaan X-ray untuk memastikan tidak ada fraktur',
            ],
            [
                'symptoms' => 'Diare berdarah, dehidrasi, demam tinggi',
                'diagnosis' => 'Parvo viral infection (suspected)',
                'treatment' => 'Rawat inap, terapi cairan intensif, antibiotik spektrum luas, antiemetik. Isolasi ketat',
                'notes' => 'Kondisi kritis. Memerlukan perawatan intensif 24 jam. Prognosis guarded. Pemilik telah diberikan penjelasan mengenai kondisi dan risiko',
            ],
            [
                'symptoms' => 'Tidak ada keluhan, pemeriksaan rutin tahunan',
                'diagnosis' => 'Sehat, kondisi baik',
                'treatment' => 'Vaksinasi booster (rabies dan DHPP), pemberian vitamin multivitamin, pemeriksaan gigi dan bersihkan karang gigi',
                'notes' => 'Berat badan ideal. Gigi dalam kondisi baik setelah pembersihan. Disarankan kontrol rutin 6 bulan sekali. Vaksinasi selanjutnya tahun depan',
            ],
            [
                'symptoms' => 'Mata berair, merah, sering menggaruk area mata',
                'diagnosis' => 'Konjungtivitis (radang selaput mata)',
                'treatment' => 'Salep mata antibiotik (chloramphenicol), tetes mata, dan e-collar untuk mencegah garukan',
                'notes' => 'Kemungkinan iritasi dari debu atau benda asing. Bersihkan area mata 3x sehari dengan air hangat. Follow-up 5 hari',
            ],
            [
                'symptoms' => 'Telinga berbau tidak sedap, sering menggeleng-gelengkan kepala',
                'diagnosis' => 'Otitis externa (infeksi telinga luar)',
                'treatment' => 'Pembersihan telinga, tetes telinga antibiotik-antifungal, dan anti-inflamasi oral',
                'notes' => 'Ditemukan kotoran berlebih dan jamur di telinga. Pemilik diajarkan cara membersihkan telinga yang benar. Hindari air masuk ke telinga saat mandi',
            ],
            [
                'symptoms' => 'Nafsu makan menurun, berat badan turun drastis dalam 2 minggu',
                'diagnosis' => 'Diabetes mellitus (suspected)',
                'treatment' => 'Tes darah lengkap, tes glukosa, mulai terapi insulin jika dikonfirmasi. Diet khusus diabetes',
                'notes' => 'Menunggu hasil lab. Pemilik diedukasi tentang manajemen diabetes pada hewan. Kemungkinan memerlukan terapi insulin seumur hidup',
            ],
            [
                'symptoms' => 'Benjolan di area perut, teraba keras saat palpasi',
                'diagnosis' => 'Mammary tumor (tumor payudara)',
                'treatment' => 'Rujukan untuk biopsi dan kemungkinan pembedahan. Diberikan pain relief sementara',
                'notes' => 'Ukuran tumor 2x3 cm. Disarankan untuk dilakukan operasi pengangkatan dan pemeriksaan histopatologi. Diskusi dengan customer mengenai prosedur dan biaya',
            ],
            [
                'symptoms' => 'Kejang-kejang selama 2 menit, tidak sadarkan diri setelahnya',
                'diagnosis' => 'Epilepsi (seizure disorder)',
                'treatment' => 'Anti-konvulsan (phenobarbital), monitoring ketat. Instruksi pertolongan pertama saat kejang',
                'notes' => 'Kejang pertama kali. Dilakukan pemeriksaan neurologis. Pemilik diajarkan cara menangani saat kejang terjadi. Follow-up untuk evaluasi respons terhadap obat',
            ],
            [
                'symptoms' => 'Sulit buang air kecil, terlihat kesakitan saat berkemih',
                'diagnosis' => 'Urinary tract infection (UTI)',
                'treatment' => 'Antibiotik (enrofloxacin), pain relief, dan peningkatan asupan air. Urinalisis',
                'notes' => 'Hasil urinalisis menunjukkan bakteri dan kristal. Diet khusus untuk kesehatan saluran kemih. Pastikan air minum selalu tersedia. Kontrol 10 hari untuk cek urin ulang',
            ],
        ];

        $index = 0;
        foreach ($pets as $pet) {
            $doctor = $doctors->random();
            
            // Select medical record template (cycle through templates)
            $template = $medicalRecordTemplates[$index % count($medicalRecordTemplates)];
            $index++;

            $date = Carbon::now()->subDays(rand(1, 120));

            // Find existing appointment for this pet or create one
            $appointment = Appointment::where('pet_id', $pet->id)->first();
            if (!$appointment) {
                $appointment = Appointment::create([
                    'user_id' => $pet->user_id ?? null,
                    'pet_id' => $pet->id,
                    'doctor_id' => $doctor->id,
                    'appointment_time' => $date,
                    'status' => 'completed',
                ]);
            }

            // Create detailed medical record
            MedicalRecord::firstOrCreate([
                'appointment_id' => $appointment->id,
                'pet_id' => $pet->id,
                'doctor_id' => $doctor->id,
            ], [
                'record_date' => $date->toDateTimeString(),
                'symptoms' => $template['symptoms'],
                'diagnosis' => $template['diagnosis'],
                'treatment' => $template['treatment'],
                'notes' => $template['notes'],
            ]);
        }
    }
}
