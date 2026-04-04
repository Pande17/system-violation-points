# Database Schema: poin_pelanggaran_siswa

## Tables and Columns

### 1. siswa
- `id`: int (PK)
- `username`: varchar(50)
- `password`: varchar(255)
- `nama`: varchar(100)
- `nis`: int
- `kelas`: varchar(20)
- `jurusan`: varchar(50)
- `jenis_kelamin`: varchar(20)
- `alamat`: varchar(255)
- `no_telp`: varchar(20)
- `email`: varchar(100)
- `id_ortuWali`: int (FK to ortu_wali.id)
- `poin`: int
- `status`: varchar(20)

### 2. jenis_pelanggaran
- `id`: int (PK)
- `kode_pelanggaran`: varchar(20)
- `nama_pelanggaran`: varchar(100)
- `poin`: int

### 3. pelanggaran
- `id`: int (PK)
- `id_jenis_pelanggaran`: int (FK to jenis_pelanggaran.id)
- `id_siswa`: int (FK to siswa.id)
- `poin`: int
- `keterangan`: varchar(255)
- `created_by`: int

### 4. ortu_wali
- `id`: int (PK)
- `nama`: varchar(100)
- `hubungan`: varchar(50)
- `no_telp`: varchar(20)
- `pekerjaan`: varchar(100)
- `alamat`: varchar(255)

### 5. surat
- `id`: int (PK)
- `jenis_surat`: varchar(50)
- `nomor_surat`: varchar(50)
- `tanggal_surat`: date
- `id_siswa`: int (FK to siswa.id)
- `keterangan`: varchar(255)
- `deskripsi`: varchar(255)
- `created_by`: int

### 6. laporan
- `id`: int (PK)
- `jenis_laporan`: varchar(50)
- `id_surat`: int (FK to surat.id)
- `keterangan`: varchar(255)
- `created_by`: int

### 7. guru
- `id`: int (PK)
- `username`: varchar(50)
- `password`: varchar(255)
- `nama`: varchar(100)
- `kode_guru`: varchar(20)
- `jenis_kelamin`: varchar(20)
- `email`: varchar(100)
- `role`: varchar(50)

### 8. jurusan & kelas (Master Data)
- `jurusan`: id, kode, nama, ketua_kompetensi.
- `kelas`: id, tingkat, jurusan, kelas, wali_kelas.

## Key Relationships (Entity Relationship)
1. **kelas.wali_kelas** ➔ **guru.id**
2. **siswa.id_ortuWali** ➔ **ortu_wali.id**
3. **pelanggaran.id_siswa** ➔ **siswa.id**
4. **pelanggaran.id_jenis_pelanggaran** ➔ **jenis_pelanggaran.id**
5. **surat.id_siswa** ➔ **siswa.id**
6. **laporan.id_surat** ➔ **surat.id**