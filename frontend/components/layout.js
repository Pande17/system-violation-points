import { putAPI, getAPI } from '../../utils/fetchData.js';

export async function loadComponents(basePrefix = '../../') {
    try {
        const [sidebarRes, headerRes, footerRes] = await Promise.all([
            fetch(`${basePrefix}components/sidebar.html`),
            fetch(`${basePrefix}components/header.html`),
            fetch(`${basePrefix}components/footer.html`)
        ]);

        const sidebarContainer = document.getElementById('sidebar-container');
        const headerContainer = document.getElementById('header-container');
        const footerContainer = document.getElementById('footer-container');

        if (sidebarContainer) {
            let sidebarHtml = await sidebarRes.text();
            
            // Adjust prefix for nested routes
            if (basePrefix !== '../../') {
                const pageBase = `${basePrefix}pages/admin/`;
                sidebarHtml = sidebarHtml.replaceAll('href="', `href="${pageBase}`);
                sidebarHtml = sidebarHtml.replaceAll('src="../../', `src="${basePrefix}`);
                
                // Allow surat links to remain nested
                sidebarHtml = sidebarHtml.replaceAll(`href="${pageBase}surat/`, `href="${basePrefix}pages/admin/surat/`);
            }
            
            sidebarContainer.innerHTML = sidebarHtml;
        }
        
        if (headerContainer) headerContainer.innerHTML = await headerRes.text();
        if (footerContainer) footerContainer.innerHTML = await footerRes.text();

        // Highlight active link in sidebar
        const currentPath = window.location.pathname;
        const filename = currentPath.split('/').pop() || 'dashboard.html';
        const pageTitles = {
            'dashboard.html': 'Dashboard',
            'siswa.html': 'Data Siswa',
            'guru.html': 'Data Guru',
            'kelas.html': 'Data Kelas',
            'jurusan.html': 'Data Jurusan',
            'jenis_pelanggaran.html': 'Jenis Pelanggaran',
            'pelanggaran.html': 'Input Pelanggaran',
            'cetak-surat.html': 'Cetak Surat Baru',
            'daftar-surat.html': 'Arsip Surat',
            'form.html': 'Cetak Surat',
            'laporan.html': 'Rekapitulasi Pelanggaran',
            'profile.html': 'Profil Saya'
        };

        const pageTitleEl = document.getElementById('pageTitle');
        if (pageTitleEl) pageTitleEl.textContent = pageTitles[filename] || 'Overview';

        document.querySelectorAll('.nav-link').forEach(a => {
            const href = a.getAttribute('href');
            if (!href) return;
            const normalizedHref = href.replace('../../', '');
            const targetFilename = normalizedHref.split('/').pop();
            
            // Stricter comparison using the extracted filename to avoid 'jenis_pelanggaran' matching 'pelanggaran'
            const isActive = filename === targetFilename;

            if (isActive) {
                a.classList.add('bg-white/10', 'text-white');
                a.classList.remove('text-white/70', 'hover:bg-white/5');
            } else if (!a.id || a.id !== 'suratDropdownBtn') {
                a.classList.remove('bg-white/10', 'text-white');
                a.classList.add('text-white/70', 'hover:bg-white/5');
            }
        });

        // Surat Dropdown Toggle
        const suratDropdownBtn = document.getElementById('suratDropdownBtn');
        const suratDropdownMenu = document.getElementById('suratDropdownMenu');
        const suratChevron = document.getElementById('suratChevron');

        if (suratDropdownBtn && suratDropdownMenu) {
            suratDropdownBtn.addEventListener('click', (e) => {
                e.preventDefault();
                suratDropdownMenu.classList.toggle('hidden');
                if (suratChevron) {
                    suratChevron.classList.toggle('rotate-180');
                }
            });

            // Auto open if active
            const isSuratActive = currentPath.includes('cetak-surat.html') ||
                currentPath.includes('daftar-surat.html') ||
                currentPath.includes('/surat/');

            if (isSuratActive) {
                suratDropdownMenu.classList.remove('hidden');
                if (suratChevron) suratChevron.classList.add('rotate-180');
                suratDropdownBtn.classList.add('bg-white/10', 'text-white');
                suratDropdownBtn.classList.remove('text-white/70');
            }
        }

        // Set user name
        const userStr = localStorage.getItem('user');
        if (userStr) {
            const user = JSON.parse(userStr);
            const adminNameEl = document.getElementById('adminName');
            if (adminNameEl) adminNameEl.textContent = user.username;
        }

        // Sidebar Burger Toggle
        const burgerBtn = document.getElementById('burgerBtn');
        const sidebar = document.getElementById('sidebar');
        if (burgerBtn && sidebar) {
            burgerBtn.addEventListener('click', () => {
                sidebar.classList.toggle('w-[280px]');
                sidebar.classList.toggle('w-[88px]');
                const sidebarTexts = document.querySelectorAll('.sidebar-text');
                sidebarTexts.forEach(el => {
                    if (sidebar.classList.contains('w-[88px]')) {
                        el.classList.add('opacity-0', 'pointer-events-none');
                        setTimeout(() => el.classList.add('hidden'), 200);
                    } else {
                        // Only remove hidden and show if NOT role-restricted (Request #12 fix)
                        if (!el.classList.contains('role-restricted')) {
                            el.classList.remove('hidden');
                            setTimeout(() => el.classList.remove('opacity-0', 'pointer-events-none'), 10);
                        }
                    }
                });
            });
        }

        // Logout
        const logoutBtn = document.getElementById('logoutBtn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', () => {
                localStorage.removeItem('user');
                localStorage.removeItem('token');
                window.location.href = '../Login.html';
            });
        }

        // --- Role-Based Sidebar Visibility (Request #10) ---
        const userStrRaw = localStorage.getItem('user');
        if (userStrRaw) {
            const user = JSON.parse(userStrRaw);
            const role = user.role ? user.role.toLowerCase() : (user.type || '');
            
            const sidebarNavMain = document.getElementById('sidebarNavMain');
            const sidebarNavPelanggaran = document.getElementById('sidebarNavPelanggaran');
            const sidebarNavSurat = document.getElementById('sidebarNavSurat');
            const sidebarNavLaporan = document.getElementById('sidebarNavLaporan');

            const textMenuUtama = sidebarNavMain?.previousElementSibling;
            const textManajemenPelanggaran = sidebarNavPelanggaran?.previousElementSibling;
            const textAdministrasiSurat = sidebarNavSurat?.previousElementSibling;
            const textLaporanRekap = sidebarNavLaporan?.previousElementSibling;

            if (role === 'guru mapel') {
                // Cannot access Rekap Pelanggaran
                if (sidebarNavLaporan) sidebarNavLaporan.classList.add('hidden', 'role-restricted');
                if (textLaporanRekap) textLaporanRekap.classList.add('hidden', 'role-restricted');

                // Cannot access Cetak Surat, only Riwayat Surat
                if (sidebarNavSurat) {
                    const cetakLinks = sidebarNavSurat.querySelectorAll('a[href*="cetak-surat.html"], a[href*="/form.html"]');
                    cetakLinks.forEach(link => link.classList.add('hidden', 'role-restricted'));
                    
                    // Hide the HR separator
                    const hr = sidebarNavSurat.querySelector('hr');
                    if (hr) hr.classList.add('hidden', 'role-restricted');
                }
            } else if (role === 'siswa') {
                // Students only see Dashboard and Profile
                if (sidebarNavPelanggaran) sidebarNavPelanggaran.classList.add('hidden', 'role-restricted');
                if (textManajemenPelanggaran) textManajemenPelanggaran.classList.add('hidden', 'role-restricted');
                if (sidebarNavSurat) sidebarNavSurat.classList.add('hidden', 'role-restricted');
                if (textAdministrasiSurat) textAdministrasiSurat.classList.add('hidden', 'role-restricted');
                if (sidebarNavLaporan) sidebarNavLaporan.classList.add('hidden', 'role-restricted');
                if (textLaporanRekap) textLaporanRekap.classList.add('hidden', 'role-restricted');

                // Hide other main menu items except Dashboard
                if (sidebarNavMain) {
                    const mainLinks = sidebarNavMain.querySelectorAll('a:not([href*="dashboard.html"])');
                    mainLinks.forEach(link => {
                        if (link.id !== 'navProfileSiswa') {
                            link.classList.add('hidden', 'role-restricted');
                        }
                    });

                    // Add Profile Navigation for student (Requested in #10)
                    const profileLinkExists = sidebarNavMain.querySelector('a[id="navProfileSiswa"]');
                    if (!profileLinkExists) {
                        const profileLink = document.createElement('a');
                        profileLink.id = 'navProfileSiswa';
                        profileLink.href = 'profile.html';
                        profileLink.className = 'nav-link flex items-center gap-3 px-4 py-3 text-white/70 hover:text-white hover:bg-white/5 rounded-xl font-medium transition-all group overflow-hidden';
                        profileLink.innerHTML = `
                            <i class="fas fa-user-circle w-5 text-center shrink-0 transition-colors"></i>
                            <span class="sidebar-text whitespace-nowrap transition-opacity duration-300">Profile</span>
                        `;
                        sidebarNavMain.appendChild(profileLink);
                    }
                }

                // Disable header profile button for students (Request #11)
                const userProfileBtn = document.getElementById('userProfileBtn');
                if (userProfileBtn) {
                    userProfileBtn.classList.remove('cursor-pointer', 'hover:bg-gray-50', 'hover:border-gray-100');
                    userProfileBtn.classList.add('cursor-default', 'opacity-90', 'pointer-events-none');
                    const chevron = userProfileBtn.querySelector('.fa-chevron-down');
                    if (chevron) chevron.classList.add('hidden');
                }
            }
        }

        // --- Move Modals to Body to avoid stacking context issues ---
        const modalOverlay = document.getElementById('modalOverlay');
        const profileModal = document.getElementById('profileModal');
        const confirmModal = document.getElementById('confirmModal');

        if (modalOverlay) document.body.appendChild(modalOverlay);
        if (profileModal) document.body.appendChild(profileModal);
        if (confirmModal) document.body.appendChild(confirmModal);

        // --- Profile & Confirmation Modal Logic ---
        const userProfileBtn = document.getElementById('userProfileBtn');
        const closeProfileBtn = document.getElementById('closeProfileBtn');
        const cancelProfileBtn = document.getElementById('cancelProfileBtn');
        const saveProfileBtn = document.getElementById('saveProfileBtn');

        const confirmOverlay = document.getElementById('confirmOverlay');
        const cancelConfirmBtn = document.getElementById('cancelConfirmBtn');
        const proceedConfirmBtn = document.getElementById('proceedConfirmBtn');

        const profileUsername = document.getElementById('profileUsername');
        const profilePassword = document.getElementById('profilePassword');
        const toggleProfilePassword = document.getElementById('toggleProfilePassword');
        const profileNama = document.getElementById('profileNama');
        const profileEmail = document.getElementById('profileEmail');

        const guruOnlyFields = document.getElementById('guruOnlyFields');
        const profileKodeGuru = document.getElementById('profileKodeGuru');
        const profileRole = document.getElementById('profileRole');

        const siswaOnlyFields = document.getElementById('siswaOnlyFields');
        const profileNIS = document.getElementById('profileNIS');
        const profileKelas = document.getElementById('profileKelas');
        const profileJurusan = document.getElementById('profileJurusan');
        const siswaAddressField = document.getElementById('siswaAddressField');
        const profileAlamat = document.getElementById('profileAlamat');

        const openModal = (modal, overlay) => {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            overlay.classList.remove('hidden');
            setTimeout(() => {
                overlay.classList.add('opacity-100');
                overlay.classList.remove('opacity-0');
                const content = modal.querySelector('div:not(.absolute)');
                content.classList.remove('scale-90', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 50);
        };

        const closeModal = (modal, overlay, keepOverlay = false) => {
            if (!keepOverlay) {
                overlay.classList.remove('opacity-100');
                overlay.classList.add('opacity-0');
            }
            const content = modal.querySelector('div:not(.absolute)');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-90', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                if (!keepOverlay) overlay.classList.add('hidden');
            }, 300);
        };

        if (userProfileBtn && profileModal && confirmModal) {
            userProfileBtn.addEventListener('click', async () => {
                openModal(profileModal, modalOverlay);

                if (userStr) {
                    const user = JSON.parse(userStr);
                    const endpoint = user.type === 'siswa' ? '/siswa' : '/guru';
                    try {
                        const res = await getAPI(`${endpoint}?id=${user.id}`);
                        if (res && res.status === 200 && res.data) {
                            const data = res.data;
                            profileUsername.value = data.username || '';
                            profileNama.value = data.nama || '';
                            profileEmail.value = data.email || '';
                            profilePassword.value = '';

                            if (user.type !== 'siswa') {
                                guruOnlyFields.classList.remove('hidden');
                                siswaOnlyFields.classList.add('hidden');
                                siswaAddressField.classList.add('hidden');
                                profileKodeGuru.value = data.kode_guru || '';
                                profileRole.value = data.role || '';
                            } else {
                                guruOnlyFields.classList.add('hidden');
                                siswaOnlyFields.classList.remove('hidden');
                                siswaAddressField.classList.remove('hidden');
                                profileNIS.value = data.nis || '';
                                profileKelas.value = data.kelas || '';
                                profileJurusan.value = data.jurusan || '';
                                profileAlamat.value = data.alamat || '';
                            }
                        }
                    } catch (error) {
                        console.error("Gagal mengambil data profil:", error);
                    }
                }
            });

            closeProfileBtn.addEventListener('click', () => closeModal(profileModal, modalOverlay));
            cancelProfileBtn.addEventListener('click', () => closeModal(profileModal, modalOverlay));

            toggleProfilePassword.addEventListener('click', () => {
                const type = profilePassword.getAttribute('type') === 'password' ? 'text' : 'password';
                profilePassword.setAttribute('type', type);
                toggleProfilePassword.innerHTML = type === 'password' ? '<i class="fas fa-eye text-sm"></i>' : '<i class="fas fa-eye-slash text-sm"></i>';
            });

            saveProfileBtn.addEventListener('click', () => {
                if (!profileUsername.value.trim()) { alert("Username tidak boleh kosong"); return; }
                openModal(confirmModal, confirmOverlay);
            });

            cancelConfirmBtn.addEventListener('click', () => {
                closeModal(confirmModal, confirmOverlay);
            });

            proceedConfirmBtn.addEventListener('click', async () => {
                try {
                    const user = JSON.parse(localStorage.getItem('user'));
                    const endpoint = user.type === 'siswa' ? '/siswa' : '/guru';
                    const getRes = await getAPI(`${endpoint}?id=${user.id}`);
                    if (!getRes || getRes.status !== 200) { alert("Gagal memuat data terbaru."); return; }

                    const payload = getRes.data;
                    payload.username = profileUsername.value;
                    payload.nama = profileNama.value;
                    payload.email = profileEmail.value;
                    if (profilePassword.value) payload.password = profilePassword.value;
                    else payload.password = '';

                    if (user.type === 'siswa') payload.alamat = profileAlamat.value;
                    else payload.kode_guru = payload.kode_guru || payload.kodeGuru || '0021.000';

                    const res = await putAPI(endpoint, payload);
                    if (res && (res.status === 200 || res.status === true)) {
                        user.username = payload.username;
                        localStorage.setItem('user', JSON.stringify(user));
                        const adminNameEl = document.getElementById('adminName');
                        if (adminNameEl) adminNameEl.textContent = user.username;
                        closeModal(confirmModal, confirmOverlay);
                        closeModal(profileModal, modalOverlay);
                    } else {
                        alert(res.message || "Gagal memperbarui profil.");
                    }
                } catch (error) {
                    console.error("Error updating profile:", error);
                    alert("Terjadi kesalahan sistem saat memperbarui profil.");
                }
            });
        }
    } catch (error) {
        console.error('Error loading components:', error);
    }
}
