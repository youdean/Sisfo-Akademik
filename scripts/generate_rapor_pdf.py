from reportlab.lib.pagesizes import A4
from reportlab.pdfgen import canvas
from reportlab.lib.units import cm


def generate_pdf(filename: str):
    c = canvas.Canvas(filename, pagesize=A4)
    width, height = A4

    text = c.beginText(2 * cm, height - 2 * cm)
    lines = [
        "RAPOR PESERTA DIDIK",
        "",
        "Nama Sekolah : SMAS MUHAMMADIYAH",
        "Alamat : Jl. Merdeka No. 118",
        "Nama Siswa : MUHAMMAD HAIKAL FIRDAUS",
        "No. Induk/NISN : 2223.10.023 / 0064177255",
        "Kelas/Semester : XI IPS / 3 (Ganjil)",
        "Tahun Pelajaran : 2023-2024",
        "",
        "A. SIKAP",
        "Spiritual : Baik",
        "  Selalu berdoa sebelum dan sesudah kegiatan, menjalankan ibadah sesuai agama,",
        "  dan membiasakan salam.",
        "Sosial : Baik",
        "  Jujur, bertanggung jawab, santun, percaya diri; disiplin mulai meningkat.",
        "",
        "B. PENGETAHUAN",
        "  Pendidikan Agama dan Budi Pekerti : 77 (B)",
        "  PPKn : 77 (B)",
        "  Bahasa Indonesia : 77 (B)",
        "  Matematika : 77 (B)",
        "  Sejarah Indonesia : 78 (B)",
        "  Bahasa Inggris : 77 (B)",
        "  Seni Budaya : 78 (B)",
        "  Penjasorkes : 78 (B)",
        "  Geografi : 77 (B)",
        "  Sejarah : 78 (B)",
        "  Sosiologi : 78 (B)",
        "  Ekonomi : 78 (B)",
        "  Kemuhammadiyahan : 78 (B)",
        "  Bahasa Arab : 78 (B)",
        "  Teknologi Informasi dan Komunikasi : 78 (B)",
        "Total Nilai Pengetahuan : 1164",
        "",
        "C. KETERAMPILAN",
        "  Pendidikan Agama dan Budi Pekerti : 77 (B)",
        "  PPKn : 77 (B)",
        "  Bahasa Indonesia : 77 (B)",
        "  Matematika : 77 (B)",
        "  Sejarah Indonesia : 78 (B)",
        "  Bahasa Inggris : 77 (B)",
        "  Seni Budaya : 78 (B)",
        "  Penjasorkes : 78 (B)",
        "  Geografi : 77 (B)",
        "  Sejarah : 78 (B)",
        "  Sosiologi : 78 (B)",
        "  Ekonomi : 78 (B)",
        "  Kemuhammadiyahan : 78 (B)",
        "  Bahasa Arab : 78 (B)",
        "  Teknologi Informasi dan Komunikasi : 78 (B)",
        "Total Nilai Keterampilan : 1463",
        "",
        "D. EKSTRAKURIKULER",
        "  Hizbul Wathan : B",
        "  Tapak Suci : B",
        "",
        "E. PRESTASI",
        "  -",
        "",
        "F. KETIDAKHADIRAN",
        "  Sakit : 0 hari",
        "  Izin : 0 hari",
        "  Tanpa Keterangan : 5 hari",
        "",
        "F. CATATAN WALI KELAS",
        "  Selalu berusaha untuk mematuhi tata tertib sekolah dan patuh terhadap guru.",
        "",
        "Rangking : 17 dari 19 siswa",
        "KKM : 75",
    ]

    for line in lines:
        text.textLine(line)

    c.drawText(text)
    c.showPage()
    c.save()


def main():
    generate_pdf("rapor_example.pdf")


if __name__ == "__main__":
    main()
