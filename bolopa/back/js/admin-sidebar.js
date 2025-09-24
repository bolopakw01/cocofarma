/* Admin sidebar toggle behavior
   - Toggles .open on .admin-sidebar
   - Persists state in localStorage key: 'sidebar-open' ('1' open, '0' closed)
   - Updates toggle icon (bi-chevron-left / bi-chevron-right) and aria-expanded
   - Keyboard shortcut: Ctrl/Cmd + B to toggle
*/
(function(){
    document.addEventListener('DOMContentLoaded', function(){
        var toggle = document.getElementById('sidebarToggle');
        if (!toggle) return;
        var sidebar = document.querySelector('.admin-sidebar');
        var state = localStorage.getItem('sidebar-open');
        var icon = toggle.querySelector('i');
        if (state === '1') {
            sidebar.classList.add('open');
            if(icon) { icon.classList.remove('bi-chevron-left'); icon.classList.add('bi-chevron-right'); }
            toggle.setAttribute('aria-expanded','true');
        } else {
            toggle.setAttribute('aria-expanded','false');
        }

        toggle.addEventListener('click', function(e){
            var isOpen = sidebar.classList.toggle('open');
            localStorage.setItem('sidebar-open', isOpen ? '1' : '0');
            if(icon) {
                if(isOpen) { icon.classList.remove('bi-chevron-left'); icon.classList.add('bi-chevron-right'); }
                else { icon.classList.remove('bi-chevron-right'); icon.classList.add('bi-chevron-left'); }
            }
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        // Keyboard shortcut Ctrl/Cmd + B to toggle
        document.addEventListener('keydown', function(e){
            var meta = e.ctrlKey || e.metaKey;
            if(!meta) return;
            if(e.key === 'b' || e.key === 'B'){
                e.preventDefault();
                var isOpen = sidebar.classList.toggle('open');
                localStorage.setItem('sidebar-open', isOpen ? '1' : '0');
                if(icon) {
                    if(isOpen) { icon.classList.remove('bi-chevron-left'); icon.classList.add('bi-chevron-right'); }
                    else { icon.classList.remove('bi-chevron-right'); icon.classList.add('bi-chevron-left'); }
                }
                toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            }
        });
    });
})();
