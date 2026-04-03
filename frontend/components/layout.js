export async function loadComponents() {
    try {
        const [sidebarRes, headerRes, footerRes] = await Promise.all([
            fetch('../../components/sidebar.html'),
            fetch('../../components/header.html'),
            fetch('../../components/footer.html')
        ]);
        
        const sidebarContainer = document.getElementById('sidebar-container');
        const headerContainer = document.getElementById('header-container');
        const footerContainer = document.getElementById('footer-container');

        if (sidebarContainer) sidebarContainer.outerHTML = await sidebarRes.text();
        if (headerContainer) headerContainer.outerHTML = await headerRes.text();
        if (footerContainer) footerContainer.outerHTML = await footerRes.text();

        // Highlight active link in sidebar
        const path = window.location.pathname.split('/').pop() || 'dashboard.html';
        const pageTitles = {
            'dashboard.html': 'Dashboard',
            'siswa.html': 'Data Siswa',
            'guru.html': 'Data Guru',
            'kelas.html': 'Data Kelas & Jurusan',
            'jenis-pelanggaran.html': 'Jenis Pelanggaran',
            'pelanggaran.html': 'Input Pelanggaran'
        };
        
        const pageTitleEl = document.getElementById('pageTitle');
        if (pageTitleEl) pageTitleEl.textContent = pageTitles[path] || 'Overview';

        document.querySelectorAll('.nav-link').forEach(a => {
            if (a.getAttribute('data-page') === path) {
                a.classList.add('bg-white/10', 'text-white');
                a.classList.remove('text-white/70', 'hover:bg-white/5');
            } else {
                a.classList.remove('bg-white/10', 'text-white');
                a.classList.add('text-white/70', 'hover:bg-white/5');
            }
        });

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
                        el.classList.remove('hidden');
                        setTimeout(() => el.classList.remove('opacity-0', 'pointer-events-none'), 10);
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
    } catch (error) {
        console.error('Error loading components:', error);
    }
}
