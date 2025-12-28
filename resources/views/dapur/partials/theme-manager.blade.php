{{-- Alpine.js Theme Manager Script --}}
@push('head')
<script>
    {{-- Initialize theme immediately in head --}}
    (function() {
        try {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        } catch(e) {
            console.error('Theme initialization error:', e);
        }
    })();
</script>
@endpush

@push('scripts')
<script>
    {{-- Theme Manager Alpine.js Component --}}
    document.addEventListener('alpine:init', () => {
        Alpine.data('themeManager', () => ({
            sidebarCollapsed: false,
            sidebarHover: false,
            darkMode: false, // Will be set in initTheme()

            initTheme() {
                // Set initial theme based on cookie, localStorage, or system preference
                const cookieTheme = document.cookie.split('; ').find(row => row.startsWith('theme='))?.split('=')[1];
                const savedTheme = localStorage.getItem('theme');
                const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                
                // Prioritize cookie, then localStorage, then system preference
                const theme = cookieTheme || savedTheme || (prefersDark ? 'dark' : 'light');
                
                this.darkMode = theme === 'dark';
                
                if (this.darkMode) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
                
                // Sync localStorage with cookie
                if (cookieTheme && cookieTheme !== savedTheme) {
                    localStorage.setItem('theme', cookieTheme);
                }
                
                console.log('Theme initialized:', this.darkMode ? 'dark' : 'light', 'Saved:', savedTheme, 'Cookie:', cookieTheme);
            },

            toggleTheme() {
                this.darkMode = !this.darkMode;
                const theme = this.darkMode ? 'dark' : 'light';
                localStorage.setItem('theme', theme);
                document.cookie = `theme=${theme}; path=/; max-age=31536000`; // Set cookie
                if (this.darkMode) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
                this.$nextTick(() => {
                    console.log('Theme toggled:', theme);
                    console.log('HTML has dark class:', document.documentElement.classList.contains('dark'));
                });
            },

            initSidebarState() {
                // Load sidebar collapsed state from localStorage
                const savedState = localStorage.getItem('sidebarCollapsed');
                if (savedState === 'true') {
                    this.sidebarCollapsed = true;
                }
            },

            toggleSidebar() {
                this.sidebarCollapsed = !this.sidebarCollapsed;
                localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed);
            },

            handleLogout() {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Confirm Logout',
                        text: 'Are you sure you want to logout?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#fa9a08',
                        cancelButtonColor: '#1e1e1e',
                        confirmButtonText: 'Yes, Sign Out',
                        background: this.darkMode ? '#0A0A0A' : '#fff',
                        color: this.darkMode ? '#fff' : '#000',
                        customClass: {
                            popup: 'rounded-lg border border-white/5',
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const logoutForm = document.getElementById('logout-form');
                            if (logoutForm) {
                                logoutForm.submit();
                            } else {
                                // Fallback: create and submit form dynamically
                                const form = document.createElement('form');
                                form.method = 'POST';
                                form.action = '{{ route("logout") }}';
                                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                                if (csrfToken) {
                                    const csrfInput = document.createElement('input');
                                    csrfInput.type = 'hidden';
                                    csrfInput.name = '_token';
                                    csrfInput.value = csrfToken.getAttribute('content');
                                    form.appendChild(csrfInput);
                                }
                                document.body.appendChild(form);
                                form.submit();
                            }
                        }
                    });
                } else {
                    // If SweetAlert is not available, submit logout form directly
                    const logoutForm = document.getElementById('logout-form');
                    if (logoutForm) {
                        logoutForm.submit();
                    }
                }
            }
        }));
    });
</script>
@endpush

