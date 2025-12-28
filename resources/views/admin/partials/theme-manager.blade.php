{{-- Alpine.js Theme Manager Script untuk Admin --}}
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('themeManager', () => ({
            darkMode: localStorage.getItem('theme') === 'dark' ||
                (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches),

            toggleTheme() {
                this.darkMode = !this.darkMode;
                const theme = this.darkMode ? 'dark' : 'light';
                
                // Set localStorage
                localStorage.setItem('theme', theme);
                
                // Set cookie untuk persist antar reload dan bisa dipakai server-side
                document.cookie = `theme=${theme}; path=/; max-age=31536000`;
                
                // Update DOM class
                if (this.darkMode) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            },

            handleLogout() {
                Swal.fire({
                    title: 'Confirm Logout',
                    text: "Sesi administrasi akan diakhiri.",
                    icon: 'warning',
                    showCancelButton: true,
                    background: this.darkMode ? '#0A0A0A' : '#fff',
                    color: this.darkMode ? '#fff' : '#000',
                    confirmButtonColor: '#fa9a08',
                    cancelButtonColor: '#1e1e1e',
                    confirmButtonText: 'Yes, Sign Out',
                    customClass: {
                        popup: 'rounded-lg border border-white/5',
                        confirmButton: 'rounded-md text-xs font-bold px-5 py-2.5',
                        cancelButton: 'rounded-md text-xs font-bold px-5 py-2.5'
                    }
                }).then((result) => {
                    if (result.isConfirmed) performLogout();
                });
            }
        }));
    });
</script>

