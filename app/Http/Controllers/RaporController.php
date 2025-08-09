<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;

class RaporController extends Controller
{
    /**
     * Generate report card PDF.
     */
    public function cetak()
    {
        $rapor = [
            'identitas' => [
                'nama_sekolah' => 'SMAS MUHAMMADIYAH',
                'kelas' => 'XI-IPS',
                'alamat' => 'Jl. Merdeka No. 118',
                'semester' => '3 (Ganjil)',
                'nama' => 'MUHAMMAD HAIKAL FIRDAUS',
                'tahun_ajaran' => '2023-2024',
                'nis' => '2223.10.023',
                'nisn' => '0064177255',
            ],
            'sikap' => [
                [
                    'jenis' => 'Spiritual',
                    'deskripsi' => 'Selalu berdoa sebelum dan sesudah melakukan kegiatan; menjalankan ibadah sesuai dengan agamanya dan sikap memberi salam pada saat awal dan akhir kegiatan mulai berkembang',
                    'predikat' => 'Baik',
                ],
                [
                    'jenis' => 'Sosial',
                    'deskripsi' => 'Selalu menunjukkan sikap jujur, tanggung jawab, santun, percaya diri, sedangkan sikap disiplin mengalami peningkatan',
                    'predikat' => 'Baik',
                ],
            ],
            'pengetahuan' => [
                'kelompok_a' => [
                    [
                        'no' => 1,
                        'mapel' => 'Pendidikan Agama dan Budi Pekerti (Hilda Mujakiatul Udzma - NUPTK. )',
                        'kkm' => 75,
                        'nilai' => 77,
                        'predikat' => 'B',
                        'deskripsi' => 'Memiliki kemampuan baik dalam Menganalisis makna Q.S. al-Maidah/5:48; Q.S. an-Nisa/4:59, dan Q.S. at-Taubah/9:105 dan Menganalisis makna Q.S. al-Maidah/5:48; Q.S. an-Nisa/4:59, dan Q.S. at-Taubah/9:105',
                    ],
                    [
                        'no' => 2,
                        'mapel' => 'PPKn (Kokoy Rokoyah, S.p. - NUPTK. 5738758660300032)',
                        'kkm' => 75,
                        'nilai' => 77,
                        'predikat' => 'B',
                        'deskripsi' => 'Memiliki kemampuan baik dalam Menganalisis pelanggaran hak asasi manusia dalam perspektif pancasila dan Menganalisis pelanggaran hak asasi manusia dalam perspektif pancasila',
                    ],
                    [
                        'no' => 3,
                        'mapel' => 'Bahasa Indonesia (Maya, S.Pd. - NUPTK. 0655750652200032)',
                        'kkm' => 75,
                        'nilai' => 77,
                        'predikat' => 'B',
                        'deskripsi' => 'Memiliki kemampuan baik dalam Mengonstruksi informasi berupa pernyataan umum dan tahapan dalam teks prosedur dan Mengonstruksi informasi berupa pernyataan umum dan tahapan dalam teks prosedur',
                    ],
                    [
                        'no' => 4,
                        'mapel' => 'Matematika (Nur Dwi Juliyanti, S.T. - NUPTK. 0046768669130083)',
                        'kkm' => 75,
                        'nilai' => 77,
                        'predikat' => 'B',
                        'deskripsi' => 'Memiliki kemampuan baik dalam Menjelaskan metode pembuktian Pernyataan matematis dan Menjelaskan metode pembuktian Pernyataan matematis',
                    ],
                    [
                        'no' => 5,
                        'mapel' => 'Sejarah Indonesia (Yanuria Sopiah, S.Pd. - NUPTK. 8461763665300022)',
                        'kkm' => 75,
                        'nilai' => 78,
                        'predikat' => 'B',
                        'deskripsi' => 'Memiliki kemampuan baik dalam Menganalisis proses masuk dan perkembangan penjajahan bangsa Eropa di Indonesia dan Menganalisis proses masuk dan perkembangan penjajahan bangsa Eropa di Indonesia',
                    ],
                    [
                        'no' => 6,
                        'mapel' => 'Bahasa Inggris (Yanuria Sopiah, S.Pd. - NUPTK. 8461763665300022)',
                        'kkm' => 75,
                        'nilai' => 77,
                        'predikat' => 'B',
                        'deskripsi' => 'Memiliki kemampuan baik dalam Menerapkan teks interaksi transaksional terkait saran dan tawaran dan Menerapkan teks interaksi transaksional terkait saran dan tawaran',
                    ],
                ],
                'kelompok_b' => [
                    [
                        'no' => 7,
                        'mapel' => 'Seni Budaya (Nurmawaji, S.Pd. - NUPTK. 4836774675130142)',
                        'kkm' => 75,
                        'nilai' => 78,
                        'predikat' => 'B',
                        'deskripsi' => 'Memiliki kemampuan baik dalam Menganalisis konsep, unsur, prinsip, bahan, dan teknik dalam berkarya seni rupa dan Menganalisis konsep, unsur, prinsip, bahan, dan teknik dalam berkarya seni rupa',
                    ],
                    [
                        'no' => 8,
                        'mapel' => 'Penjasorkes (Samsurijal - NUPTK. 3038742644200043)',
                        'kkm' => 75,
                        'nilai' => 78,
                        'predikat' => 'B',
                        'deskripsi' => 'Memiliki kemampuan baik dalam Menganalisis keterampilan gerak salah satu permainan bola besar dan Menganalisis keterampilan gerak salah satu permainan bola besar',
                    ],
                    [
                        'no' => 9,
                        'mapel' => 'Kemuhammadiyahan (ODRI, S.Pd. - NUPTK. 4836774675130142)',
                        'kkm' => 75,
                        'nilai' => 78,
                        'predikat' => 'B',
                        'deskripsi' => 'Memiliki kemampuan baik dalam Memahami periodisasi dinamika perjuangan Muhammadiyah dan Memahami periodisasi dinamika perjuangan Muhammadiyah',
                    ],
                    [
                        'no' => 10,
                        'mapel' => 'Bahasa Arab (M. Haiz Ilmi E. - NUPTK. )',
                        'kkm' => 75,
                        'nilai' => 77,
                        'predikat' => 'B',
                        'deskripsi' => 'Memiliki kemampuan baik dalam Mengemukakan tindak tutur kemampuan pada teks interaksi interpersonal dan Mengemukakan tindak tutur kemampuan pada teks interaksi interpersonal',
                    ],
                    [
                        'no' => 11,
                        'mapel' => 'Teknologi Informasi dan Komunikasi (Nur Dwi Juliyanti, S.T. - NUPTK. 0046768669130083)',
                        'kkm' => 75,
                        'nilai' => 78,
                        'predikat' => 'B',
                        'deskripsi' => 'Memiliki kemampuan baik dalam Memahami cara merakit/memrogram piranti sederhana (embedded system) yang tersedia di pasaran dan Memahami cara merakit/memrogram piranti sederhana (embedded system) yang tersedia di pasaran',
                    ],
                ],
                'peminatan' => [
                    [
                        'no' => 12,
                        'mapel' => 'Geografi (Kokoy Rokoyah, S.p. - NUPTK. 5738758660300032)',
                        'kkm' => 75,
                        'nilai' => 78,
                        'predikat' => 'B',
                        'deskripsi' => 'Memiliki kemampuan baik dalam Memahami kondisi wilayah dan posisi strategis Indonesia sebagai poros maritim dunia dan Memahami kondisi wilayah dan posisi strategis Indonesia sebagai poros maritim dunia',
                    ],
                    [
                        'no' => 13,
                        'mapel' => 'Sejarah (Yanuria Sopiah, S.Pd. - NUPTK. 8461763665300022)',
                        'kkm' => 75,
                        'nilai' => 78,
                        'predikat' => 'B',
                        'deskripsi' => 'Memiliki kemampuan baik dalam Menganalisis kerajaan-kerajaan maritim Indonesia pada masa Hindu dan Buddha dan Menganalisis kerajaan-kerajaan maritim Indonesia pada masa Hindu dan Buddha',
                    ],
                    [
                        'no' => 14,
                        'mapel' => 'Sosiologi (Siti Roayataeni, SE - NUPTK. 3863767668130102)',
                        'kkm' => 75,
                        'nilai' => 78,
                        'predikat' => 'B',
                        'deskripsi' => 'Memiliki kemampuan baik dalam Memahami pengelompokan sosial di masyarakat dan Memahami pengelompokan sosial di masyarakat',
                    ],
                    [
                        'no' => 15,
                        'mapel' => 'Ekonomi (Siti Roayataeni, SE - NUPTK. 3863767668130102)',
                        'kkm' => 75,
                        'nilai' => 78,
                        'predikat' => 'B',
                        'deskripsi' => 'Memiliki kemampuan baik dalam Menganalisis konsep dan metode penghitungan pendapatan nasional dan Menganalisis konsep dan metode penghitungan pendapatan nasional',
                    ],
                ],
            ],
            'total_pengetahuan' => 1164,
            'keterampilan' => [
                'kelompok_a' => [
                    [
                        'no' => 1,
                        'mapel' => 'Pendidikan Agama dan Budi Pekerti',
                        'kkm' => 75,
                        'nilai' => 77,
                        'predikat' => 'B',
                        'deskripsi' => 'Membaca Q.S. al-Maidah/5:48; Q.S. an-Nisa/4:59, dan Q.S. at-Taubah/9:105 dan Membaca Q.S. al-Maidah/5:48; Q.S. an-Nisa/4:59, dan Q.S. at-Taubah/9:105',
                    ],
                    [
                        'no' => 2,
                        'mapel' => 'PPKn',
                        'kkm' => 75,
                        'nilai' => 77,
                        'predikat' => 'B',
                        'deskripsi' => 'Menyaji hasil analisis pelanggaran hak asasi manusia dalam perspektif pancasila dan Menyaji hasil analisis pelanggaran hak asasi manusia dalam perspektif pancasila',
                    ],
                    [
                        'no' => 3,
                        'mapel' => 'Bahasa Indonesia',
                        'kkm' => 75,
                        'nilai' => 77,
                        'predikat' => 'B',
                        'deskripsi' => 'Merancang pernyataan umum dan tahapan-tahapan dalam teks prosedur dan Merancang pernyataan umum dan tahapan-tahapan dalam teks prosedur',
                    ],
                    [
                        'no' => 4,
                        'mapel' => 'Matematika',
                        'kkm' => 75,
                        'nilai' => 77,
                        'predikat' => 'B',
                        'deskripsi' => 'Menggunakan metode pembuktian induksi matematika untuk menguji pernyataan matematis dan Menggunakan metode pembuktian induksi matematika untuk menguji pernyataan matematis',
                    ],
                    [
                        'no' => 5,
                        'mapel' => 'Sejarah Indonesia',
                        'kkm' => 75,
                        'nilai' => 78,
                        'predikat' => 'B',
                        'deskripsi' => 'Mengolah informasi tentang proses masuk dan perkembangan penjajahan bangsa Eropa dan Mengolah informasi tentang proses masuk dan perkembangan penjajahan bangsa Eropa',
                    ],
                    [
                        'no' => 6,
                        'mapel' => 'Bahasa Inggris',
                        'kkm' => 75,
                        'nilai' => 77,
                        'predikat' => 'B',
                        'deskripsi' => 'Menyusun teks interaksi transaksional terkait saran dan tawaran dan Menyusun teks interaksi transaksional terkait saran dan tawaran',
                    ],
                ],
                'kelompok_b' => [
                    [
                        'no' => 7,
                        'mapel' => 'Seni Budaya',
                        'kkm' => 75,
                        'nilai' => 78,
                        'predikat' => 'B',
                        'deskripsi' => 'Membuat karya seni rupa dua dimensi dengan memodifikasi objek dan Membuat karya seni rupa dua dimensi dengan memodifikasi objek',
                    ],
                    [
                        'no' => 8,
                        'mapel' => 'Penjasorkes',
                        'kkm' => 75,
                        'nilai' => 78,
                        'predikat' => 'B',
                        'deskripsi' => 'Mempraktikkan hasil analisis keterampilan gerak salah satu permainan bola besar dan Mempraktikkan hasil analisis keterampilan gerak salah satu permainan bola besar',
                    ],
                ],
                'peminatan' => [
                    [
                        'no' => 9,
                        'mapel' => 'Geografi',
                        'kkm' => 75,
                        'nilai' => 77,
                        'predikat' => 'B',
                        'deskripsi' => 'Menyajikan contoh hasil penalaran tentang posisi strategis wilayah Indonesia dan Menyajikan contoh hasil penalaran tentang posisi strategis wilayah Indonesia',
                    ],
                    [
                        'no' => 10,
                        'mapel' => 'Sejarah',
                        'kkm' => 75,
                        'nilai' => 78,
                        'predikat' => 'B',
                        'deskripsi' => 'Menyajikan analisis tentang kerajaan maritim Indonesia pada masa Hindu Buddha dan Menyajikan analisis tentang kerajaan maritim Indonesia pada masa Hindu Buddha',
                    ],
                    [
                        'no' => 11,
                        'mapel' => 'Sosiologi',
                        'kkm' => 75,
                        'nilai' => 78,
                        'predikat' => 'B',
                        'deskripsi' => 'Menalar tentang terjadinya pengelompokan sosial di masyarakat dan Menalar tentang terjadinya pengelompokan sosial di masyarakat',
                    ],
                    [
                        'no' => 12,
                        'mapel' => 'Ekonomi',
                        'kkm' => 75,
                        'nilai' => 78,
                        'predikat' => 'B',
                        'deskripsi' => 'Menyajikan hasil penghitungan pendapatan nasional dan Menyajikan hasil penghitungan pendapatan nasional',
                    ],
                ],
                'tambahan' => [
                    [
                        'no' => 13,
                        'mapel' => 'Kemuhammadiyahan',
                        'kkm' => 75,
                        'nilai' => 78,
                        'predikat' => 'B',
                        'deskripsi' => 'Menyajikan periodesasi dalam dinamika perjuangan Muhammadiyah dan Menyajikan periodesasi dalam dinamika perjuangan Muhammadiyah',
                    ],
                    [
                        'no' => 14,
                        'mapel' => 'Bahasa Arab',
                        'kkm' => 75,
                        'nilai' => 78,
                        'predikat' => 'B',
                        'deskripsi' => 'Menggunakan tindak tutur kemampuan pada teks interaksi interpersonal dan Menggunakan tindak tutur kemampuan pada teks interaksi interpersonal',
                    ],
                    [
                        'no' => 15,
                        'mapel' => 'Teknologi Informasi dan Komunikasi',
                        'kkm' => 75,
                        'nilai' => 78,
                        'predikat' => 'B',
                        'deskripsi' => 'Memrogram dan merakit piranti sederhana embedded system (berangkat dari contoh yang sudah ada) yang bersifat tepat guna dan Memrogram dan merakit piranti sederhana embedded system (berangkat dari contoh yang sudah ada) yang bersifat tepat guna',
                    ],
                ],
            ],
            'total_keterampilan' => 1463,
            'ekstrakurikuler' => [
                ['no' => 1, 'kegiatan' => 'HIZBUL WATHAN', 'keterangan' => 'B'],
                ['no' => 2, 'kegiatan' => 'TAPAK SUCI', 'keterangan' => 'B'],
                ['no' => 3, 'kegiatan' => '-', 'keterangan' => '0'],
                ['no' => 4, 'kegiatan' => '-', 'keterangan' => '0'],
            ],
            'prestasi' => [
                ['no' => 1, 'jenis' => '-', 'keterangan' => '0'],
                ['no' => 2, 'jenis' => '-', 'keterangan' => '0'],
                ['no' => 3, 'jenis' => '-', 'keterangan' => '0'],
                ['no' => 4, 'jenis' => '-', 'keterangan' => '0'],
            ],
            'ketidakhadiran' => [
                'sakit' => 0,
                'izin' => 0,
                'tanpa_keterangan' => 5,
            ],
            'catatan' => 'Selalu berusaha untuk mematuhi tata tertib sekolah dan patuh terhadap Guru.',
            'tanggapan' => '',
            'ranking' => [
                'ke' => 17,
                'dari' => 19,
            ],
            'ttd' => [
                'tanggal' => 'Kota Bogor, 23 Desember 2023',
                'orang_tua' => 'YIYI SUPRIADI',
                'wali_kelas' => 'Kokoy Rokoyah, SE.',
                'kepala_sekolah' => 'DRS MOCH ZAENAL AL AQILI',
            ],
        ];

        $pdf = Pdf::loadView('rapor', compact('rapor'))->setPaper('a4', 'portrait');

        return $pdf->download('rapor.pdf');
    }
}

